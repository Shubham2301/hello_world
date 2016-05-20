<?php

namespace myocuhub\Listeners;

use Auth;
use Datetime;
use Event;
use Exception;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use myocuhub\Events\AppointmentScheduled;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Facades\WebScheduling4PC;
use myocuhub\Models\PatientInsurance;
use myocuhub\Patient;
use myocuhub\User;

class RequestFPCAppointment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentScheduled $event)
    {
        $request = $event->getRequest();
        $appointment = $event->getAppointment();

        try {
            $apptInfo = $this->prepareApptRequest($request);
        } catch (Exception $e) {
            Log::error($e);
            $event->_setFPCRequestStatus(false);
            return;
        }
        

        $apptResult = WebScheduling4PC::requestApptInsert($apptInfo);

        if ($apptResult != null) {
            if ($apptResult->RequestApptInsertResult->ApptKey != -1) {
                $appointment->setFPCID($apptResult->RequestApptInsertResult->ApptKey);
                

                $action = '4PC Appointment Request for Provider = ' . $apptInfo['AcctKey'] . ' Location = ' . $apptInfo['LocKey'] . ' on Date ' . $apptInfo['ApptStartDateTime'] . ' for Patient = ' . $apptInfo['PatientData']['FirstName'] . ' ' . $apptInfo['PatientData']['LastName'];
                $description = '';
                $filename = basename(__FILE__);
                $ip = $request->getClientIp();
                Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
                
                $event->_setFPCRequestStatus(true);
                return;
            } else {

                $action = 'Attempt to Request Appointment with 4PC failed for Provider = ' . $request->input('provider_id') . ' Location = ' . $request->input('location_id') . ' for Date ' . $request->input('appointment_time') . ' ';
                $description = $apptResult->RequestApptInsertResult->Result;
                $filename = basename(__FILE__);
                $ip = $request->getClientIp();
                Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            }
        }

        $event->_setFPCRequestStatus(false);
        return;
    }

    public function prepareApptRequest($request){

        $apptInfo = array();

        $userID = Auth::user()->id;
        $network = User::getNetwork($userID);
        $networkID = $network->id;
        $patientID = $request->input('patient_id');
        $providerID = $request->input('provider_id');
        $locationID = $request->input('location_id');
        $practiceID = $request->input('practice_id');
        $appointmentType = $request->input('appointment_type');
        $appointmentTypeKey = $request->input('appointment_type_key');
        $appointmentTime = $request->input('appointment_time');
        $patient = Patient::find($patientID);

        $providerKey = $request->input('provider_acc_key');
        $locationKey = $request->input('location_code');

        $apptInfo['LocKey'] = $locationKey;
        $apptInfo['AcctKey'] = $providerKey;
        $apptInfo['ApptTypeKey'] = $appointmentTypeKey;
        $startime = new DateTime($appointmentTime);
        $apptInfo['ApptStartDateTime'] = $startime->format('m/d/Y H:i');

        $apptInfo['PatientData']['Title'] = ($patient->title) ? $patient->title : '';
        $apptInfo['PatientData']['FirstName'] = ($patient->firstname) ? $patient->firstname : '';
        $apptInfo['PatientData']['LastName'] = ($patient->lastname) ? $patient->lastname : '';
        $apptInfo['PatientData']['Address1'] = ($patient->addressline1) ? $patient->addressline1 : '';
        $apptInfo['PatientData']['Address2'] = ($patient->addressline2) ? $patient->addressline2 : '';
        $apptInfo['PatientData']['City'] = ($patient->city) ? $patient->city : '';
        $apptInfo['PatientData']['State'] = ($patient->state) ? $patient->state : '';
        $apptInfo['PatientData']['Zip'] = ($patient->zip) ? $patient->zip : '';
        $apptInfo['PatientData']['Country'] = ($patient->country) ? $patient->country : '';
        $apptInfo['PatientData']['HomePhone'] = ($patient->homephone) ? $patient->homephone : '';
        $apptInfo['PatientData']['WorkPhone'] = ($patient->workphone) ? $patient->workphone : '';
        $apptInfo['PatientData']['CellPhone'] = ($patient->cellphone) ? $patient->cellphone : '';
        $apptInfo['PatientData']['Email'] = ($patient->email) ? $patient->email : '';

        $birthdate = new DateTime($patient->birthdate);
        $apptInfo['PatientData']['DOB'] = $birthdate->format('Y-m-d') . 'T00:00:00';
        $apptInfo['PatientData']['PreferredLanguage'] = ($patient->preferredlanguage != 'English') ? 1 : 0;
        $apptInfo['PatientData']['Gender'] = ($patient->gender == 'Male' || $patient->gender == 'M') ? 1 : 0;
        $apptInfo['PatientData']['L4DSSN'] = ($patient->lastfourssn) ? $patient->lastfourssn : '';
        $patientInsurance = PatientInsurance::where('patient_id', $patientID)->first();
        if (sizeof($patientInsurance) == 0) {
            $patientInsurance = new PatientInsurance;
            $apptInfo['PatientData']['InsuranceCarrier'] = 1;
        } else {
            if ($patientInsurance->insurance_carrier_fpc_key == null) {
                $apptInfo['PatientData']['InsuranceCarrier'] = 2;
            } else {
                $apptInfo['PatientData']['InsuranceCarrier'] = $patientInsurance->insurance_carrier_fpc_key;
            }

        }

        $apptInfo['PatientData']['OtherInsurance'] = ($patientInsurance->insurance_carrier) ? $patientInsurance->insurance_carrier : '';
        $apptInfo['PatientData']['SubscriberName'] = ($patientInsurance->subscriber_name) ? $patientInsurance->subscriber_name : '';
        $subscriber_birthdate = new DateTime(($patientInsurance->subscriber_birthdate) ? $patientInsurance->subscriber_birthdate : $patient->birthdate);
        $apptInfo['PatientData']['SubscriberDOB'] = $subscriber_birthdate->format('Y-m-d') . 'T00:00:00';
        $apptInfo['PatientData']['SubscriberID'] = ($patientInsurance->subscriber_id) ? $patientInsurance->subscriber_id : '';
        $apptInfo['PatientData']['GroupNum'] = ($patientInsurance->insurance_group_no) ? $patientInsurance->insurance_group_no : '';
        $apptInfo['PatientData']['RelationshipToPatient'] = ($patientInsurance->subscriber_relation) ? $patientInsurance->subscriber_relation : '';
        $apptInfo['PatientData']['CustomerServiceNumForInsCarrier'] = '';
        $apptInfo['PatientData']['ReferredBy'] = '';
        $apptInfo['PatientData']['NotesBox'] = '';
        $apptInfo['PatientData']['ReferredBy2'] = '';
        $apptInfo['PatientData']['ReferredBy3'] = '';
        $apptInfo['PatientData']['ReferredBy4'] = '';
        $apptInfo['PatientData']['ReferredBy5'] = '';
        $apptInfo['PatientData']['IsPatKnown'] = ($patient->fpc_id) ? '1' : '0';

        return $apptInfo;
    }
}
