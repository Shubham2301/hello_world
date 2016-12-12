<?php

namespace myocuhub\Services\PatientCare;

use Datetime;
use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\FPCWritebackAudit;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\User;

class WriteBack4PC extends PatientCare
{

    public function __construct()
    {
        self::$url = 'https://www.4patientcare.net/4pcdn/writeback4pc.asmx';
        self::$wsdl = 'https://www.4patientcare.net/4pcdn/writeback4pc.asmx?WSDL';
        self::$ProviderApptScheduleAction = 'http://writeback4pc.4PatientCare.net/OcuHub_ApptSchedule';
        self::$host = 'www.4patientcare.net';
    }

    /**
     *
     * ProviderApptSchedule() provides a list of scheduled appointments for a provider.
     * These appointments may or may not have been scheduled by Ocuhub.
     *
     * This function was written as a part of a batch process that runs every midnight. However, its use can be extend otherwise.
     *
     * @param $input
     * @return SOAP response
     */
    public static function ProviderApptSchedule($input)
    {

        $input['AccessID'] = self::getAccessID();
        $input['SecurityCode'] = self::getSecurityCode();

        $client = new SoapClient(self::$wsdl, array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $response = $client->__soapCall("OcuHub_ApptSchedule", array($input), array('soapaction' => self::$ProviderApptScheduleAction, 'uri' => self::$host));

        return $response;
    }

    /**
     *
     * OcuhubAppointmentWriteback() takes input as the list of appointment schedules provided by 4PC
     * for a specific provider based on their NPI numbers.
     *
     * This method updated the Ocuhub Database with the 4PC database.
     * It performs two responsibilties
     * - Appointments that were scheduled by Ocuhub should be updated with latest relevant information provided by 4PC.
     * - Appointment that were scheduled outside Ocuhub can be brought into the Ocuhub system.
     *
     * With the intention that Ocuhub users can manage all their 4pC appointments from a single interface.
     *
     * This function was written as a part of a batch process that runs every midnight. However, its use can be extend otherwise.
     *
     * @param $schedules
     */
    public function OcuhubAppointmentWriteback($schedules)
    {

        foreach ($schedules as $schedule) {

            $fpcAappts = $schedule['schedule'];
            $provider = User::where('npi', $schedule['npi'])->first();

            foreach ($fpcAappts as $fpcAppt) {
                try {
                    if(!is_array($fpcAappts)) {
                        $fpcAppt = $fpcAappts;
                    }
                    $appt = Appointment::where('fpc_id', $fpcAppt->FPCApptID)->get();
                    $practiceLocation = PracticeLocation::where('location_code', $fpcAppt->LocK)->first();


                    if (!sizeof($appt)) {

                        /**
                         * Appointment was not scheduled by Ocuhub
                         */

                        $appt = new Appointment;
                        $appt->practice_id = $practiceLocation->practice_id;
                        $appt->location_id = $practiceLocation->id;
                        $appt->provider_id = $provider->id;
                        $appt->appointmenttype = $fpcAppt->ApptReason;
                        $appt->fpc_id = $fpcAppt->FPCApptID;
                        $date = new Datetime($fpcAppt->ApptStart);
                        $appt->start_datetime = $date->format('Y-m-d H:i:s');
                        $date = new Datetime($fpcAppt->ApptEnd);
                        $appt->end_datetime = $date->format('Y-m-d H:i:s');
                        $patient = Patient::where('fpc_id', $fpcAppt->PatientData->FPCPatientID)->first();
                        if ($patient && $patient->careConsole) {

                            /**
                             * Patient Exists in Ocuhub
                             */

                            $appt->patient_id = $patient->id;
                            $appt->network_id = $patient->careConsole->importHistory->network_id;
                            $appt->save();

                        } else {

                            $patients = [];
                            $patients['firstname'] = $fpcAppt->PatientData->FirstName;
                            $patients['lastname'] = $fpcAppt->PatientData->LastName;
                            $patients['lastfourssn'] = $fpcAppt->PatientData->L4DSSN;
                            $date = new Datetime($fpcAppt->PatientData->DOB);
                            $patients['birthdate'] = $date->format('Y-m-d H:i:s');
                            $patient = Patient::where($patients)->first();

                            if ($patient && $patient->careConsole) {

                                /**
                                 * Patient Exists in Ocuhub but not linked to FPC ID
                                 */

                                $patient->fpc_id = $fpcAppt->PatientData->FPCPatientID;
                                $patient->save();

                                $appt->patient_id = $patient->id;
                                $appt->network_id = $patient->careConsole->importHistory->network_id;
                                $appt->save();
                            }
                        }

                        $newAppoitment = Appointment::where('fpc_id', $fpcAppt->FPCApptID)->first();
                        if ($newAppoitment) {
                            $audit = new FPCWritebackAudit;
                            $audit->patient_id = $newAppoitment->patient_id;
                            $audit->provider_id = $newAppoitment->provider_id;
                            $audit->appointment_id = $newAppoitment->id;
                            $audit->save();
                        }


                    } else {

                        /**
                         * Appointment was scheduled by Ocuhub
                         */

                        $appt = Appointment::find($appt[0]['id']);

                        if ($appt->enable_writeback) {
                            $appt->practice_id = $practiceLocation->practice_id;
                            $appt->location_id = $practiceLocation->id;
                            $appt->provider_id = $provider->id;
                            $appt->appointmenttype = $fpcAppt->ApptReason;
                            $appt->fpc_id = $fpcAppt->FPCApptID;
                            $date = new Datetime($fpcAppt->ApptStart);
                            $appt->start_datetime = $date->format('Y-m-d H:i:s');
                            $date = new Datetime($fpcAppt->ApptEnd);
                            $appt->end_datetime = $date->format('Y-m-d H:i:s');

                            $appt->save();

                            $patient = Patient::find($appt->patient_id);
                            $patient->fpc_id = $fpcAppt->PatientData->FPCPatientID;

                            $patient->save();

                            $audit = new FPCWritebackAudit;
                            $audit->patient_id = $appt->patient_id;
                            $audit->provider_id = $appt->provider_id;
                            $audit->appointment_id = $appt->id;

                            $audit->save();
                        }

                    }
                } catch (Exception $e) {
                    Log::error($e);
                }
            }
        }
    }

}
