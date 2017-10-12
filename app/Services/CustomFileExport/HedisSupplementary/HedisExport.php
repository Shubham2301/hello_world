<?php

namespace myocuhub\Services\CustomFileExport\HedisSupplementary;

use Carbon\Carbon;
use DateTime;
use Event;
use Illuminate\Support\Facades\Mail;
use MyCCDA;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Facades\Helper;
use myocuhub\Models\Careconsole;
use myocuhub\Models\Ccda;
use myocuhub\Models\PatientRecord;
use myocuhub\Models\ReportField;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\Services\ActionService;

class HedisExport
{
    private $ActionService;
    private $export_status = array();
    private $patient_id;
    private $dateFormat = 'm/d/Y';
    private $system_data;
    private $ccda_data;
    private $web_form_data;
    
    public function __construct(ActionService $ActionService)
    {
        $this->ActionService = $ActionService;
    }

    public function index($network_id)
    {
        $patient_list = self::getNetworkPatientList($network_id);

        if (empty($patient_list)) {
            $this->export_status['Export Status'] = 'No patient to export data. Aborted!';
            return json_encode($this->export_status);
        }

        if (!self::checkCredential($network_id)) {
            $this->export_status['Mode of transfer'] = 'Not transferred. Data exported as file.';
        } else {
            $file_data = self::generateFileData($patient_list);

            self::storeFileData($file_data, $network_id);

            $file = self::generateHedisFile($file_data);
            $this->export_status['Mode of transfer'] = 'SFTP';
            $this->export_status['SSH Status'] = self::sendSSH($file, $network_id);
            unlink(base_path().'/temp_ccda/' . $file . '.csv');

            self::sendExportNotification();

            $action = 'Hedis File generated for ' . $this->export_status['Network Name'];
            $description = '';
            $filename = basename(__FILE__);
            Event::fire(new MakeAuditEntry($action, $description, $filename));
        }

        return json_encode($this->export_status);
    }

    public function generateFileData(array $patient_id_list)
    {
        $file_data = array();
        foreach ($patient_id_list as $patientID) {
            $patient_data = self::getPatientFileData($patientID);
            if (!empty($patient_data['Procedure'])) {
                $patient_file_procedure_split = self::splitProcedureData($patient_data);
                $file_data = array_merge($patient_file_procedure_split, $file_data);
            } else {
                $file_data[] = $patient_data;
            }
        }
        return $file_data;
    }

    protected function splitProcedureData($patient_data)
    {
        $patient_file_data = array();
        foreach ($patient_data['Procedure'] as $procedure) {
            $file_data = $patient_data;
            $file_data['Procedure'] = strtoupper($procedure);
            $patient_file_data[] = $file_data;
        }

        return $patient_file_data;
    }

    public function getNetworkPatientList($network_id)
    {
        $patient_list = array();

        $careconsole_patients = CareConsole::whereHas('patient', function ($sub_query) {
                $sub_query->excludeTestPatient();
            })
            ->where('stage_id', '5')
            ->whereHas('importHistory', function ($subquery) use ($network_id) {
                $subquery->where('network_id', $network_id);
            })
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->with(['latestContactHistory', 'latestContactHistory.action'])
            ->get();

        foreach ($careconsole_patients as $patient) {
            // if ($patient->latestContactHistory->action->name != 'hedis-data-exported') {
                $patient_list[] = $patient->patient_id;
            // }
        }

        $this->export_status['Patient Count'] = count($patient_list);
        return $patient_list;
    }

    protected function getPatientFileData($patientID)
    {
        $this->patient_id = $patientID;

        $system_data = self::getSystemData();
        $ccda_data = self::getCcdaData();
        $web_form_data = self::getWebFormData();
        $combined_data = self::processData([], 'combined-data');
        $default_field_data = self::getDefaultFieldData();
        
        $patient_file_data = array_merge($system_data, $default_field_data, $ccda_data, $web_form_data, $combined_data);

        $careconsole = Careconsole::where('patient_id', $this->patient_id)->first();

        $this->ActionService->userAction(44, null, null, '', '', $careconsole->id, '');

        return self::sortValues($patient_file_data);
    }

    protected function getSystemData()
    {
        $careconsole = Careconsole::where('patient_id', $this->patient_id)
                            ->with(['appointment', 'appointment.provider', 'appointment.provider.providerType'])
                            ->first();

        $this->system_data = $careconsole;
        return self::processData($careconsole, 'careconsole');
    }

    protected function getCcdaData()
    {
        $ccda = Ccda::where('patient_id', $this->patient_id)
                ->where('created_at', '>', $this->system_data->appointment->start_datetime)
                ->orderBy('created_at', 'desc')->first();

        if (!$ccda) {
            $ccda = MyCCDA::generateCCDAFromSystem($this->patient_id);
        }

        $data = MyCCDA::updateDemographics($ccda->ccdablob, $this->patient_id);

        $this->ccda_data = $data;

        return self::processData($data, 'ccda');
    }

    protected function getWebFormData()
    {
        $record = PatientRecord::where('patient_id', $this->patient_id)
                ->where('web_form_template_id', '5')
                ->where('created_at', '>', $this->system_data->appointment->start_datetime)
                ->orderBy('id', 'desc')->first();

        if ($record) {
            $record_data = json_decode($record->content, true);
        } else {
            $record_data = [];
        }
        $this->web_form_data = $record_data;
        return self::processData($record_data, 'web-form');

        return [];
    }

    protected function getDefaultFieldData()
    {
        $data_array = array();

        $data_array['place_of_service'] = '11';

        $data_array['secondary_insurance'] = '';
        $data_array['medicare_hic'] = '';
        $data_array['medicaid_id'] = '';
        $data_array['rendering_provider_key'] = '';
        $data_array['rendering_provider_speciality_2'] = '';
        $data_array['rendering_provider_tax_id'] = '';
        $data_array['rx_fill_date'] = '';
        $data_array['rx_days_supply'] = '';

        $data_array['test_name'] = '';
        $data_array['test_result_value'] = '';

        $data_array['dialated_eye_exam_performed_by_professional'] = 'Y';

        return $data_array;
    }

    protected function processData($data, $type)
    {
        $data_array = array();
        switch ($type) {
            case 'careconsole':
                $data_array['product_id'] = self::getFieldValue($data, 'product_id');
                $data_array['rendering_provider_name'] = (isset($data->appointment) && isset($data->appointment->provider)) ? $data->appointment->provider->getName('print_format') : '';
                $data_array['rendering_provider_npi'] = (isset($data->appointment) && isset($data->appointment->provider)) ? $data->appointment->provider->npi : '';
                $data_array['rendering_provider_speciality_1'] = (isset($data->appointment) && isset($data->appointment->provider)) ? self::getFieldValue($data->appointment->provider, 'provider_speciality_taxonomy') : '';
                $data_array['date_of_service'] = isset($data->appointment) ? self::getFieldValue($data->appointment, 'date_of_service') : '';

                $data_array['member_key'] = self::getFieldValue($data, 'member_key');
                $data_array['member_hcid'] = self::getFieldValue($data, 'member_hcid');
                break;
            case 'ccda':
                $data_array['member_last_name'] = $data['demographics']['name']['family'];
                $data_array['member_first_name'] = $data['demographics']['name']['given'][0];
                $data_array['address_1'] = $data['demographics']['address']['street'][0];
                $data_array['address_2'] = $data['demographics']['address']['street'][1];
                $data_array['city'] = $data['demographics']['address']['city'];
                $data_array['state'] = $data['demographics']['address']['state'];
                $data_array['zipcode'] = $data['demographics']['address']['zip'];
                $data_array['member_email'] = $data['demographics']['email'];
                $data_array['member_telephone_1'] = self::getFieldValue($data['demographics']['phone']['home'], 'phone');
                $data_array['member_telephone_2'] = self::getFieldValue($data['demographics']['phone']['mobile'], 'phone');
                $data_array['gender'] = self::getFieldValue($data['demographics']['gender'], 'gender');
                $data_array['race'] = $data['demographics']['race'];
                $data_array['dob'] = self::getFieldValue($data['demographics']['dob'], 'dob');

                $data_array['bmi'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'bmi') : '';
                $data_array['bmi_percentile'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'percentile') : '';
                $data_array['height'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'height') : '';
                $data_array['weight'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'weight') : '';

                $data_array['loinc'] = isset($data['results']) ? self::getFieldValue($data['results'], 'loinc') : '';
                $data_array['ndc'] = isset($data['medications']) ? self::getFieldValue($data['medications'], 'ndc') : '';
                break;
            case 'web-form':
                break;
            case 'combined-data':
                $data_array = array_merge($data_array, self::getFieldValue([], 'diagnosis'));
                $data_array = array_merge($data_array, self::getFieldValue([], 'blood-pressure'));
                $data_array['procedures'] = self::getFieldValue([], 'procedures');
                break;
            default:
                break;
        }

        return $data_array;
    }

    protected function getFieldValue($data, $field_type)
    {
        switch ($field_type) {
            case 'product_id':
                $product_id = '';
                $patient_referral_history = $data->load('referralHistory')->referralHistory;
                if ($patient_referral_history) {
                    $product_id = $patient_referral_history->referred_by_practice;
                }

                if ($product_id == 'Medicaid' || $product_id == 'Medicare' || $product_id == 'Commercial') {
                    return $product_id;
                }
                return '';
                break;
            case 'provider_speciality_taxonomy':
            
                if (!isset($data->providerType)) {
                    return '';
                }
                $provider_type = $data->providerType->name;
                $speciality = strtolower($data->speciality);

                if ($provider_type == 'Optometrist') {
                    return '152W00000X';
                } else {
                    if (strpos($speciality, 'retina') !== false) {
                        return '207WX0107X';
                    } elseif (strpos($speciality, 'glaucoma') !== false) {
                        return '207WX0009X';
                    } else {
                        return '207W00000X';
                    }
                }
                break;
            case 'date_of_service':
                $date = new \DateTime($data->start_datetime);
                return $date->format($this->dateFormat);
                break;
            case 'gender':
                if (strtolower($data) == 'male' || strtolower($data) == 'm') {
                    return 'M';
                } else {
                    return 'F';
                }
                break;
            case 'dob':
                $date = new \DateTime($data);
                return $date->format($this->dateFormat);
                break;
            case 'phone':
                $phone = $data;
                $phone = preg_replace('(\D)', '', $phone);
                return $phone;
                break;
            case 'member_key':
                $member_insurance = $data->load('patient')->patient->load('patientInsurance')->patientInsurance;
                if (isset($member_insurance)) {
                    $subscriber_id = $member_insurance->subscriber_id;
                    if (strpos($subscriber_id, 'MCID') !== false) {
                        $mcid = substr($subscriber_id, strpos($subscriber_id, '[') + 1, strpos($subscriber_id, ']') - strpos($subscriber_id, '[') -1);
                        return $mcid;
                    } else {
                        return $subscriber_id;
                    }
                    return '';
                }
                break;
            case 'member_hcid':
                $member_insurance = $data->load('patient')->patient->load('patientInsurance')->patientInsurance;
                if (isset($member_insurance)) {
                    $subscriber_id = $member_insurance->subscriber_id;
                    if (strpos($subscriber_id, 'HCID') !== false) {
                        $hcid = substr($subscriber_id, strpos($subscriber_id, 'HCID [') + 6, -1);
                        return $hcid;
                    } else {
                        return '';
                    }
                    return '';
                }
                return '';
                break;
            case 'height':
            case 'weight':
            case 'bmi':
            case 'percentile':
            case 'systolic':
            case 'diastolic':
                foreach ($data as $row) {
                    $row_name = strtolower($row['name']);
                    if (strpos($row_name, $field_type) !== false) {
                        return $row['value'];
                    } else {
                        continue;
                    }
                }
                break;
            case 'blood-pressure':
                $blood_pressure = array();
                $web_form_data = $this->web_form_data;
                if (isset($web_form_data['blood_pressure_1']) && isset($web_form_data['blood_pressure_2']) && $web_form_data['blood_pressure_1'] != '' && $web_form_data['blood_pressure_2'] != '') {
                    $blood_pressure['systolic_blood_pressure'] = $web_form_data['blood_pressure_1'];
                    $blood_pressure['diastolic_blood_pressure'] = $web_form_data['blood_pressure_2'];
                } else {
                    $ccda_data = $this->ccda_data;
                    $blood_pressure['systolic_blood_pressure'] = (isset($ccda_data['vitals']) && count($ccda_data['vitals']) != 0) ? self::getFieldValue($ccda_data['vitals'][ count($ccda_data['vitals']) - 1 ]['results'], 'systolic') : '';
                    $blood_pressure['diastolic_blood_pressure'] = (isset($ccda_data['vitals']) && count($ccda_data['vitals']) != 0) ? self::getFieldValue($ccda_data['vitals'][ count($ccda_data['vitals']) - 1 ]['results'], 'diastolic') : '';
                }
                return $blood_pressure;
                break;
            case 'ccda_diagnosis':
                $diagnosis_array = array();
                for ($i=0; $i < 9; $i++) {
                    switch ($i) {
                        case '0':
                            if (isset($data['problems']['0'])) {
                                if (isset($data['problems']['0']['code'])) {
                                    $diagnosis_array[] = $data['problems']['0']['code'];
                                } elseif (isset($data['problems']['0']['translation']) && isset($data['problems']['0']['translation']['code'])) {
                                    $diagnosis_array[] = $data['problems']['0']['translation']['code'];
                                }
                            }
                            break;
                        default:
                            if (isset($data['problems'][$i])) {
                                if (isset($data['problems'][$i]['code'])) {
                                    $diagnosis_array[] = $data['problems'][$i]['code'];
                                } elseif (isset($data['problems'][$i]['translation']) && isset($data['problems'][$i]['translation']['code'])) {
                                    $diagnosis_array[] = $data['problems'][$i]['translation']['code'];
                                }
                            }
                    }
                }
                return $diagnosis_array;
                break;
            case 'diagnosis':
                $ccda_diagnosis = self::getFieldValue($this->ccda_data, 'ccda_diagnosis');
                $web_form_diagnosis = self::getFieldValue($this->web_form_data, 'web_form_diagnosis');
                $diagnosis_data = array_merge($web_form_diagnosis, $ccda_diagnosis);

                $diagnosis_array = array();
                for ($i=0; $i < 9; $i++) {
                    switch ($i) {
                        case '0':
                            $diagnosis_array['dxpri'] = '';
                            if (isset($diagnosis_data[$i])) {
                                $diagnosis_array['dxpri'] = strtoupper($diagnosis_data[$i]);
                            }
                            break;
                        default:
                            $diagnosis_array['dxsec'.$i] = '';
                            if (isset($diagnosis_data[$i])) {
                                $diagnosis_array['dxsec'.$i] = strtoupper($diagnosis_data[$i]);
                            }
                    }
                }
                return $diagnosis_array;
                break;
            case 'procedures':
                $ccda_procedures = self::getFieldValue($this->ccda_data, 'ccda_procedures');
                $web_form_procedures = self::getFieldValue($this->web_form_data, 'web_form_procedures');
                $procedures_data = array_merge($web_form_procedures, $ccda_procedures);

                return $procedures_data;
                break;
            case 'loinc':
                $loinc_values = array();
                foreach ($data as $data_row) {
                    if (isset($data_row['tests'])) {
                        foreach ($data_row['tests'] as $test) {
                            array_push($loinc_values, $test['code']);
                        }
                    }
                }
                return implode('; ', $loinc_values);
                break;
            case 'ndc':
                $ndc = array();
                foreach ($data as $data_row) {
                    if (isset($data_row['product'])) {
                        if (isset($data_row['product']['translation'])) {
                            if ($data_row['product']['translation']['code_system_name'] == 'NDC') {
                                $ndc_code = $data_row['product']['translation']['code'];
                                $ndc_code = substr_replace($ndc_code, '-', -2, 0);
                                $ndc_code = substr_replace($ndc_code, '-', -7, 0);
                                array_push($ndc, $ndc_code);
                            }
                        }
                    }
                }
                return implode('; ', $ndc);
                break;
            case 'web_form_diagnosis':
                $diagnosis_values = array();
                if (array_key_exists('diabetes_related_diagnosis', $data)) {
                    $diagnosis_values = $data['diabetes_related_diagnosis'];
                }

                $diagnosis_array = array();
                foreach ($diagnosis_values as $value) {
                    foreach (explode(',', $value) as $code_fragment) { //cleanup for values added in  single column
                        foreach (explode(' ', $code_fragment) as $code_value) {
                            if (trim($code_value) != '') {
                                $diagnosis_array[] = trim($code_value);
                            }
                        }
                    }
                }

                return $diagnosis_array;
            break;
            case 'ccda_procedures':
                $ccda_data = $this->ccda_data;
                $procedures_values = array();
                if (isset($ccda_data['procedures'])) {
                    foreach ($ccda_data['procedures'] as $data_row) {
                        if (trim($data_row['code']) == '') {
                            continue;
                        }
                        array_push($procedures_values, $data_row['code']);
                    }
                }

                return $procedures_values;
            break;
            case 'web_form_procedures':
                $web_form_data = $this->web_form_data;

                $procedures_values = array();
                if (isset($web_form_data)) {
                    if (array_key_exists('diabetes_related_diagnosis_cpt_codes', $web_form_data)) {
                        $procedures_values = array_merge($procedures_values, $web_form_data['diabetes_related_diagnosis_cpt_codes']);
                    }
                    if (array_key_exists('diabetes_related_diagnosis_hcpcs_code', $web_form_data)) {
                        $procedures_values = array_merge($procedures_values, $web_form_data['diabetes_related_diagnosis_hcpcs_code']);
                    }
                }

                return array_filter($procedures_values);
            break;
        }

        return '';
    }

    protected function sortValues($original_array)
    {
        $sorted_array = array();

        $report_fields = ReportField::where('report_name', 'hedis_export')->get(['name', 'display_name'])->toArray();

        foreach ($report_fields as $field) {
            $sorted_array[$field['display_name']] = isset($original_array[$field['name']]) ? $original_array[$field['name']] : '';
        }

        return $sorted_array;
    }

    protected function generateHedisFile($patient_file_data)
    {
        $current_date_time = Carbon::now()->toDateTimeString();
        $file_name = 'illuma-'.str_replace(' ', '_', $current_date_time);

        $export = Helper::exportExcel($patient_file_data, $file_name, '127.0.0.1', array(), 'csv', config('constants.paths.ccda.temp_ccda'));

        return $file_name;
    }

    protected function checkCredential($network_id)
    {
        $network_name = Network::find($network_id)->name;
        $this->export_status['Network Name'] = $network_name;
        $network_name = str_replace(' ', '_', strtolower($network_name));

        $connections = config('remote.connections');

        return array_key_exists($network_name, $connections);
    }

    protected function sendSSH($file_name, $network_id)
    {
        $network_name = strtolower(Network::find($network_id)->name);
        $network_name = str_replace(' ', '_', $network_name);

        try {
            $ssh = \SSH::into($network_name)->put(base_path().'/temp_ccda/' . $file_name . '.csv', $file_name . '.csv');
        } catch (\Exception $e) {
            return 'failure';
        }

        unlink(base_path().'/temp_ccda/' . $file_name . '.csv');

        return 'success';
    }

    protected function storeFileData($file_data, $network_name)
    {
        $this->export_status['File Data Storage'] = 'False'; //temporary
        return '1';
    }

    protected function sendExportNotification()
    {
        $message = 'HEDIS file generated for ' . $this->export_status['Network Name'] . ' network, containing the information for ' . $this->export_status['Patient Count'] . ' patients. The file was transferred via ' . $this->export_status['Mode of transfer'] . '.';
        if (array_key_exists('SSH Status', $this->export_status)) {
            $message .= ' The transfer was a ' . $this->export_status['SSH Status'] . '.';
        }

        Mail::raw($message, function ($m) {
            $subject = 'HEDIS File Export';
            $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
            $m->to(env('MAIL_HEDIS_NOTIFICATION_TO'), 'illuma Support')->subject($subject);
        });
        return '1';
    }
}
