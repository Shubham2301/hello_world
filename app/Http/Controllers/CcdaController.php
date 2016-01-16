<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Models\Ccda;
use myocuhub\Models\Vital;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

            $destinationPath = 'ccda';
            $extension = $file->getClientOriginalExtension();
            $filename = str_random(4).".{$extension}";
            $upload_success = $file->move(public_path().'/'.$destinationPath, $filename);
            $xmlfile = public_path().'/'.$destinationPath.'/'.$filename;
            $jsonfile = public_path().'/patientjson.json';
            $a = exec("node ".app_path()."/tojson.js ". $xmlfile." >".$jsonfile);
            $jsonstring = file_get_contents($jsonfile, true);
            $ccda = new Ccda;
            $ccda->ccdablob = $jsonstring;
            if($ccda->save()){
                return $ccda->ccdablob;
                return 'successfull'; }
        }

        return 'unsuccessful';
    }

    public function genrateXml()
    {
        $xmlfile = public_path().'/updatedjson.xml';
        $jsonfile = public_path().'/patientjson.json';
        $a = exec("node ".app_path()."/toxml.js ". $jsonfile." >".$xmlfile);

    }


    public function updateVitals($id)
    {
        $ccda = Ccda::find(16);
        $jsonobject = json_decode($ccda->ccdablob,true);
        $vitals = Vital::select('v_date')->groupBy('v_date')->where('ccda_id',$id)->get();
        $vitalsize = sizeof($jsonobject['vitals']);
        foreach($vitals as $vital)
        {
            $vsigns = Vital::where('ccda_id',$id)->where('v_date',$vital->v_date)->get();
            $newVitals=[];
            $i=0;
            $newVitals['date'] = date('m-d-Y', strtotime($vital->v_date));
            foreach($vsigns as $signs){
                $newVitals['results'][$i]['name']            = $signs->name;
                $newVitals['results'][$i]['code']            = $signs->code;
                $newVitals['results'][$i]['code_system']     = $signs->code_system;
                $newVitals['results'][$i]['code_system_name']= $signs->code_system_name;
                $newVitals['results'][$i]['unit']            = $signs->unit;
                $i++;
            }
            $jsonobject['vitals'][$vitalsize]=$newVitals;
            $vitalsize++;

        }
        $ccda->ccdablob = json_encode($jsonobject);
        $ccda->save();
        $jsonfile = public_path().'/patientjson.json';
        $myfile = fopen($jsonfile, "w");
        $ss     = fwrite($myfile,$ccda->ccdablob);

        $this->genrateXml();
    }

    public function addVital()
    {
       return view('ccda.addvitals');
    }
    public function saveVitals(Request $request)
    {
        $date = $request->input('v_date');
        //$dt = \DateTime::format('m-d-Y', $date);
         // return $dt;
        //$datenew = date('m-d-Y', strtotime($date));
       // return $datenew;

        $vital = new Vital;
        $vital->ccda_id             = 16;
        $vital->v_date              = $date;
        $vital->name                = $request->input('v_name');
        $vital->code                = '8302-2';
        $vital->code_system         = '2.16.840.1.113883.6.1';
        $vital->code_system_name    = 'LOINC';
        $vital->value               = $request->input('v_value');
        $vital->unit                = $request->input('v_unit');

        if($vital->save())
            return 'successfull';
        return 'fail';


    }

    public function modifiedxml(){
        foreach($data as $vitalsign){
            $date =$vitalsign['date'];
            foreach($vitalsign['results'] as $results){
                $vital = new Vital;
                $vital->ccda_id             = $id;
                $vital->v_date              = $date;
                $vital->name                = $results['name'];
                $vital->code                = $results['code'];
                $vital->code_system         = $results['code_system'];
                $vital->code_system_name    = $results['code_system_name'];
                $vital->value               = $results['value'];
                $vital->unit                = $results['unit'];
                $vital->save();
            }

        }
    }

    public function getxml()
    {
        $this->updateVitals(14);
        $file= public_path().'/updatedjson.xml';
        $headers = array(
              'Content-Type: application/xml',
            );
        return Response()->download($file, 'amrsing.xml', $headers);

    }


}
