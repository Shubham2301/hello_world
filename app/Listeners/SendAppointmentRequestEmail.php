<?php

namespace myocuhub\Listeners;

use Auth;
use DateTime;
use Event;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Log;
use MyCCDA;
use Storage;
use myocuhub\Events\AppointmentScheduled;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Facades\SES;
use myocuhub\Jobs\PatientEngagement\ConfirmAppointmentPatientMail;
use myocuhub\Models\PatientFile;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;

class SendAppointmentRequestEmail
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
		$appt = [];

		$practice = Practice::find($appointment->practice_id);
		$loggedInUser = Auth::user();
		$network = User::getNetwork($loggedInUser->id);
		$patient = Patient::find($appointment->patient_id);
		$provider = User::find($appointment->provider_id);
		$location = PracticeLocation::find($appointment->location_id);
		$patientInsurance = PatientInsurance::where('patient_id', $appointment->patient_id)->first();
		$appointmentType = $request->input('appointment_type');
		$appointmentTypeKey = $request->input('appointment_type_key');
		$apptStartdate = new DateTime($appointment->start_datetime);
		$patientDob = new DateTime($patient->birthdate);

		$sendCCDA = false;
		if ($request->has('send_ccda_file') && $request->send_ccda_file === 'true') {
			$sendCCDA = true;
		}

		$appt = [
			'user_name' => $loggedInUser->name ?: '',
			'user_network' => $network->name ?: '',
			'user_email' => $loggedInUser->email ?: '',
			'user_phone' => $loggedInUser->cellphone ?: '',
			'appt_type' => $appointmentType ?: '',
			'provider_name' => $provider->title.' '.$provider->firstname.' '.$provider->lastname,
			'location_name' => $location->locationname ?: '',
			'location_address' => ($location->addressline1 ?: '') . ', ' . ($location->addressline2 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: ''),
			'practice_name' => $practice->name  ?: '',
			'practice_phone' => $location->phone  ?: '',
			'appt_startdate' => $apptStartdate->format('F d, Y'),
			'appt_starttime' => $apptStartdate->format('h i A'),
			'patient_id' => $patient->id  ?: '',
			'patient_name' => $patient->title.' '.$patient->firstname.' '.$patient->lastname,
			'patient_email' => $patient->email  ?: '',
			'patient_phone' => $patient->cellphone . ', ' . $patient->workphone . ', ' . $patient->homephone,
			'patient_ssn' => $patient->lastfourssn ?: '',
			'patient_address' => ($patient->addressline1 ? $patient->addressline1 . ', ': '')  . ($patient->addressline2 ? $patient->addressline2 . ', ': '') . ($patient->city ? $patient->city . ', ': '') . ($patient->state ? $patient->state . ', ': '') . ($patient->zip ? $patient->zip : ''),
			'patient_dob' => ($patient->birthdate && $patient->birthdate != '0000-00-00 00:00:00') ? $patientDob->format('F d, Y') : '',
			'insurance_carrier' => '',
			'subscriber_name' => '',
			'subscriber_id' => '',
			'subscriber_birthdate' => '',
			'insurance_group_no' => '',
			'subscriber_relation' => '',
			'send_ccda' => $sendCCDA,
			'selectedfiles' => $request->selectedfiles
		];

		if ($patientInsurance != null) {
			$subscriberDob = new DateTime($patientInsurance->subscriber_birthdate);
			$appt['insurance_carrier'] =  $patientInsurance->insurance_carrier ?: '';
			$appt['subscriber_name'] = $patientInsurance->subscriber_name ?: '';
			$appt['subscriber_id'] = $patientInsurance->subscriber_id ?: '';
			$appt['subscriber_birthdate'] = ($patientInsurance->subscriber_birthdate && $patientInsurance->subscriber_birthdate != '0000-00-00 00:00:00')? $subscriberDob->format('F d, Y') : '';
			$appt['insurance_group_no'] = $patientInsurance->insurance_group_no ?: '';
			$appt['subscriber_relation'] = $patientInsurance->subscriber_relation ?: '';
		}

		$event->_setProviderEmailStatus($this->sendProviderMail($appt, $location));
	}

	public function engagePatient($patient, $appointment){
		$preference = EngagementPreference::where('patient_id', $patient->id)->get();
		switch ($preference['type']) {
			case config('patient_engagement.type.sms'):
                dispatch((new ConfirmAppointmentPatientSMS($patient, $appointment))->onQueue('sms'));
                break;
            case config('patient_engagement.type.phone'):
                break;
            case config('patient_engagement.type.email'):
            default:
                dispatch((new ConfirmAppointmentPatientMail($patient, $appointment))->onQueue('email'));
                break;
		}
		dispatch(new ConfirmAppointmentPatientMail($patient, $appointment));
	}

	public function sendProviderMail($appt, $location)
	{

		if ($location->email == null || $location->email == '') {
			return false;
		}

		$attr = [
			'from' => [
				'name' => config('constants.support.email_name'),
				'email' => config('constants.support.email_id'),
			],
			'to' => [
				'name' => $location->name,
				'email' => $location->email,
			],
			'subject' => config('constants.message_views.request_appointment_provider.subject'),
			'body' =>'',
			'view' => config('constants.message_views.request_appointment_provider.view'),
			'appt' => $appt,
			'attachments' => [],
		];

		/**
         * Add Check for SES Email here.
         */
		if (SES::isDirectID($location->email)) {
			/**
             * Generate CCDA file and send email via SES to Provider
             */
			try {
				$patientID = $attr['appt']['patient_id'];
				if ($appt['send_ccda']) {
					$attr['attachments'] = $this->getSelectedFiles($attr['appt']['selectedfiles'],  $patientID);
				}
				return  SES::send($attr);
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
		} else {
			/**
             * Send email via regular mail.
             */

			try {
				$mailToProvider = Mail::send($attr['view'], ['appt' => $attr['appt']], function ($m) use ($attr) {
					$m->from($attr['from']['email'], $attr['from']['name']);
					$m->to($attr['to']['email'], $attr['to']['name'])->subject($attr['subject']);
				});
			} catch (Exception $e) {
				Log::error($e);
				$action = 'Application Exception in sending Appointment Request email not sent to patient '. $location->email;
				$description = '';
				$filename = basename(__FILE__);
				$ip = '';
				Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

				return false;
			}
		}
		/**
         * If Practice Location has regular/ Non SES Email then send mail via Mandrill.
         */

		return true;
	}

	public function getSelectedFiles($fileString, $patientID)
	{
		$data = explode( ',', $fileString );
		$data = array_unique($data);
		$paths = [];

		if (in_array("CCDA", $data)) {
			$paths[] = MyCCDA::generateXml($patientID, true) ?: '';
			$key = array_search('CCDA', $data);
			array_splice($data, $key, 1);
		}

		for($i=0; $i<sizeOf($data); $i++)
		{
			try{
				$file = PatientFile::find($data[$i]);
				$tempFileName =config('constants.paths.ccda.temp_ccda'). $file->display_name . '.' . $file->extension;

				$fileContent =Storage::get($file->treepath . '/' . $file->name . '.' . $file->extension);

				$myfile = fopen($tempFileName, "w");
				$ss = fwrite($myfile, $fileContent);
				$paths[] = $tempFileName;
			}catch (Exception $e) {
				Log::error($e);
				$action = 'Application Exception in fetching files for  patient ID '. $patientID;
				$description = '';
				$filename = basename(__FILE__);
				$ip = '';
				Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
			}
		}
		return $paths;
	}
}
