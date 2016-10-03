<?php

namespace myocuhub\Http\Controllers\Traits\PatientRecords;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Events\Patient\PatientRecordCreation;
use myocuhub\Models\PatientRecord;
use myocuhub\Models\WebFormTemplate;
use myocuhub\Patient;
use myocuhub\Services\ActionService;
use myocuhub\Models\ProviderType;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\Action;
use Auth;
use DateTime;
use PDF;
use Helper;
use myocuhub\Network;

trait PatientRecordsTrait
{
    private $ActionService;

    public function __construct(ActionService $ActionService)
    {
        $this->ActionService = $ActionService;
    }

    public function showRecord(Request $request)
    {
        $record = PatientRecord::find($request->id);
        return view('web-forms.show', [ 'template' => $record->template, 'record' => $record->content]);
    }

    public function createRecord($templateName, $patientID)
    {
        $patient = Patient::find($patientID);
        $template = WebFormTemplate::get($templateName);
        return view('web-forms.create', [ 'template' => $template, 'patient' => $patient]);
    }

    public function printRecord($contactHistoryID)
    {
        $pdf = $this->createPDF($contactHistoryID);

        return $pdf->inline();

    }

    public function getPatientRecordView(Request $request)
    {
        $patient = array();
        $patient['id'] = $request->patient_id;
        return view('patient-records.index')->with('patient', $patient);
    }

    public function getWebFormIndex(Request $request)
    {
        $forms = Network::find(session('network-id'))->webForms;
        return view('web-forms.index', ['forms' => $forms]);
    }

    public function savePatientRecord(Request $request)
    {
        $data = [
            'web_form_template_id' => $request->template_id,
            'patient_id' => $request->patient_id,
            'content' => json_encode($request->all())
        ];
        $record =  PatientRecord::create($data);
        $patientID = $data['patient_id'];

        $templateID = $data['web_form_template_id'];

        $contactHistoryID =  $this->createContactHistory($patientID, $templateID);

        $record->contact_history_id = $contactHistoryID;
        $record->save();
        event(new PatientRecordCreation(
            [
                'template_id' => $request->template_id,
                'patient_id' => $request->patient_id,
                'ip'   => $request->getClientIp(),
                'contact_history_id' => $contactHistoryID,

            ]
        ));

        $request->session()->put('success', 'Record created successfully');

        if( (WebFormTemplate::find($request->template_id)->name == 'Eye exam report') )
        {
            if (( ($request->has('ORR') && $request->ORR == 'yes') || ($request->has('surgery_referral') && $request->surgery_referral == 'yes')))
            {
                $specialRequest = ($request->has('ORR') && $request->ORR == 'yes') ? 'Ophthalmology Retinal Referral' : 'Cataract Surgery Referral';

                $patient = Patient::find($data['patient_id']);
                $patient->special_request = $specialRequest;
                $patient->save();

                if ( Network::find(session('network-id'))->enable_console == 1)
                {
                    $actionID = Action::where('name', 'move-to-contact-status')->first()->id;
                    $movePatient = $this->ActionService->userAction($actionID, '-1', null, 'Moved patient to Contact Status', '', $patient->careConsole->id, '');
                }

                $providerTypeId = ProviderType::where('abbr', 'MD')->first()->id;
                return redirect('/providers?referraltype_id=6&action=schedule_appointment&patient_id='.$data['patient_id'].'&provider_type_id='.$providerTypeId.'&record_id='.$record->id);
            }
        }
        return redirect('/webform');
    }

    public function PatientListForCreateRecord(Request $request)
    {
        $patients = $this->search($request);
        $patients = json_decode($patients, true);
        return view('web-forms.search_patient')->with('patients', $patients)->render();
    }

    public function PatientListForShowRecord(Request $request)
    {
        $patients = $this->search($request);
        $patients = json_decode($patients, true);
        if (!array_key_exists('id', $patients[0])) {
            return 'No result found';
        }

        return (sizeof($patients) === 0) ? 'No patient found' : view('patient-records.patient_listing')->with('patients', $patients)->render();
    }

    public function changeValuesInArray($dataArray, $getResult)
    {
        $i = 0 ;
        foreach ($dataArray as $data) {
            $dataArray[$i]['date'] = explode(" ", $data['date']);
            $dataArray[$i]['notes'] = explode(config('constants.schedule_notes_delimiter'), $data['notes']);

            if ($i > $getResult) {
                break;
            }

            $i++;
        }

        return array_slice($dataArray, 0, $getResult);
    }

    public function getCareTimeLine(Request $request)
    {
        $patientID = $request->patient_id;
        $getResult = $request->getresult;
        if ($getResult == "") {
            $getResult = 0;
        }

        $getResult += config('constants.default_timeline_result');
        $patient = Patient::find($patientID);
        $consoleID = $patient->careConsole->id;
        $progress = $this->ActionService->getContactActions($consoleID);
        $progress = $this->changeValuesInArray($progress, $getResult);

        return view('patient-records.care_timeline')
            ->with('progress', $progress)
            ->with('getResults', $getResult)
            ->with('patientID', $patientID)
            ->render();
    }


    public function createContactHistory($patientID, $templateID)
    {
        $actionID = Action::where('name', 'create-record')->first()->id;
        $templateName = WebFormTemplate::find($templateID)->display_name;
        $contactDate = new DateTime();
        $patient = Patient::find($patientID);
        $consoleID = $patient->careConsole->id;
        $contactHistory = new ContactHistory;
        $contactHistory->user_id = Auth::user()->id;
        $contactHistory->action_id = $actionID;
        $contactHistory->notes = $templateName;
        $contactHistory->console_id = $consoleID;
        $contactHistory->contact_activity_date = $contactDate->format(config('constants.db_date_format'));
        $contactHistory->save();

        return $contactHistory->id;
    }

    public function CreatePDF($contactHistoryID)
    {

        $record = ContactHistory::find($contactHistoryID)->record;
        $patientID = $record->patient_id;
        $patient = Patient::find($patientID);

        $recordData = json_decode($record->content, true);

        $data['patient'] = $patient;
        $data['record'] = $recordData;
        $data['signature'] = '';

        if (isset($data['record']['sigoutput']) && $data['record']['sigoutput'] != '') {
            $data['signature'] = Helper::sigJsonToImage($data['record']['sigoutput']);
        }
        $printView = $record->template->print_view;
        $html = view('patient-records.prints.'.$printView)->with('data', $data)->render();
        $pdf = PDF::loadHtml($html);
        return $pdf;
    }
}
