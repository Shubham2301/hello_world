<?php

namespace myocuhub\Http\Controllers\CareConsole;

use Auth;
use DateTime;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Action;
use myocuhub\Models\Appointment;
use myocuhub\Models\AppointmentType;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Facades\Helper;
use myocuhub\Models\MessageTemplate;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\ReferralHistory;
use myocuhub\Models\Timezone;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\Services\ActionService;
use myocuhub\Services\CareConsoleService;
use myocuhub\Services\KPI\KPIService;
use myocuhub\User;

class CareConsoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $KPIService;
    private $ActionService;
    private $CareConsoleService;

    public function __construct(KPIService $KPIService, ActionService $ActionService, CareConsoleService $CareConsoleService)
    {
        $this->KPIService = $KPIService;
        $this->ActionService = $ActionService;
        $this->CareConsoleService = $CareConsoleService;
        $this->middleware('role:care-console,0');
    }

    public function index(Request $request)
    {
        if (!policy(new Careconsole)->accessConsole()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Console. Please contact your administrator.');
            return redirect('/');
        }

        $action = 'Accessed Careconsole';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        $this->CareConsoleService->moveRecallPatientsToConsoleAsPending();
        $overview = $this->getOverviewData();
        return view('careconsole.index')->with('overview', $overview);
    }

    /**
     * @return mixed
     */
    public function getOverviewData()
    {
        $user = Auth::user();
        $userID = $user->id;
        $networkID = $user->userNetwork->first()->network_id;
        $careconsoleStages = Network::find($networkID)->careconsoleStages;
        $overview = array();
        $i = 0;
        foreach ($careconsoleStages as $stage) {
            $overview['stages'][$i]['id'] = $stage->stage_id;
            $overview['stages'][$i]['name'] = $stage->name;
            $overview['stages'][$i]['display_name'] = $stage->display_name;
            $overview['stages'][$i]['color_indicator'] = $stage->color_indicator;
            $overview['stages'][$i]['description'] = $stage->description;
            $overview['stages'][$i]['abbr'] = $stage->abbr;

            $kpis = CareconsoleStage::find($stage->stage_id)->kpi;
            $j = 0;
            foreach ($kpis as $kpi) {
                $count = $this->KPIService->getCount($kpi->name, $networkID, $stage->stage_id);
                $overview['stages'][$i]['kpis'][$j]['id'] = $kpi->id;
                $overview['stages'][$i]['kpis'][$j]['name'] = $kpi->name;
                $overview['stages'][$i]['kpis'][$j]['display_name'] = $kpi->display_name;
                $overview['stages'][$i]['kpis'][$j]['color_indicator'] = $kpi->color_indicator;
                $overview['stages'][$i]['kpis'][$j]['description'] = $kpi->description;
                $overview['stages'][$i]['kpis'][$j]['count'] = $count['precise_count'];
                $overview['stages'][$i]['kpis'][$j]['abbreviated_count'] = $count['abbreviated_count'];
                $j++;
            }
            $overview['stages'][$i]['kpi_count'] = $j;
            $i++;
        }

        $overview['network_practices'] = Network::find(session('network-id'))->practices;

        $loggedInUser = Auth::user();
        $vars = [
            'user_name' => $loggedInUser->name ?: '',
        ];

        $emailTemplate = MessageTemplate::getTemplate('email', 'request_for_appointment', $networkID);
        $overview['request_for_appointment']['email'] = MessageTemplate::prepareMessage($vars, $emailTemplate);
        $overview['request_for_appointment']['email_subject'] = MessageTemplate::getTemplate('email', 'request_for_appointment', $networkID, 'subject') ?: 'Request For Appointment';
        $overview['request_for_appointment']['phone'] = nl2br(MessageTemplate::getTemplate('phone', 'request_for_appointment', $networkID), false);
        $smsTemplate = MessageTemplate::getTemplate('sms', 'request_for_appointment', $networkID);
        $overview['request_for_appointment']['sms'] = MessageTemplate::prepareMessage($vars, $smsTemplate);

        return $overview;
    }

    /**
     * @param Request $request
     */
    public function getDrilldownData(Request $request)
    {
        $stageID = $request->stage;

        $this->CareConsoleService->setPage($request->page);

        if ($stageID == '-1') {
            $stageID = 1;
        }
        $kpiName = $request->kpi;
        $sortParams = [];
        $sortField = $request->sort_field;
        $sortOrder = $request->sort_order;
        $lower_limit = $request->lower_limit;
        $upper_limit = $request->upper_limit;

        if ($upper_limit != -1) {
            $listing = $this->CareConsoleService->getPatientListing($stageID, $kpiName, $sortField, $sortOrder, $lower_limit, $upper_limit);
        } else {
            $listing = $this->CareConsoleService->getPatientListing($stageID, $kpiName, $sortField, $sortOrder);
        }

        $actions = $this->CareConsoleService->getActions($stageID);
        $controls = $this->CareConsoleService->getControls($stageID);
        $drilldown['controls'] = (sizeof($controls) === 0) ? '' : view('careconsole.controls')->with('controls', $controls)->render();
        $drilldown['actions'] = (sizeof($actions) === 0) ? [] : $actions;
        $drilldown['listing_header'] = view('careconsole.listing_header')->with('listing', $listing)->render();
        $drilldown['listing_content'] = view('careconsole.listing_patient')->with('listing', $listing)->with('actions', $actions)->render();
        $drilldown['lastpage'] = $listing['lastpage'];
        return json_encode($drilldown);
    }

    /**
     * @param Request $request
     */
    public function action(Request $request)
    {
        if ($request->update_demographics == 'true') {
            $patientData = $request->patient_data;
            $consoleID = $request->console_id;
            $careconsole = Careconsole::find($consoleID);
            $patientID = $careconsole->patient_id;

            $data = [];
            $data['birthdate'] = ($patientData['dob'] == '') ? null : Helper::formatDate($patientData['dob'], config('constants.db_date_format'));
            $data['email'] = $patientData['email'];
            $data['cellphone'] = $patientData['cellphone'];
            $data['homephone'] = $patientData['homephone'];
            $data['workphone'] = $patientData['workphone'];
            $data['special_request'] = $patientData['special_request'];
            $data['pcp'] = $patientData['pcp'];
            $data['addressline1'] = $patientData['address_line_1'];
            $data['addressline2'] = $patientData['address_line_2'];
            $patient = Patient::where('id', $patientID)->update($data);

            $referralHistory = ReferralHistory::find($careconsole->referral_id);
            if ($referralHistory == null) {
                $referralHistory = new ReferralHistory;
                $referralHistory->save();
                $careconsole->referral_id = $referralHistory->id;
                $careconsole->save();
            }

            $referralHistory->referred_by_provider = $patientData['referred_by_provider'];
            $referralHistory->referred_by_practice = $patientData['referred_by_practice'];
            $referralHistory->network_id = session('network-id');
            $referralHistory->save();

            $insuranceCarrier = PatientInsurance::where('patient_id', '=', $patientID)->orderBy('updated_at', 'desc')->first();
            if ($insuranceCarrier == null) {
                $insuranceCarrier = new PatientInsurance;
                $insuranceCarrier->patient_id = $patientID;
            }

            $insuranceCarrier->insurance_carrier = $patientData['insurance_carrier'];
            $insuranceCarrier->subscriber_name = $patientData['subscriber_name'];
            $insuranceCarrier->subscriber_birthdate = ($patientData['subscriber_birthdate'] == '') ? null : Helper::formatDate($patientData['subscriber_birthdate'], config('constants.db_date_format'));
            $insuranceCarrier->subscriber_id = $patientData['subscriber_id'];
            $insuranceCarrier->subscriber_relation = $patientData['relation_to_patient'];
            $insuranceCarrier->insurance_group_no = $patientData['group_number'];
            $insuranceCarrier->save();
        }

        $actionID = $request->action_id;
        $actionResultID = $request->action_result_id;
        $recallDate = $request->recall_date;
        $manualAppointmentData = [];
        $manualAppointmentData['appointment_date'] = $request->manual_appointment_date;
        $manualAppointmentData['practice_id'] = $request->manual_appointment_practice;
        $manualAppointmentData['location_id'] = $request->manual_appointment_location;
        $manualAppointmentData['provider_id'] = $request->manual_appointment_provider;
        $manualAppointmentData['appointment_type'] = $request->manual_appointment_appointment_type;
        $manualAppointmentData['custom_appointment_type'] = $request->custom_appointment_type;
        $manualAppointmentData['referredby_practice'] = $request->manual_referredby_practice;
        $manualAppointmentData['referredby_provider'] = $request->manual_referredby_provider;
        $notes = $request->notes;
        $consoleID = $request->console_id;
        $message = $request->request_message;
        $contactHistoryID = $this->ActionService->userAction($actionID, $actionResultID, $recallDate, $notes, $message, $consoleID, $manualAppointmentData);
        $stage = CareConsole::find($consoleID)->stage;
        $patientStage['id'] = $stage->id;
        $patientStage['name'] = $stage->display_name;

        $actionName = Action::find($actionID)->display_name;
        $action = "Performed Action : '$actionName' on Console Entry : $consoleID in the Careconsole";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        return json_encode($patientStage);
    }

    /**
     * @param Request $request
     */
    public function searchPatients(Request $request)
    {
        if (session('user-level') == 1) {
            $practices = Patient::where('firstname', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->leftjoin('patient_insurance', 'patients.id', '=', 'patient_insurance.patient_id')
                ->orWhere('middlename', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('lastname', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('lastfourssn', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('city', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('addressline1', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('addressline2', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('country', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('cellphone', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('email', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->orWhere('patient_insurance.subscriber_id', 'LIKE', '%' . $tosearchdata['value'] . '%')
                ->get(['*', 'patients.id']);
        } else {
            $patients = Patient::getPatientsByName($request->name, session('network-id'));
        }
        $i = 0;
        $results = [];

        foreach ($patients as $patient) {
            $console = Careconsole::where('patient_id', $patient->id)->first();
            if ($console) {
                $results[$i]['archived_date'] = null;
                $results[$i]['recall_date'] = null;
                if ($console->archived_date) {
                    $results[$i]['archived_date'] = $console->archived_date;
                }

                if ($console->recall_date) {
                    $results[$i]['recall_date'] = $console->recall_date;
                }

                $patient['appointment_id'] = $console->appointment_id;
                $results[$i]['id'] = $patient->id;
                $results[$i]['console_id'] = $console->id;
                $results[$i]['stage_id'] = $console->stage_id;
                $results[$i]['name'] = $patient->getName('system_format');
                
                if ($console->recall_date) {
                    $bucket = 'recall';
                    $bucket = CareconsoleStage::where('name', $bucket)->first();
                    $bucketID = $bucket->id;
                    $results[$i]['actions'] = $this->CareConsoleService->getActions($bucketID);
                    $results[$i]['stage_name'] = CareconsoleStage::find($bucketID)->display_name;
                    $results[$i]['stage_color'] = CareconsoleStage::find(1)->color_indicator;
                } elseif ($console->archived_date) {
                    $bucket = 'archived';
                    $bucket = CareconsoleStage::where('name', $bucket)->first();
                    $bucketID = $bucket->id;
                    $results[$i]['actions'] = $this->CareConsoleService->getActions($bucketID);
                    $results[$i]['stage_name'] = CareconsoleStage::find($bucketID)->display_name;
                    $results[$i]['stage_color'] = CareconsoleStage::find(1)->color_indicator;
                } else {
                    $results[$i]['actions'] = $this->CareConsoleService->getActions($console->stage_id);
                    $results[$i]['stage_name'] = CareconsoleStage::find($console->stage_id)->display_name;
                    $results[$i]['stage_color'] = CareconsoleStage::find($console->stage_id)->color_indicator;
                }

                $results[$i]['scheduled_to'] = '-';
                $results[$i]['appointment_date'] = '-';
                if ($patient['appointment_id']) {
                    $appointment = Appointment::find($patient['appointment_id']);
                    $provider = User::find($appointment->provider_id);
                    $results[$i]['scheduled_to'] = ($provider) ? $provider->lastname . ', ' . $provider->firstname : '-';
                    $results[$i]['appointment_date'] = $this->CareConsoleService->getPatientFieldValue($patient, 'appointment-date');
                }
                $results[$i]['days_pending'] = $this->CareConsoleService->getPatientFieldValue($console, 'days-pending');
                $results[$i]['last_scheduled_to'] = $this->CareConsoleService->getPatientFieldValue($console, 'last-scheduled-to');
                $results[$i]['contact_attempts'] = $this->CareConsoleService->getPatientFieldValue($console, 'contact-attempts');
                $i++;
            }
        }
        return json_encode($results);
    }

    /**
     * @param Request $request
     */
    public function getBucketPatients(Request $request)
    {
        $this->CareConsoleService->setPage($request->page);
        $bucketName = $request->bucket;
        $bucket = CareconsoleStage::where('name', $bucketName)->first();
        $bucketID = $bucket->id;
        $sortField = $request->sort_field;
        $sortOrder = $request->sort_order;
        $listing = $this->CareConsoleService->getBucketPatientsListing($bucketID, $sortField, $sortOrder);
        $actions = $this->CareConsoleService->getActions($bucketID);
        $drilldown['actions'] = (sizeof($actions) === 0) ? [] : $actions;
        $drilldown['listing_header'] = view('careconsole.listing_header')->with('listing', $listing)->render();
        $drilldown['listing_content'] = view('careconsole.listing_patient')->with('listing', $listing)->with('actions', $actions)->render();
        $drilldown['lastpage'] = $listing['lastpage'];
        return json_encode($drilldown);
    }
    public function getPatientRecords(Request $request)
    {
        $data = [];
        $consoleID = $request->consoleID;
        $console = Careconsole::find($consoleID);
        $patient = Patient::find($console->patient_id);
        $data['patient_id'] = $console->patient_id;
        $data['name'] = $patient->getName('system_format');
        $data['phone'] = $patient->cellphone;
        if ($console->recall_date) {
            $bucket = 'recall';
            $bucket = CareconsoleStage::where('name', $bucket)->first();
            $bucketID = $bucket->id;
            $data['actions'] = $this->CareConsoleService->getActions($bucketID);
        } elseif ($console->archived_date) {
            $bucket = 'archived';
            $bucket = CareconsoleStage::where('name', $bucket)->first();
            $bucketID = $bucket->id;
            $data['actions'] = $this->CareConsoleService->getActions($bucketID);
        } else {
            $data['actions'] = $this->CareConsoleService->getActions($console->stage_id);
        }
        $data['stageid'] = $console->stage_id;
        $data['priority'] = $console->priority;

        $appointment = Appointment::find($console->appointment_id);
        $provider = null;
        $data['appointment_type'] = '-';
        if ($appointment) {
            $provider = User::find($appointment->provider_id);
            $data['appointment_type'] = $appointment->appointmenttype;
        }
        $timezone = $patient->timezone;
        $data['timezone'] = ($timezone) ? $timezone->getName() : '';
        $data['special_request'] = ($patient->special_request != null && $patient->special_request != '') ? $patient->special_request : '-';
        $data['pcp'] = ($patient->pcp != null && $patient->pcp != '') ? $patient->pcp : '-';
        $data['scheduled_to'] = ($provider) ? $provider->title . ' ' . $provider->lastname . ', ' . $provider->firstname : '-';
        $data['appointment_date'] = ($console->appointment_id) ? $this->CareConsoleService->getPatientFieldValue($console, 'appointment-date') : '-';
        $data['contacts_attempt'] = $this->ActionService->getContactActions($consoleID);

        return json_encode($data);
    }

    public function getPatientInfo(Request $request)
    {
        $data = [];
        $patient = Patient::find($request->patientID);
        $console = Careconsole::where('patient_id', $request->patientID)->first();
        $data['patient_id'] = $request->patientID;
        $data['name'] = $this->CareConsoleService->getPatientFieldValue($patient, 'full-name');
        $data['cellphone'] = $this->CareConsoleService->getPatientFieldValue($patient, 'cellphone');
        $data['homephone'] = $this->CareConsoleService->getPatientFieldValue($patient, 'homephone');
        $data['workphone'] = $this->CareConsoleService->getPatientFieldValue($patient, 'workphone');
        $data['email'] = $this->CareConsoleService->getPatientFieldValue($patient, 'email');
        $data['special_request'] = $this->CareConsoleService->getPatientFieldValue($patient, 'special-request');
        $data['pcp'] = $this->CareConsoleService->getPatientFieldValue($patient, 'pcp');
        $data['dob'] = $this->CareConsoleService->getPatientFieldValue($patient, 'dob');
        $data['address_line_1'] = $this->CareConsoleService->getPatientFieldValue($patient, 'address-line-1');
        $data['address_line_2'] = $this->CareConsoleService->getPatientFieldValue($patient, 'address-line-2');
        $data['last_seen_by'] = $this->CareConsoleService->getPatientFieldValue($patient, 'last-scheduled-to');
        $data['insurance_carrier'] = $this->CareConsoleService->getPatientFieldValue($patient, 'insurance-carrier');
        $data['subscriber_birthdate'] = $this->CareConsoleService->getPatientFieldValue($patient, 'subscriber-birthdate');
        $data['group_number'] = $this->CareConsoleService->getPatientFieldValue($patient, 'group-number');
        $data['subscriber_name'] = $this->CareConsoleService->getPatientFieldValue($patient, 'subscriber-name');
        $data['subscriber_id'] = $this->CareConsoleService->getPatientFieldValue($patient, 'subscriber-id');
        $data['relation_to_patient'] = $this->CareConsoleService->getPatientFieldValue($patient, 'relation-to-patient');
        $data['referred_by_provider'] = $this->CareConsoleService->getPatientFieldValue($console, 'referred-by-provider');
        $data['referred_by_practice'] = $this->CareConsoleService->getPatientFieldValue($console, 'referred-by-practice');
        $timezone = $patient->timezone;
        $data['timezone'] = ($timezone) ? $timezone->getName() : '';

        return json_encode($data);
    }
    public function practiceProviders(Request $request)
    {
        $practiceID = $request->practiceID;
        $practiceUsers = User::practiceProvidersById($practiceID, session('network-id'));
        $i = 0;
        $practiceData = [];
        foreach ($practiceUsers as $user) {
            $practiceData['provider'][$i]['id'] = $user->user_id;
            $practiceData['provider'][$i]['name'] = $user->lastname . ', ' . $user->firstname;
            $i++;
        }
        $practiceData['locations'] = [];

        if (Practice::find($practiceID)) {
            $practiceData['locations'] = Practice::find($practiceID)->locations;
        }
        return json_encode($practiceData);
    }

    public function updateManualScheduleData($consoleID)
    {
        $data = [];
        $console = Careconsole::find($consoleID);
        $data['referred_by_practice'] = $this->CareConsoleService->getPatientFieldValue($console, 'referred-by-practice');
        $data['referred_by_provider'] = $this->CareConsoleService->getPatientFieldValue($console, 'referred-by-provider');
        $appointmentTypes = AppointmentType::where('network_id', session('network-id'))->where('type', 'ocuhub')->orderBy('name', 'ASC')->get();
        $appointmentTypeList = [];
        foreach ($appointmentTypes as $appointmentType) {
            $appointmentTypeList[] = $appointmentType->display_name;
        }
        $data['appointment_type'] = $appointmentTypeList;
        return $data;
    }
}
