<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Models\Ccda;
use myocuhub\Models\Vital;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use myocuhub\Patient;

class CcdaController extends Controller
{

    public function index()
    {
        return view('ccda.ccdatest');
    }

    public function saveCcda(Request $request)
    {
        if ($request->hasFile('ccda')) {
            $file = $request->file('ccda');
            $destinationPath = 'temp_ccda';
            $extension = $file->getClientOriginalExtension();
            $xmlfilename = str_random(9).".{$extension}";
            $jsonfilename = str_random(9).".json";
            $upload_success = $file->move(base_path().'/'.$destinationPath, $xmlfilename);
            $xmlfile = base_path().'/'.$destinationPath.'/'.$xmlfilename;
            $jsonfile = base_path().'/'.$destinationPath.'/temp_json/'.$jsonfilename;
            $a = exec("node ".public_path()."/js/tojson.js ". $xmlfile." ".$jsonfile);
            $jsonstring = file_get_contents($jsonfile, true);
            $validator = \Validator::make(array('jsson' => $jsonstring), array('jsson'=>'Required|json'));
             unlink($xmlfile);
             unlink($jsonfile);
            if ($validator->fails()) {
                $request->session()->flash('error','please provide a valid ccda file');
                return back()->withInput();
            }
            $ccda = new Ccda;
            $ccda->ccdablob = $jsonstring;
            $ccda->patient_id = $request->patient_id;
            if ($ccda->save()) {
                return  $this->showDemographics(json_decode($ccda->ccdablob, true),$ccda->patient_id);
               // $this->savePreviousVitals(json_decode($ccda->ccdablob, true)['vitals'], $ccda->id );
                return redirect()->route('showvitals',$ccda->id );
            }
        }

        return 'unsuccessful';
    }

    public function savePreviousVitals($data,$id)
    {
        foreach ($data as $vitals)
        {
            $date = date('Y-m-d',strtotime($vitals['date']));
            $results = $vitals['results'];
            foreach ($results as $result)
            {
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
            return redirect()->route('showvitals',$id );
        }
        return 'fail';
    }

    public function getxml($patient_id)
    {
       // $this->updateVitals($id);
        $ccdafile =  $this->updateDemographics($patient_id);
        $headers = array(
              'Content-Type: application/xml',
            );
        return Response()->download($ccdafile, 'ccdafile.xml', $headers)->deleteFileAfterSend(true);
    }

    public function showVitals($id)
    {
        $vitals = Vital::where('ccda_id',$id)->get();

        return view('ccda.showvitals')->with('vitals',$vitals)->with('id',$id);


    }

    public function showDemographics($data,$patient_id)
    {
        $patient_data = [];
        $patient_data['title']              = $data['demographics']['name']['prefix'];
        $patient_data['firstname']          = $data['demographics']['name']['given'][0];
        $patient_data['lastname']           = $data['demographics']['name']['family'];
        $patient_data['workphone']          = $data['demographics']['phone']['work'];
        $patient_data['homephone']          = $data['demographics']['phone']['home'];
        $patient_data['cellphone']          = $data['demographics']['phone']['mobile'];
        $patient_data['email']              = $data['demographics']['email'];
        $patient_data['addressline1']       = $data['demographics']['address']['street'][0];
        $patient_data['addressline2']       = $this->validateKey($data['demographics']['address']['street'],1);
        $patient_data['city']               = $data['demographics']['address']['city'];
        $patient_data['zip']                = $data['demographics']['address']['zip'];
        $patient_data['country']            = $data['demographics']['address']['country'];
        $patient_data['birthdate']          = date('Y-m-d',strtotime($data['demographics']['dob']));
        $patient_data['gender']             = $data['demographics']['gender'];
        $patient_data['preferredlanguage']  =$data['demographics']['language'];
        // $patient_data['status']         = $data['demographics']['name']['family'];
        // $patient_data['statusdate']     = $data['demographics']['name']['family'];
        // $patient_data['insurancecarrier']= $data['demographics']['name']['family'];
        // $patient_data['lastfourssn']    = $data['demographics']['name']['family'];
        $patient = Patient::find($patient_id);
        if($patient){
            $patient->update($patient_data);
            dd($patient);
        }
        return 'unsuccessfull';
    }

    public function updateDemographics($patient_id)
    {
        $ccda = Ccda::where('patient_id',$patient_id)->orderBy('created_at','desc')->first();
        $data = json_decode($ccda->ccdablob, true);
        $patient_data = Patient::find($patient_id)->toArray();
        if($patient_data){
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

    public function validateKey($data,$key){
        if (array_key_exists ( $key,$data))
        {
            return $data[$key];
        }
            return null;
    }

}
