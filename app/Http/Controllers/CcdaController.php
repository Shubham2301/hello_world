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
use myocuhub\Http\Controllers\Traits\CCDATrait;

class CcdaController extends Controller
{
    use CCDATrait;

    private $ccdaPaths = [];
    public function __construct()
    {
        $this->ccdaPaths = config('constants.paths.ccda');
    }

    public function index()
    {
        return view('ccda.ccdatest');
    }

    public function getCCDAXml($patient_id)
    {
        $ccdafile =  MyCCDA::generateXml($patient_id, true);
        if ($ccdafile == false) {
            return 'nofile';
        }
        $headers = array(
            'Content-Type: application/xml',
        );
        return Response()->download($ccdafile, 'Patient-'.$patient_id.'.xml', $headers)->deleteFileAfterSend(true);
    }

    public function show($patient_id)
    {
        $ccdafile   = MyCCDA::generateXml($patient_id);

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
            $data['error'] = false;
            if (!$jsonstring) {
                $data['error'] = true;
                return json_encode($data);
            }

            $data['patient'] = [];
            $patientID = $request->ccda_patient_id;
            if ($patientID) {
                $data['patient'] = Patient::find($patientID)->toArray();
            }
            $data['ccda'] = $this->getCCDAData(json_decode($jsonstring, true));
            return json_encode($data);
        }
    }
}
