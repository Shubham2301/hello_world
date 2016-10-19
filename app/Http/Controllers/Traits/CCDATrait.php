<?php

namespace myocuhub\Http\Controllers\Traits;

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

trait CCDATrait
{


    public function getCCDAData($ccdaData)
    {
        $data = [];
        $data['title'] =     $ccdaData['demographics']['name']['prefix'];
        $data['firstname'] = $this->validateKey($ccdaData['demographics']['name']['given'], 0);
        $data['addressline1'] = $this->validateKey($ccdaData['demographics']['address']['street'], 0);
        $data['addressline2'] = $this->validateKey($ccdaData['demographics']['address']['street'], 1);
        $data['lastname'] =  $ccdaData['demographics']['name']['family'];
        $data['workphone'] = str_replace('tel:', '', $ccdaData['demographics']['phone']['work']);
        $data['homephone'] = str_replace('tel:', '', $ccdaData['demographics']['phone']['home']);
        $data['cellphone'] = str_replace('tel:', '', $ccdaData['demographics']['phone']['mobile']);
        $data['email']     = $ccdaData['demographics']['email'];
        $data['city']      = $ccdaData['demographics']['address']['city'];
        $data['zip']       = $ccdaData['demographics']['address']['zip'];
        $data['country']   = $ccdaData['demographics']['address']['country'];
        $data['birthdate'] = date('Y-m-d', strtotime($ccdaData['demographics']['dob']));
        $data['state'] =       $ccdaData['demographics']['address']['state'];
        $data['gender']   = $ccdaData['demographics']['gender'];
        $data['preferredlanguage'] = $ccdaData['demographics']['language'];

        if ($data['gender']) {
            $data['gender'] = 'F';
            if (stripos($data['gender'], 'male') > -1) {
                $data['gender'] = 'M';
            }
        }

        if ($data['preferredlanguage']) {
            $data['preferredlanguage'] = config('patient_engagement.language.english');

            if (stripos($data['preferredlanguage'], 'Spanish')> -1) {
                $data['preferredlanguage'] = config('patient_engagement.language.spanish');
            }
        }

        return $data;
    }

    public function saveCCDA($request, $jsonstring, $patientID)
    {
        $data = [];

        if (!$jsonstring) {
            $data['error'] = true;
            return json_encode($data);
        }

        $ccda = MyCCDA::store($jsonstring, $patientID);

        if ($ccda) {
            $data['error']   = false;
            $data['patient'] = Patient::find($patientID)->toArray();
            $data['ccda']    = $this->getCCDAData(json_decode($jsonstring, true));

            $action = 'saved new CCDA';
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return $data;
        }

        $data['error'] = true;
        return json_encode($data);
    }

    public function validateKey($data, $key)
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        return null;
    }
}
