<?php

namespace myocuhub\Http\Controllers\Traits;

use Illuminate\Http\Request;
use myocuhub\Patient;

trait ValidateFPCRequestParams
{

    public function validateFPCData($patientId)
    {
        $patient = Patient::find($patientId);
        $tempFields = config('constants.fpc_mandatory_fields');
        $fields = $tempFields;

        foreach ($tempFields as $key => $field) {
            if ($field['type'] == 'field_date') {
                if ($this->validateDate($patient[$field['field_name']])) {
                    array_forget($fields, $key);
                }
            } elseif ($patient[$field['field_name']]) {
                array_forget($fields, $key);
            }
        }
        return $fields;
    }


    public function updateFPCRequiredData(Request $request)
    {
        $data = $request->all();
        $patientID =  $request->patientId;
        unset($data['patientId']);
        $updatePatient = Patient::where('id', $patientID)->update($data);
        return $updatePatient;
    }

    public function getFPCValidateView(Request $request)
    {
        $patientID = $request->patient_id;
        $validatedFPCData = $this->validateFPCData($patientID);
        $data['validate_fpc_count'] = sizeof($validatedFPCData);
        $data['validate_fpc_view'] = view('patient.field_model_fpc')->with('fields_FPC', $validatedFPCData)->render();
        return json_encode($data);
    }

    public function validateDate($date)
    {
        $timeStamp = strtotime($date);
        if (!$timeStamp) {
            return false;
        }
        $date = date('Y-m-d', $timeStamp);
        $test_arr  = explode('-', $date);
        $checked  =   checkdate($test_arr[1], $test_arr[2], $test_arr[0]);
        $last_date =  strtotime("1902-01-31");
        return  (strtotime($date) > $last_date) && $checked;
    }
}
