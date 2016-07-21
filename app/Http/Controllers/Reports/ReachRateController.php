<?php

namespace myocuhub\Http\Controllers\Reports;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Models\ContactHistory;
use myocuhub\User;
use Datetime;

class ReachRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $startDate;
    protected $endDate;

    public function index()
    {
        return view('reports.reach_rate_report.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $userID = Auth::user()->id;
        $network = User::getNetwork($userID);
        $networkID = $network->network_id;

        $this->setStartDate($request->start_date);
        $this->setEndDate($request->end_date);

        $result = array();

        $result['success'] = 0;
        $result['dropout'] = 0;
        $result['reached'] = 0;
        $result['not_reached'] = 0;
        $result['appointment_scheduled'] = 0;
        $result['show'] = 0;
        $result['no_show'] = 0;
        $result['cancelled'] = 0;
        $result['appointment_completed'] = 0;
        $result['reports'] = 0;
        $result['repeat_count'] = 0;
        $result['pending_patient'] = 0;

        $careconsole_data = Careconsole::getReachRateData($networkID, $this->getStartDate(), $this->getEndDate());
        $result['patient_count'] = count($careconsole_data);

        foreach ($careconsole_data as $careconsole) {
            $history = array();

            $history['not_reached'] = array();
            $history['reached'] = array();

            $history['repeat_count'] = 0;
            $history['archived'] = 0;
            $history['unarchived'] = 0;
            $history['reached'][0] = 0;
            $history['not_reached'][0] = 0;

            foreach ($careconsole->contactHistory as $contact_history) {
                switch ($contact_history->action->name) {
                    case 'request-patient-email':
                        break;
                    case 'request-patient-phone':
                        break;
                    case 'request-patient-sms':
                        break;
                    case 'contact-attempted-by-phone':
                    case 'contact-attempted-by-email':
                    case 'contact-attempted-by-mail':
                    case 'contact-attempted-by-other':
                    case 'patient-notes':
                    case 'requested-data':

                        break;
                    case 'schedule':
                    case 'reschedule':
                    case 'manually-schedule':
                    case 'manually-reschedule':
                        $result['appointment_scheduled']++;
                        $history['reached'][$history['repeat_count']]++;
                        break;
                    case 'move-to-console':
                        break;
                    case 'recall-later':
                        break;
                    case 'unarchive':
                        if($history['unarchived'] <= $history['archived'] && $history['unarchived'] != 0) {
                            $history['repeat_count']++;
                            $history['reached'][$history['repeat_count']] = 0;
                            $history['not_reached'][$history['repeat_count']] = 0;
                        }
                        $history['unarchived']++;
                        break;
                    case 'archive':
                        $history['archived']++;
                        if($contact_history->currentStage->name == 'finalization')
                            $result['success']++;
                        else
                            $result['dropout']++;
                        break;
                    case 'kept-appointment':
                        $result['appointment_completed']++;
                        $result['show']++;
                        break;
                    case 'no-show':
                        $result['appointment_completed']++;
                        $result['no_show']++;
                        break;
                    case 'cancelled':
                        $result['appointment_completed']++;
                        $result['cancelled']++;
                        break;
                    case 'data-received':
                        $result['reports']++;
                        break;
                    case 'mark-as-priority':
                        break;
                    case 'remove-priority':
                        break;
                    case 'annual-exam':

                        break;
                    case 'refer-to-specialist':
                    case 'highrisk-contact-pcp':
                    default:
                        break;
                }
                if($contact_history->actionResult) {
                    switch ($contact_history->actionResult->name) {
                        case 'mark-as-priority':
                            break;
                        case 'already-seen-by-outside-dr':
                        case 'patient-declined-services':
                        case 'other-reasons-for-declining':
                        case 'no-need-to-schedule':
                        case 'no-insurance':
                            $history['archived']++;
                            $result['dropout']++;
                            $history['reached'][$history['repeat_count']]++;
                            break;
                        case 'unable-to-reach':
                            $history['not_reached'][$history['repeat_count']]++;
                            break;
                        case 'hold-for-future':
                            $history['reached'][$history['repeat_count']]++;
                            break;
                        default:
                            break;
                    }
                }
                if($contact_history->previous_stage) {
                    switch ($contact_history->previousStage->name) {
                        case 'contact-status':

                            break;
                        case 'scheduled-for-appointment':
                            break;
                        case 'missed-appointment':
                            break;
                        case 'exam-report':
                            break;
                        case 'finalization':
                            break;
                    }
                }
            }
            $result['repeat_count'] += $history['repeat_count'];
            for($i = 0; $i <= $history['repeat_count']; $i++ ) {
                if($history['reached'][$i] != 0)
                    $result['reached']++;
                else if($history['reached'][$i] == 0 && $history['not_reached'][$i] != 0)
                    $result['not_reached']++;
                else
                    $result['pending_patient']++;
            }
        }

        $result['patient_count'] += $result['repeat_count'];
        $result['completed'] = $result['success'] + $result['dropout'];
        $result['active_patient'] = $result['patient_count'] - $result['completed'];
        $result['contact_attempted'] = $result['reached'] + $result['not_reached'];
        $result['not_scheduled'] = $result['reached'] - $result['appointment_scheduled'];
        $result['no_show'] += $result['cancelled'];
        $result['no_reports'] = $result['show'] - $result['reports'];
        $stages = CareconsoleStage::all();
        foreach ($stages as $stage) {
            $result[$stage->name] = abs(ContactHistory::getAverageDaysInStage($stage->id, $this->getStartDate(), $this->getEndDate()));
        }

        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setStartDate($startDate)
    {
        $date = new Datetime($startDate);
        $this->startDate = $date->format('Y-m-d 00:00:00');
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate($endDate)
    {
        $date = new Datetime($endDate);
        $date->modify("+1 days");
        $this->endDate = $date->format('Y-m-d 00:00:00');
    }

    public function getEndDate()
    {
        return $this->endDate;
    }
}
