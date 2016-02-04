<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Models\Ccda;
use myocuhub\Models\Vital;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use myocuhub\Patient;
use \DOMDocument;
use \XSLTProcessor;

class CcdaController extends Controller
{

    public function index()
    {
        return view('ccda.ccdatest');
    }

    public function saveCcda(Request $request)
    {
        if ($request->hasFile('patient_ccda')) {
            $file = $request->file('patient_ccda');
            $destinationPath = 'temp_ccda';
            $extension = $file->getClientOriginalExtension();
            $xmlfilename = str_random(9).".{$extension}";
            $jsonfilename = str_random(9).".json";
            $upload_success = $file->move(base_path().'/'.$destinationPath, $xmlfilename);
            $xmlfile = base_path().'/'.$destinationPath.'/'.$xmlfilename;
            $jsonfile = base_path().'/'.$destinationPath.'/temp_json/'.$jsonfilename;
            $xml_to_json = exec("node ".public_path()."/js/tojson.js ". $xmlfile." ".$jsonfile);
            $jsonstring = file_get_contents($jsonfile, true);
            $validator = \Validator::make(array('jsson' => $jsonstring), array('jsson'=>'Required|json'));
             unlink($xmlfile);
             unlink($jsonfile);
            if ($validator->fails()) {
                //$request->session()->flash('error','please provide a valid ccda file');
                //return back()->withInput();
                return 'unsuccessful';
            }
            $ccda = new Ccda;
            $ccda->ccdablob = $jsonstring;
            $ccda->patient_id = $request->patient_id;
            if ($ccda->save()) {
                $data = [];
                $data['patient'] = $this->getPatientData($ccda->patient_id);
                $data['ccda']    = $this->getCCDAData(json_decode($ccda->ccdablob, true));
                return json_encode($data);
                //return  $this->updatePatientDemographics(json_decode($ccda->ccdablob, true), $ccda->patient_id);
                //return redirect()->route('showvitals',$ccda->id );
            }
        }
        return 'unsuccessful';
    }

    public function savePreviousVitals($data, $id)
    {
        foreach ($data as $vitals) {
            $date = date('Y-m-d', strtotime($vitals['date']));
            $results = $vitals['results'];
            foreach ($results as $result) {
                $vital = new Vital;
                $vital->ccda_id             = $id;
                $vital->v_date              = $date;
                $vital->name                = $result['name'];
                $vital->code                = $result['code'];
                $vital->code_system         = $result['code_system'];
                $vital->code_system_name    = $result['code_system_name'];
                $vital->value               = $result['value'];
                $vital->unit                = $result['unit'];
                $vital->save();
            }
        }

    }

    public function updateVitals($id)
    {
        $ccda = Ccda::find($id);
        $jsonobject = json_decode($ccda->ccdablob, true);
        $vitals = Vital::select('v_date')->groupBy('v_date')->where('ccda_id', $id)->get();
        $vitalsize = 0;
        foreach ($vitals as $vital) {
            $vsigns = Vital::where('ccda_id', $id)->where('v_date', $vital->v_date)->get();
            $newVitals=[];
            $i=0;
            $newVitals['date'] = date('m-d-Y', strtotime($vital->v_date));
            foreach ($vsigns as $signs) {
                $newVitals['results'][$i]['name']            = $signs->name;
                $newVitals['results'][$i]['code']            = $signs->code;
                $newVitals['results'][$i]['value']           = $signs->value;
                $newVitals['results'][$i]['code_system']     = $signs->code_system;
                $newVitals['results'][$i]['code_system_name']= $signs->code_system_name;
                $newVitals['results'][$i]['unit']            = $signs->unit;
                $i++;
            }
            $jsonobject['vitals'][$vitalsize]=$newVitals;
            $vitalsize++;
        }
        //$ccda->ccdablob = json_encode($jsonobject);
        $ccda->save();
        $jsonfile = public_path().'/patientjson.json';
        $myfile = fopen($jsonfile, "w");
        $ss     = fwrite($myfile, $ccda->ccdablob);

        $this->genrateXml();
    }

    public function addVital($id)
    {
        return view('ccda.addvitals')->with('id', $id);
    }

    public function saveVitals(Request $request)
    {
        $date = $request->input('v_date');
        $id = $request->input('ccda_id');

        $vital = new Vital;
        $vital->ccda_id             = $id;
        $vital->v_date              = $date;
        $vital->name                = $request->input('v_name');
        $vital->code                = '8302-2';
        $vital->code_system         = '2.16.840.1.113883.6.1';
        $vital->code_system_name    = 'LOINC';
        $vital->value               = $request->input('v_value');
        $vital->unit                = $request->input('v_unit');

        if ($vital->save()) {
            return redirect()->route('showvitals', $id);
        }
        return 'fail';
    }

    public function getxml($patient_id)
    {
       // $this->updateVitals($id);
        $ccdafile =  $this->updateDemographics($patient_id);
        if($ccdafile == false)
            return 'nofile';
        $headers = array(
              'Content-Type: application/xml',
            );
        return Response()->download($ccdafile, 'ccdafile.xml', $headers)->deleteFileAfterSend(true);
    }

    public function showVitals($id)
    {
        $vitals = Vital::where('ccda_id', $id)->get();
        return view('ccda.showvitals')->with('vitals', $vitals)->with('id', $id);
    }


    public function updateDemographics($patient_id)
    {
        $ccda = Ccda::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
        if(!($ccda))
           return false;

        $data = json_decode($ccda->ccdablob, true);
        $patient_data = Patient::find($patient_id)->toArray();
        if ($patient_data) {
            $data['demographics']['name']['prefix']         = $patient_data['title'];
            $data['demographics']['name']['given'][0]       = $patient_data['firstname'];
            $data['demographics']['name']['family']         = $patient_data['lastname'];
            $data['demographics']['phone']['work']          = $patient_data['workphone'] ;
            $data['demographics']['phone']['home']          = $patient_data['homephone'];
            $data['demographics']['phone']['mobile']        = $patient_data['cellphone'];
            $data['demographics']['email']                  = $patient_data['email'] ;
            $data['demographics']['address']['street'][0]   = $patient_data['addressline1'];
            $data['demographics']['address']['street'][1]   = $patient_data['addressline2'];
            $data['demographics']['address']['city']        = $patient_data['city'];
            $data['demographics']['address']['zip']         = $patient_data['zip'];
            $data['demographics']['address']['country']     = $patient_data['country'];
            $data['demographics']['dob']                    = $patient_data['birthdate'];
            $data['demographics']['gender']                 = $patient_data['gender'];
            $data['demographics']['language']               = $patient_data['preferredlanguage'];

            return $this->genrateXml($data);

        }
        return 'try again';

    }

    public function genrateXml($data)
    {
        $jsonobject =json_encode($data);
        $jsonfilename = str_random(9).".json";
        $jsonfile = base_path().'/temp_ccda/temp_json/'.$jsonfilename;
        $myfile = fopen($jsonfile, "w");
        $ss = fwrite($myfile, $jsonobject);
        $xmlfilename = str_random(9).".xml";
        $xmlfile = base_path().'/temp_ccda/'.$xmlfilename;
        $a = exec("node ".public_path()."/js/toxml.js ". $jsonfile." ".$xmlfile);
        fclose($myfile);
        unlink($jsonfile);
        return $xmlfile;
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
        $data['title']              = $ccdaData['demographics']['name']['prefix'];
        $data['firstname']          = $ccdaData['demographics']['name']['given'][0];
        $data['lastname']           = $ccdaData['demographics']['name']['family'];
        $data['workphone']          = $ccdaData['demographics']['phone']['work'];
        $data['homephone']          = $ccdaData['demographics']['phone']['home'];
        $data['cellphone']          = $ccdaData['demographics']['phone']['mobile'];
        $data['email']              = $ccdaData['demographics']['email'];
        $data['addressline1']       = $ccdaData['demographics']['address']['street'][0];
        $data['addressline2']       = $this->validateKey($ccdaData['demographics']['address']['street'], 1);
        $data['city']               = $ccdaData['demographics']['address']['city'];
        $data['zip']                = $ccdaData['demographics']['address']['zip'];
        $data['country']            = $ccdaData['demographics']['address']['country'];
        $data['birthdate']          = date('Y-m-d', strtotime($ccdaData['demographics']['dob']));
        $data['gender']             = $ccdaData['demographics']['gender'];
        $data['preferredlanguage']  = $ccdaData['demographics']['language'];
        return $data;
    }

    public function getPatientData($patient_id)
    {
        $data = [];
        $patient_data = Patient::find($patient_id)->toArray();
        if ($patient_data) {
            $data['id'] = $patient_id;
            $data['title']        = $patient_data['title'];
            $data['firstname']    = $patient_data['firstname'];
            $data['lastname']     = $patient_data['lastname'];
            $data['workphone']    = $patient_data['workphone'] ;
            $data['homephone']    = $patient_data['homephone'];
            $data['cellphone']    = $patient_data['cellphone'];
            $data['email']        = $patient_data['email'] ;
            $data['addressline1'] = $patient_data['addressline1'];
            $data['addressline2'] = $patient_data['addressline2'];
            $data['city']           = $patient_data['city'];
            $data['zip']            = $patient_data['zip'];
            $data['country']        = $patient_data['country'];
            $data['birthdate']      = date('Y-m-d', strtotime($patient_data['birthdate']));
            $data['gender']         = $patient_data['gender'];
            $data['preferredlanguage']= $patient_data['preferredlanguage'];
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
            return $patient_id;
        }
        return 'false';
    }

    public function showCCDA($patient_id){
        $ccda = Ccda::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
        if(!($ccda))
            return 'nofile';
        $data = json_decode($ccda->ccdablob, true);
        $xmlfile= $this->genrateXml($data);

        $xml = new DOMDocument;
        $xml->load($xmlfile);


        $xsl = new DOMDocument;
        $xsl->load(public_path().'/lib/xslt/CDA.xsl');

        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);
        unlink($xmlfile);

        return $proc->transformToXML($xml);

    }



}
?>
