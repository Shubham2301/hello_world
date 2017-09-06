<?php

namespace myocuhub\Http\Controllers\Reports;

use Auth;
use Datetime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\NetworkWebForm;
use myocuhub\Models\PatientRecord;
use myocuhub\Models\WebFormTemplate;
use myocuhub\Network;
use myocuhub\User;

class RecordReportController extends ReportController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessRecordReportController()) {
            $request->session()->flash('failure', 'Unauthorized Access to the Report. Please contact your administrator.');
            return redirect('/referraltype');
        }

        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }
        reset($networkData);
        $firstNetworkID = key($networkData);
        $webFormList = $this->getNetworkWebForms($firstNetworkID);

        $data['record-report'] = true;
        return view('reports.record_report.index')->with('data', $data)->with('networkData', $networkData)->with('webFormList', $webFormList);
    }

    public function getNetworkWebForms($networkID)
    {
        $webFormTemplateList = NetworkWebForm::where('network_id', $networkID)->get();
        $webFormList = array();
        foreach ($webFormTemplateList as $webForm) {
            $webFormList[$webForm->web_form_template_id] = $webForm->webForm->display_name;
        }
        return $webFormList;
    }

    public function generateReportExcel(Request $request)
    {
        $start_date = Helper::formatDate($request->start_date, config('constants.db_date_format'));
        $end_date = Helper::formatDate($request->end_date, config('constants.db_date_format'));
        $network_id = $request->network_id;
        $web_form_id = $request->web_form_id;
        $patientRecords = PatientRecord::getPatientRecords($start_date, $end_date, $network_id, $web_form_id);
        $webFormTemplate = WebFormTemplate::find($web_form_id);
        $jsonStructure = json_decode($webFormTemplate->web_form_json, true);
        $data = [];
        foreach ($patientRecords as $record) {
            $recordData = json_decode($record->content, true);
            $recordDataUpdate = array();
            foreach ($jsonStructure as $key => $value) {
                $index = str_replace('_', ' ', $key);
                $index = ucwords($index);
                if (is_array($value)) {
                    $recordField = (array_key_exists($key, $recordData) && !empty($recordData[$key])) ? implode(',', $recordData[$key]) : '-';
                    $recordDataUpdate[$index] = $recordField;
                } else {
                    $recordField = (array_key_exists($key, $recordData) && $recordData[$key] != '') ? $recordData[$key] : '-';
                    $recordDataUpdate[$index] = $recordField;
                }
            }
            $data[] = $recordDataUpdate;
        }
        if (!sizeof($data)) {
            $data[] = $jsonStructure;
        }
        $networkName = Network::find($network_id)->name;
        $webFormName = $webFormTemplate->display_name;
        $fileName = 'Record Report - '.$webFormName.' - '.$networkName;
        $fileType = 'xlsx';
        Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('Audits', function ($sheet) use ($data) {
                $sheet->setPageMargin(0.25);
                $sheet->fromArray($data);
            });
        })->export($fileType);
    }
}
