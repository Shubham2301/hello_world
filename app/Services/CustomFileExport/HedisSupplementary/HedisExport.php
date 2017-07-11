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
    private $file_order = [
        'product_id' => 'Product ID',
        'secondary_insurance' => 'SecondaryInsurance',
        'member_key' => 'MemberKey',
        'member_hcid' => 'MemberHCID',
        'medicare_hic' => 'Medicare HIC',
        'medicaid_id' => 'Medicaid ID',
        'member_last_name' => 'Member Last Name',
        'member_first_name' => 'Member First Name',
        'address_1' => 'Address 1',
        'address_2' => 'Address 2',
        'city' => 'City',
        'state' => 'State',
        'zipcode' => 'Zipcode',
        'member_email' => 'MemberEmail',
        'member_telephone_1' => 'MemberTelephone',
        'member_telephone_2' => 'MemberTelephone 2',
        'gender' => 'Gender',
        'race' => 'Race',
        'dob' => 'DOB',
        'rendering_provider_key' => 'Rendering Provider Key',
        'rendering_provider_name' => 'Rendering Provider Name',
        'rendering_provider_npi' => 'Rendering Provider NPI',
        'rendering_provider_tax_id' => 'Rendering Provider Tax ID',
        'rendering_provider_speciality_1' => 'Rendering Provider Specialty (1)',
        'rendering_provider_speciality_2' => 'Rendering Provider Specialty (2)',
        'date_of_service' => 'Date of Service',
        'dxpri' => 'DxPri',
        'dxsec1' => 'DxSec1',
        'dxsec2' => 'DxSec2',
        'dxsec3' => 'DxSec3',
        'dxsec4' => 'DxSec4',
        'dxsec5' => 'DxSec5',
        'dxsec6' => 'DxSec6',
        'dxsec7' => 'DxSec7',
        'dxsec8' => 'DxSec8',
        'procedures' => 'Procedure',
        'loinc' => 'LOINC',
        'test_name' => 'Test Name',
        'test_result_value' => 'Test Result Value',
        'ndc' => 'NDC',
        'rx_fill_date' => 'RxFillDate',
        'rx_days_supply' => 'RxDays Supply',
        'bmi' => 'BMI',
        'bmi_percentile' => 'BMIPercentile',
        'height' => 'Height',
        'weight' => 'Weight',
        'systolic_blood_pressure' => 'Systolic Blood Pressure',
        'diastolic_blood_pressure' => 'Diastolic Blood Pressure',
        'dialated_eye_exam_performed_by_professional' => 'DilatedEyeExam Performed by eye care professional',
        'place_of_service' => 'Place of Service',
    ];

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
            $file_data[] = self::getPatientFileData($patientID);
        }
        return $file_data;
    }

    public function getNetworkPatientList($network_id)
    {
        $patient_list = array();

        $careconsole_patients = CareConsole::has('patient')
            ->where('stage_id', '5')
            ->whereHas('importHistory', function ($subquery) use ($network_id) {
                $subquery->where('network_id', $network_id);
            })
            ->whereNull('archived_date')
            ->whereNull('recall_date')
            ->with(['latestContactHistory', 'latestContactHistory.action'])
            ->get();

        foreach ($careconsole_patients as $patient) {
            if ($patient->latestContactHistory->action->name != 'hedis-data-exported') {
                $patient_list[] = $patient->patient_id;
            }
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
        $default_field_data = self::getDefaultFieldData();

        $patient_file_data = array_merge($system_data, $default_field_data, $ccda_data, $web_form_data);

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

            $this->web_form_data = $record_data;
            return self::processData($record_data, 'web-form');
        }

        return [];
    }

    protected function getDefaultFieldData()
    {
        $data_array = array();

        $data_array['place_of_service'] = '11';

        $data_array['secondary_insurance'] = '';
        $data_array['member_hcid'] = '';
        $data_array['medicare_hic'] = '';
        $data_array['medicaid_id'] = '';
        $data_array['rendering_provider_key'] = '';
        $data_array['rendering_provider_speciality_2'] = '';
        $data_array['rendering_provider_tax_id'] = '';
        $data_array['rx_fill_date'] = '';
        $data_array['rx_days_supply'] = '';

        $data_array['test_name'] = '';
        $data_array['test_result_value'] = '';

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
                $data_array['dialated_eye_exam_performed_by_professional'] = self::getFieldValue($data, 'dialated_eye_exam_performed_by_professional');
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
                $data_array['member_telephone_1'] = $data['demographics']['phone']['home'];
                $data_array['member_telephone_2'] = $data['demographics']['phone']['mobile'];
                $data_array['gender'] = self::getFieldValue($data['demographics']['gender'], 'gender');
                $data_array['race'] = $data['demographics']['race'];
                $data_array['dob'] = self::getFieldValue($data['demographics']['dob'], 'dob');

                $data_array['bmi'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'bmi') : '';
                $data_array['bmi_percentile'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'percentile') : '';
                $data_array['height'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'height') : '';
                $data_array['weight'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'weight') : '';
                $data_array['systolic_blood_pressure'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'systolic') : '';
                $data_array['diastolic_blood_pressure'] = (isset($data['vitals']) && count($data['vitals']) != 0) ? self::getFieldValue($data['vitals'][ count($data['vitals']) - 1 ]['results'], 'diastolic') : '';

                $data_array = array_merge($data_array, self::getFieldValue($data, 'diagnosis'));
                $data_array['loinc'] = isset($data['results']) ? self::getFieldValue($data['results'], 'loinc') : '';
                $data_array['ndc'] = isset($data['medications']) ? self::getFieldValue($data['medications'], 'ndc') : '';
                break;
            case 'web-form':
                $procedure_data = array();
                $ccda_procedure = isset($this->ccda_data['procedures']) ? self::getFieldValue($this->ccda_data['procedures'], 'procedures') : [];
                $web_form_procedure = self::getFieldValue($this->web_form_data, 'procedures_web_form');
                
                $procedure_data = array_merge($ccda_procedure, $web_form_procedure);

                $data_array['procedures'] = implode('; ', $procedure_data);
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
            case 'gender':
                if (strtolower($data) == 'male' || strtolower($data) == 'm') {
                    return 'M';
                } else {
                    return 'F';
                }
            case 'dob':
                $date = new \DateTime($data);
                return $date->format($this->dateFormat);
                break;
            case 'member_key':
                $member_insurance = $data->load('patient')->patient->load('patientInsurance')->patientInsurance;
                if (isset($member_insurance)) {
                    return $member_insurance->subscriber_id;
                }
                break;
            case 'dialated_eye_exam_performed_by_professional':
                $patient_id = $data->patient_id;
                $web_form_check = PatientRecord::where('patient_id', $patient_id)
                ->whereHas('WebFormTemplate', function ($query) {
                    $query->where('name', 'illuma-web-form');
                })->first();
                if ($web_form_check) {
                    return 'Y';
                }
                return 'N';
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
            case 'diagnosis':
                $diagnosis_array = array();
                for ($i=0; $i < 9; $i++) {
                    switch ($i) {
                        case '0':
                            $diagnosis_array['dxpri'] = '';
                            if (isset($data['problems']['0'])) {
                                if (isset($data['problems']['0']['code'])) {
                                    $diagnosis_array['dxpri'] = $data['problems']['0']['code'];
                                } elseif (isset($data['problems']['0']['translation']) && isset($data['problems']['0']['translation']['code'])) {
                                    $diagnosis_array['dxpri'] = $data['problems']['0']['translation']['code'];
                                }
                            }
                            break;
                        default:
                            $diagnosis_array['dxsec'.$i] = '';
                            if (isset($data['problems'][$i])) {
                                if (isset($data['problems'][$i]['code'])) {
                                    $diagnosis_array['dxsec'.$i] = $data['problems'][$i]['code'];
                                } elseif (isset($data['problems'][$i]['translation']) && isset($data['problems'][$i]['translation']['code'])) {
                                    $diagnosis_array['dxsec'.$i] = $data['problems'][$i]['translation']['code'];
                                }
                            }
                    }
                }
                return $diagnosis_array;
                break;
            case 'procedures':
                $procedures_values = array();
                foreach ($data as $data_row) {
                    array_push($procedures_values, $data_row['code']);
                }
                return $procedures_values;
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
                        if (isset($data_row['product']['code'])) {
                            array_push($ndc, $data_row['product']['code']);
                        } elseif (isset($data_row['product']['translation'])) {
                            array_push($ndc, $data_row['product']['translation']['code']);
                        }
                    }
                }
                return implode('; ', $ndc);
                break;
            case 'procedures_web_form':
                $procedures_values = array();
                if (array_key_exists('diabetes_related_diagnosis', $data)) {
                    $procedures_values = array_merge($procedures_values, $data['diabetes_related_diagnosis']);
                }
                if (array_key_exists('diabetes_related_diagnosis_cpt_codes', $data)) {
                    $procedures_values = array_merge($procedures_values, $data['diabetes_related_diagnosis_cpt_codes']);
                }
                if (array_key_exists('diabetes_related_diagnosis_hcpcs_code', $data)) {
                    $procedures_values = array_merge($procedures_values, $data['diabetes_related_diagnosis_hcpcs_code']);
                }

                return $procedures_values;
            break;
        }

        return '';
    }

    protected function sortValues($original_array)
    {
        $sorted_array = array();
        foreach ($this->file_order as $key => $value) {
            $sorted_array[$value] = isset($original_array[$key]) ? $original_array[$key] : '';
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
