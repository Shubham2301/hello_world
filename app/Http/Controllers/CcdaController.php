<?php

namespace myocuhub\Http\Controllers;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Ccda;
use myocuhub\Models\Vital;
use myocuhub\Patient;
use \DOMDocument;
use \XSLTProcessor;
use MyCCDA;

class CcdaController extends Controller
{
	private $ccdaPaths = [];
	public function __construct()
	{
		$this->ccdaPaths = config('constants.paths.ccda');
	}

	public function index()
	{
		return view('ccda.ccdatest');
	}

	public function save(Request $request)
	{
		$data = [];
		if ($request->hasFile('patient_ccda')) {
			$file = $request->file('patient_ccda');
			$jsonstring = MyCCDA::generateJson($file);

			if(!$jsonstring)
			{
				$data['error'] = true;
				return json_encode($data);
			}
			$ccda = new Ccda;
			$ccda->ccdablob = $jsonstring;
			$ccda->patient_id = $request->patient_id;
			if ($ccda->save()) {
				$data['error']   = false;
				$data['patient'] = $this->getPatientData($ccda->patient_id);
				$data['ccda']    = $this->getCCDAData(json_decode($ccda->ccdablob, true));

				$action = 'saved new CCDA';
				$description = '';
				$filename = basename(__FILE__);
				$ip = $request->getClientIp();
				Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
				return json_encode($data);
			}
		}
		$data['error'] = true;
		return json_encode($data);
		;
	}

	public function getCCDAXml($patient_id)
	{
		$ccdafile =  MyCCDA::genrateXml($patient_id, true);
		if ($ccdafile == false) {
			return 'nofile';
		}
		$headers = array(
			'Content-Type: application/xml',
		);
		return Response()->download($ccdafile, 'Patient-'.$patient_id.'.xml', $headers)->deleteFileAfterSend(true);
	}

	public function validateKey($data, $key)
	{
		if (array_key_exists($key, $data)) {
			return $data[$key];
		}
		return null;
	}

	public function getCCDAData($ccdaData)
	{
		$data = [];
		$data['title'] =     $ccdaData['demographics']['name']['prefix'];
		$data['firstname'] = $this->validateKey($ccdaData['demographics']['name']['given'], 0);
		$data['addressline1'] = $this->validateKey($ccdaData['demographics']['address']['street'], 0);
		$data['addressline2'] = $this->validateKey($ccdaData['demographics']['address']['street'], 1);
		$data['lastname'] =  $ccdaData['demographics']['name']['family'];
		$data['workphone'] = $ccdaData['demographics']['phone']['work'];
		$data['homephone'] = $ccdaData['demographics']['phone']['home'];
		$data['cellphone'] = $ccdaData['demographics']['phone']['mobile'];
		$data['email']     = $ccdaData['demographics']['email'];
		$data['city']      = $ccdaData['demographics']['address']['city'];
		$data['zip']       = $ccdaData['demographics']['address']['zip'];
		$data['country']   = $ccdaData['demographics']['address']['country'];
		$data['birthdate'] = date('Y-m-d', strtotime($ccdaData['demographics']['dob']));
		$data['state'] =       $ccdaData['demographics']['address']['state'];
		$data['gender']   = $ccdaData['demographics']['gender'];
		$data['preferredlanguage'] = $ccdaData['demographics']['language'];

		if($data['gender'])
		{
			$data['gender'] = 'Female';
			if(stripos($data['gender'],'male') > -1)
			{
				$data['gender'] = 'Male';
			}
		}

		if($data['preferredlanguage'])
		{
			$data['preferredlanguage'] = 'English';

			if(stripos($data['preferredlanguage'],'Spanish')> -1)
			{
				$data['preferredlanguage'] = 'Spanish';
			}
		}

		return $data;
	}

	public function getPatientData($patient_id)
	{
		$data = [];
		$patient_data = Patient::find($patient_id)->toArray();
		if ($patient_data) {
			$data['id'] = $patient_id;
			$data['title'] = $patient_data['title'];
			$data['firstname'] = $patient_data['firstname'];
			$data['lastname'] = $patient_data['lastname'];
			$data['workphone'] = $patient_data['workphone'];
			$data['homephone'] = $patient_data['homephone'];
			$data['cellphone'] = $patient_data['cellphone'];
			$data['email'] = $patient_data['email'];
			$data['addressline1'] = $patient_data['addressline1'];
			$data['addressline2'] = $patient_data['addressline2'];
			$data['city'] = $patient_data['city'];
			$data['zip'] = $patient_data['zip'];
			$data['country'] = $patient_data['country'];
			$data['birthdate'] = date('Y-m-d', strtotime($patient_data['birthdate']));
			$data['gender'] = $patient_data['gender'];
			$data['preferredlanguage'] = $patient_data['preferredlanguage'];
			return $data;
		}
	}

	public function updatePatientDemographics(Request $request)
	{
		$data = $request->all();
		$patient_id = $data['patient_id'];
		unset($data['patient_id']);
		unset($data['_token']);
		$patient = Patient::find($patient_id);
		if ($patient) {
			$patient->update($data);

			$action = 'updated patient demographics';
			$description = '';
			$filename = basename(__FILE__);
			$ip = $request->getClientIp();
			Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

			return $patient_id;
		}
		return 'false';
	}

	public function show($patient_id)
	{
		$ccdafile   = MyCCDA::genrateXml($patient_id);

		$xml = new DOMDocument;
		$xml->load($ccdafile);

		$xsl = new DOMDocument;
		$xsl->load($this->ccdaPaths['stylesheet']);

		$proc = new XSLTProcessor;
		$proc->importStyleSheet($xsl);
		$transformed_xml = $proc->transformToXML($xml);
		unlink($ccdafile);
		return $transformed_xml;
	}

	public function ccdaDataForPatientForm(Request $request)
	{
		if ($request->hasFile('patient_ccda')) {
			$file = $request->file('patient_ccda');
			$jsonstring = MyCCDA::generateJson($file);
			if(!$jsonstring)
			{
				$data['error'] = true;
				return json_encode($data);
			}
			$data = $this->getCCDAData(json_decode($jsonstring, true));
			return json_encode($data);
		}
	}
}
