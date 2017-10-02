<?php

namespace myocuhub\Http\Controllers\Reports;

use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Reports\ReportController;
use myocuhub\Models\ContactHistory;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\Services\CareConsoleService;
use myocuhub\User;

class PatientExportController extends ReportController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $CareConsoleService;

    public function __construct(CareConsoleService $CareConsoleService)
    {
        $this->CareConsoleService = $CareConsoleService;
    }

    public function index(Request $request)
    {
        if (!policy(new ReportController)->accessPatientExportController()) {
            $request->session()->flash('failure', 'Unauthorized Access. Please contact your administrator.');
            return redirect('/referraltype');
        }
        $networks = Network::all()->sortBy("name");
        foreach ($networks as $network) {
            $networkData[$network->id] = $network->name;
        }
        reset($networkData);

        $data['export-patient-activity'] = true;
        return view('reports.export_patient_activity.index')->with('data', $data)->with('networkData', $networkData);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!policy(new ReportController)->accessPatientExportController()) {
            $request->session()->flash('failure', 'Unauthorized Access. Please contact your administrator.');
            return redirect('/referraltype');
        }
        
        $this->generatePatientExcel($request);
    }

    public function generatePatientExcel(Request $request)
    {
        self::setStartDate($request->start_date);
        self::setEndDate($request->end_date);

        $network_id = ($request->network_id) ? $request->network_id : null;
        $start_date = self::getStartDate();
        $end_date = self::getEndDate();
        $networkName = ($request->network_id) ?  Network::find($network_id)->name : '' ;
        $networkData = [];
        $networkData = ContactHistory::getPatientExcelData($network_id, $start_date, $end_date);
        $data = [];
        foreach ($networkData as $contactHistory) {
            $temp = array();
            $temp['Patient ID'] = $contactHistory->careconsole->patient->id;
            $temp['Patient Name'] = $this->CareConsoleService->getPatientFieldValue($contactHistory->careconsole->patient, 'print-name');
            $temp['Email'] = $this->CareConsoleService->getPatientFieldValue($contactHistory->careconsole->patient, 'email');
            $temp['Birthdate'] = $this->CareConsoleService->getPatientFieldValue($contactHistory->careconsole->patient, 'dob');
            $temp['PCP'] = $this->CareConsoleService->getPatientFieldValue($contactHistory->careconsole->patient, 'pcp');
            $temp['Referred By Practice'] = $this->CareConsoleService->getPatientFieldValue($contactHistory->careconsole, 'referred-by-practice');
            $temp['Referred By Provider'] = $this->CareConsoleService->getPatientFieldValue($contactHistory->careconsole, 'referred-by-provider');
            $temp['Contact Activity Date'] = $this->CareConsoleService->getPatientFieldValue($contactHistory, 'contact-activity-date');
            $temp['Action Name'] = ($contactHistory->action) ? $contactHistory->action->display_name : '-' ;
            $temp['Action Result'] = ($contactHistory->actionResult) ? $contactHistory->actionResult->display_name : '-' ;
            $temp['Notes'] = ($contactHistory->notes) ? strip_tags(str_replace("</br>", " ", $contactHistory->notes)) : '-';
            $temp['Appointment Date'] = ($contactHistory->appointments) ? $this->CareConsoleService->getPatientFieldValue($contactHistory, 'appointment-date') : '-';
            $temp['Scheduled To Provider'] = ($contactHistory->appointments) ? $this->CareConsoleService->getPatientFieldValue($contactHistory, 'provider-name') : '-';
            $temp['Scheduled To Practice'] = ($contactHistory->appointments) ? $this->CareConsoleService->getPatientFieldValue($contactHistory, 'practice-name') : '-';
            $temp['Scheduled To Location'] = ($contactHistory->appointments) ? $this->CareConsoleService->getPatientFieldValue($contactHistory, 'location-name') : '-';
            $data[] = $temp;
        }

        $fileName = $networkName.' Patient Activity List';
        $fileType = 'xlsx';
        Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('Audits', function ($sheet) use ($data) {
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  35,
                    'C'     =>  35,
                    'D'     =>  35,
                    'E'     =>  35,
                    'F'     =>  35,
                    'G'     =>  35,
                    'H'     =>  35,
                    'I'     =>  35,
                    'J'     =>  35,
                    'K'     =>  70,
                    'L'     =>  35,
                    'M'     =>  35,
                    'N'     =>  35,
                    'O'     =>  35,
                    'P'     =>  35,
                ));

                $sheet->setPageMargin(0.25);
                $sheet->fromArray($data);
                $sheet->cell('A1:F1', function ($cells) {
                    $cells->setFont(array(
                        'family'     => 'Calibri',
                        'size'       => '11',
                        'bold'       =>  true
                    ));
                });
            });
        })->export($fileType);
    }
}
