<?php

namespace myocuhub\Services;

use DateTime;
use myocuhub\Models\Kpi;
use myocuhub\User;
use myocuhub\Models\Ccda;
use myocuhub\Patient;

class CCDAService
{

    private $ccdaPaths ;
    public function __construct()
    {
        $this->ccdaPaths = config('constants.paths.ccda');
    }

    public function generateXml($patientID, $updated = false)
    {
        if (!$patientID) {
            return false;
        }
        try {
            $ccda = Ccda::where('patient_id', $patientID)->orderBy('created_at', 'desc')->first();
            if (!$ccda) {
                $ccda = $this->generateCCDAFromSystem($patientID);
            }

            $dataInjson = $ccda->ccdablob;
            if ($updated) {
                $dataInjson = json_encode($this->updateDemographics($ccda->ccdablob, $patientID));
            }


            $jsonfilename = str_random(9) . ".json";
            $xmlfilename = str_random(9) . ".xml";

            $jsonfile = $this->ccdaPaths['temp_json'] . $jsonfilename;
            $xmlfile =  $this->ccdaPaths['temp_ccda'] . $xmlfilename;

            $myfile = fopen($jsonfile, "w");
            $ss = fwrite($myfile, $dataInjson);

            $command = env('NODE_PATH', '/usr/local/bin/node').' ' . $this->ccdaPaths['toxml'] . $jsonfile . " " . $xmlfile;
            exec($command);
            fclose($myfile);
            unlink($jsonfile);
            return $xmlfile;
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in generating C-CDA file for patient id '. $patientID;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return false;
        }
    }

    public function updateDemographics($ccdaInJson, $patientID)
    {
        $data = json_decode($ccdaInJson, true);
        $patient_data = Patient::find($patientID)->toArray();
        if ($patient_data) {
            $data['demographics']['name']['prefix'] = $patient_data['title'];
            $data['demographics']['name']['given'][0] = $patient_data['firstname'];
            $data['demographics']['name']['given'][1] = $patient_data['middlename'];
            $data['demographics']['name']['family'] = $patient_data['lastname'];
            $data['demographics']['phone']['work'] = $patient_data['workphone'];
            $data['demographics']['phone']['home'] = $patient_data['homephone'];
            $data['demographics']['phone']['mobile'] = $patient_data['cellphone'];
            $data['demographics']['email'] = $patient_data['email'];
            $data['demographics']['address']['street'][0] = $patient_data['addressline1'];
            $data['demographics']['address']['street'][1] = $patient_data['addressline2'];
            $data['demographics']['address']['city'] = $patient_data['city'];
            $data['demographics']['address']['zip'] = $patient_data['zip'];
            $data['demographics']['address']['country'] = $patient_data['country'];
            $data['demographics']['dob'] = $patient_data['birthdate'];
            $data['demographics']['gender'] = $patient_data['gender'];
            $data['demographics']['language'] = $patient_data['preferredlanguage'];
            $data['document']['date'] = date('Y-m-d H:i:m');

            if ($patient_data['gender'] == 'M') {
                $data['demographics']['gender'] = 'male';
            } else if ($patient_data['gender'] == 'F') {
                $data['demographics']['gender'] = 'female';
            }

            return $data;
        }
        return false;
    }

    public function generateCCDAFromSystem($patientID)
    {
        $defaultCCDA = file_get_contents(config('constants.paths.ccda.default_ccda'));
        $CCDAData = $this->updateDemographics($defaultCCDA, $patientID);
        $dataInjson = json_encode($CCDAData);
        $ccda = new Ccda;
        $ccda->ccdablob = $dataInjson;
        $ccda->patient_id = $patientID;
        $ccda->save();
        return $ccda;
    }

    public function generateJson($fileInXml)
    {
        try {
            $file = $fileInXml;
            $extension = $file->getClientOriginalExtension();
            $xmlfilename = str_random(9) . ".{$extension}";
            $jsonfilename = str_random(9) . ".json";
            $upload_success = $file->move($this->ccdaPaths['temp_ccda'], $xmlfilename);
            $xmlfile = $this->ccdaPaths['temp_ccda'] . '/' . $xmlfilename;
            $jsonfile = $this->ccdaPaths['temp_json'] . $jsonfilename;
            $command = env('NODE_PATH', '/usr/local/bin/node') ." " . $this->ccdaPaths['tojson'] . $xmlfile . " " . $jsonfile;
            $xml_to_json = exec($command);
            $jsonstring = file_get_contents($jsonfile, true);
            $validator = \Validator::make(array('jsson' => $jsonstring), array('jsson' => 'Required|json'));
            unlink($xmlfile);
            unlink($jsonfile);
            if ($validator->fails()) {
                return false;
            }
            return $jsonstring;
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in generating C-CDA file for patient id '. $patientID;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return false;
        }
    }

    public function IsCCDA($file)
    {
        $extension = $file->getClientOriginalExtension();
        if ($extension != 'xml') {
            return false;
        }
        return $this->generateJson($file);
    }

    public function store($json, $patientID)
    {
        return Ccda::create([
            'ccdablob'=> $json,
            'patient_id' =>$patientID
        ]);
    }
}
