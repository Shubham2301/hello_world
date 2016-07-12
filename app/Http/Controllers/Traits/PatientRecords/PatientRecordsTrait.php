<?php

namespace myocuhub\Http\Controllers\Traits\PatientRecords;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\WebFormTemplate;
use myocuhub\Models\PatientRecord;
use myocuhub\Events\Patient\PatientRecordCreation;

trait PatientRecordsTrait
{

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
}
