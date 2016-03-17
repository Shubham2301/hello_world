<?php

namespace myocuhub\Services\PatientCare;

use Datetime;
use myocuhub\Models\Appointment;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;
use SoapClient;

class WriteBack4PC extends PatientCare {

	public function __construct() {
		self::$url = 'https://www.4patientcare.net/4pcdn/writeback4pc.asmx';
		self::$wsdl = 'https://www.4patientcare.net/4pcdn/writeback4pc.asmx?WSDL';
		self::$ProviderApptScheduleAction = 'http://writeback4pc.4PatientCare.net/OcuHub_ApptSchedule';
		self::$host = 'www.4patientcare.net';
	}

	public static function ProviderApptSchedule($input) {

		$input['AccessID'] = self::getAccessID();
		$input['SecurityCode'] = self::getSecurityCode();

		$client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
		$response = $client->__soapCall("OcuHub_ApptSchedule", array($input), array('soapaction' => self::$ProviderApptScheduleAction, 'uri' => self::$host));

		return $response;
	}

	public function OcuhubAppointmentWriteback($schedules) {

		foreach ($schedules as $schedule) {

			$fpcAappts = $schedule['schedule'];
			$provider = User::where('npi', $schedule['npi']);

			foreach ($fpcAappts as $fpcAappts) {
				$appt = Appointment::where('fpc_id', $fpcAppt['FPCApptID']);
				$practiceLocation = PracticeLocation::where('location_code', $fpcAppt['LocK']);

				if (!$appt) {

					/**
					 * Appointment was not scheduled by Ocuhub
					 */

					$appt = new Appointment;
					$appt->practice_id = $practiceLocation->practice_id;
					$appt->location_id = $practiceLocation->id;
					$appt->provider_id = $provider->id;
					$appt->appointmenttype = $fpcAppt['ApptReason'];
					$appt->fpc_id = $fpcAppt['FPCApptID'];
					$date = new Datetime($fpcAppt['ApptStart']);
					$appt->start_datetime = $date->format('Y-m-d H:m:s');
					$date = new Datetime($fpcAppt['ApptEnd']);
					$appt->end_datetime = $date->format('Y-m-d H:m:s');

					$patient = Patient::where('fpc_id', $fpcAppt['PatientData']['FPCPatientID']);
					if ($patient) {

						/**
						 * Patient Exists in Ocuhub
						 */

						$appt->patient_id = $patient->id;
					} else {

						/**
						 * Patient Does Not Exist in Ocuhub
						 */

						$patient = new Patient;
						$patient->fpc_id = $fpcAppt['PatientData']['FPCPatientID'];
						$patient->title = $fpcAppt['PatientData']['Title'];
						$patient->firstname = $fpcAppt['PatientData']['FirstName'];
						$patient->lastname = $fpcAppt['PatientData']['LastName'];
						$patient->homephone = $fpcAppt['PatientData']['Home'];
						$patient->workphone = $fpcAppt['PatientData']['Work'];
						$patient->cellphone = $fpcAppt['PatientData']['Cell'];
						$patient->email = $fpcAppt['PatientData']['Email'];
						$patient->addressline1 = $fpcAppt['PatientData']['Address1'];
						$patient->addressline2 = $fpcAppt['PatientData']['Address2'];
						$patient->city = $fpcAppt['PatientData']['City'];
						$patient->state = $fpcAppt['PatientData']['State'];
						$patient->zip = $fpcAppt['PatientData']['Zip'];
						$patient->lastfourssn = $fpcAppt['PatientData']['L4DSSN'];
						$date = new Datetime($fpcAppt['PatientData']['DOB']);
						$patient->birthdate = $date->format('Y-m-d H:m:s');
						$patient->preferredlanguage = $fpcAppt['PatientData']['PreferredLanguage'];

						$patient->save();

						if ($patient) {
							$appt->patient_id = $patient->id;
						}
					}

					$appt->patient_id = $patient->id;

					$appt->save();

				} else {

					/**
					 * Appointment was scheduled by Ocuhub
					 */

					$appt->practice_id = $practiceLocation->practice_id;
					$appt->location_id = $practiceLocation->id;
					$appt->provider_id = $provider->id;
					$appt->appointmenttype = $fpcAppt['ApptReason'];
					$appt->fpc_id = $fpcAppt['FPCApptID'];
					$date = new Datetime($fpcAppt['ApptStart']);
					$appt->start_datetime = $date->format('Y-m-d H:m:s');
					$date = new Datetime($fpcAppt['ApptEnd']);
					$appt->end_datetime = $date->format('Y-m-d H:m:s');

					$appt->save();

					$patient = Patient::where('id', $appt->patient_id);
					$patient->fpc_id = $fpcAppt['PatientData']['FPCPatientID'];

					$patient->save();

				}
			}
		}
	}

}
