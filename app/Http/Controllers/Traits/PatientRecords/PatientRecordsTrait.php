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

    public function createRecord($name)
    {
        $template = WebFormTemplate::get($name);
        return view('web-forms.create', [ 'template' => $template]);
    }

    public function printRecord(Request $request)
    {
    }

    public function getPatientRecordView(Request $request)
    {
        return view('patient-records.index');
    }

    public function getWebFormIndex(Request $request)
    {
        $forms = WebFormTemplate::all();
        return view('web-forms.index', ['forms' => $forms]);
    }

    public function savePatientRecord(Request $request)
    {
        $data = [
            'web_form_template_id' => $request->template_id,
            'patient_id' => $request->patient_id,
            'content' => json_encode($request->all())
        ];

        PatientRecord::create($data);
        $request->session()->put('success', 'Record created successfully');
        event(new PatientRecordCreation(
            [
                'template_id' => $request->template_id,
                'patient_id' => $request->patient_id,
                'ip'   => $request->getClientIp()
            ]
        ));
        return redirect('/webform');
    }

    public function PatientListForCreateRecord(Request $request)
    {
        $patients = $this->search($request);
        $patients = json_decode($patients, true);
        if (!array_key_exists('id', $patients[0])) {
            return 'No result found';
        }
        return (sizeof($patients) === 0) ? 'No patient found' : view('web-forms.search_patient')->with('patients', $patients)->render();
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

    public function changeDateInArray($dataArray)
    {
        $i = 0 ;
        foreach ($dataArray as $data) {
            $dataArray[$i]['date'] = explode(" ", $data['date']);
            $dataArray[$i]['notes'] = explode("</br>", $data['notes']);
            $i++;
        }
        return $dataArray;
    }

    public function getCareTimeLine($patientID)
    {
        $patient = Patient::find($patientID);
        $consoleID = $patient->careConsole->id;
        $progress = $this->ActionService->getContactActions(23836);
        $progress = $this->ActionService->getContactActions($consoleID);
        $progress = $this->changeDateInArray($progress);
        return view('patient-records.care_timeline')
        ->with('progress', $progress)->render();

    }
}
