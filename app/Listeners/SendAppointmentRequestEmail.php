<?php

namespace myocuhub\Listeners;

use Auth;
use DateTime;
use Event;
use Exception;
use Log;
use MyCCDA;
use myocuhub\Events\AppointmentScheduled;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Events\Patient\CreateAttachmentFailure;
use myocuhub\Facades\SES;
use myocuhub\Http\Controllers\Traits\PatientRecords\PatientRecordsTrait;
use myocuhub\Jobs\PatientEngagement\ConfirmAppointmentPatientMail;
use myocuhub\Jobs\PatientEngagement\ConfirmAppointmentPatientSMS;
use myocuhub\Models\EngagementPreference;
use myocuhub\Models\PatientFile;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Patient;
use myocuhub\Services\MandrillService\MandrillService;
use myocuhub\User;
use Storage;

class SendAppointmentRequestEmail
{
    use PatientRecordsTrait;
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
        $network = $loggedInUser->userNetwork->first()->network;
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
            'provider_name' => $provider->title . ' ' . $provider->firstname . ' ' . $provider->lastname,
            'location_name' => $location->locationname ?: '',
            'location_address' => ($location->addressline1 ?: '') . ', ' . ($location->city ?: '') . ', ' . ($location->state ?: '') . ', ' . ($location->zip ?: ''),
            'practice_name' => $practice->name ?: '',
            'practice_phone' => $location->phone ?: '',
            'appt_startdate' => $apptStartdate->format('F d, Y'),
            'appt_starttime' => $apptStartdate->format('h i A'),
            'patient_id' => $patient->id ?: '',
            'patient_name' => $patient->title . ' ' . $patient->firstname . ' ' . $patient->lastname,
            'patient_email' => $patient->email ?: '',
            'patient_phone' => $patient->cellphone . ', ' . $patient->workphone . ', ' . $patient->homephone,
            'patient_ssn' => $patient->lastfourssn ?: '',
            'patient_address' => ($patient->addressline1 ? $patient->addressline1 . ', ' : '') . ($patient->addressline2 ? $patient->addressline2 . ', ' : '') . ($patient->city ? $patient->city . ', ' : '') . ($patient->state ? $patient->state . ', ' : '') . ($patient->zip ? $patient->zip : ''),
            'patient_dob' => ($patient->birthdate && $patient->birthdate != '0000-00-00 00:00:00') ? $patientDob->format('F d, Y') : '',
            'insurance_carrier' => '',
            'subscriber_name' => '',
            'subscriber_id' => '',
            'subscriber_birthdate' => '',
            'insurance_group_no' => '',
            'subscriber_relation' => '',
            'send_ccda' => $sendCCDA,
            'selectedfiles' => $request->selectedfiles,
            'patient_pcp' => $patient->pcp ?: '',
        ];

        if ($patientInsurance != null) {
            $subscriberDob = new DateTime($patientInsurance->subscriber_birthdate);
            $appt['insurance_carrier'] = $patientInsurance->insurance_carrier ?: '';
            $appt['subscriber_name'] = $patientInsurance->subscriber_name ?: '';
            $appt['subscriber_id'] = $patientInsurance->subscriber_id ?: '';
            $appt['subscriber_birthdate'] = ($patientInsurance->subscriber_birthdate && $patientInsurance->subscriber_birthdate != '0000-00-00 00:00:00') ? $subscriberDob->format('F d, Y') : '';
            $appt['insurance_group_no'] = $patientInsurance->insurance_group_no ?: '';
            $appt['subscriber_relation'] = $patientInsurance->subscriber_relation ?: '';
        }

        $event->_setProviderEmailStatus($this->sendProviderMail($appt, $location));

        $this->engagePatient($patient, $appointment);
    }

    public function engagePatient($patient, $appointment)
    {
        $preference = EngagementPreference::where('patient_id', $patient->id)->first();
        switch ($preference['type']) {
            case config('patient_engagement.type.sms'):
                dispatch((new ConfirmAppointmentPatientSMS($patient, $appointment))->onQueue('sms'));
                break;
            case config('patient_engagement.type.phone'):
                break;
            case config('patient_engagement.type.email'):
            default:
                dispatch(new ConfirmAppointmentPatientMail($patient, $appointment));
                break;
        }
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
                'name' => $location->locationname,
                'email' => $location->email,
            ],
            'subject' => config('constants.message_views.request_appointment_provider.subject') . ' at ' . $appt['practice_name'] . ' - ' . $appt['location_name'],
            'body' => '',
            'view' => config('constants.message_views.request_appointment_provider.view'),
            'appt' => $appt,
            'attachments' => [],
        ];

        if (SES::isDirectID($location->email)) {
            $loggedInUser = Auth::user();

            if (SES::isDirectID($loggedInUser->sesemail)) {
                $attr['from']['name'] = $loggedInUser->name;
                $attr['from']['email'] = $loggedInUser->sesemail;
            } else {
                $attr['from']['email'] = config('constants.support.ses.email.id');
                $attr['from']['name'] = config('constants.support.ses.email.display_name');
            }

            try {
                $patientID = $attr['appt']['patient_id'];
                if ($appt['send_ccda']) {
                    $attr['attachments'] = $this->createAttachments($attr['appt']['selectedfiles'], $patientID);
                }
                return SES::send($attr);
            } catch (Exception $e) {
                Log::error($e);
                return false;
            }
        } else {
            $mandrillService = new MandrillService;
            $template = $mandrillService->templateInfo('Email to provider');

            $vars = [];

            foreach ($attr['appt'] as $key => $value) {
                $vars[] = [
                    'name' => str_replace('_', '', strtoupper($key)),
                    'content' => $value,
                ];
            }

            if ($location->special_instructions) {
                $vars[] = [
                    'name' => 'SPECIALINSTRUCTIONS',
                    'content' => $location->special_instructions,
                ];
            }

            $loggedInUser = Auth::user();

            if ($loggedInUser->email) {
                $vars[] = [
                    'name' => 'USEREMAIL',
                    'content' => $loggedInUser->email,
                ];
            }

            if (Auth::check() && session('user-level') == 2) {
                $vars[] = [
                    'name' => 'NETWORKLOGO',
                    'content' => config('constants.production_url') . '/images/networks/network_' . $loggedInUser->userNetwork->first()->network_id . '.png',
                ];
            }

            $appt = [
                'from' => [
                    'name' => $attr['from']['name'],
                    'email' => $attr['from']['email'],
                ],
                'to' => [
                    'name' => $attr['to']['name'],
                    'email' => $attr['to']['email'],
                ],
                'subject' => $attr['subject'],
                'template' => $template['slug'],
                'vars' => $vars,
                'attachments' => []
            ];

            try {
                $response = $mandrillService->sendTemplate($appt);
            } catch (Exception $e) {
                Log::error($e);
                $action = 'Application Exception in sending Appointment Request email to practice : ' . $location->email;
                $description = '';
                $filename = basename(__FILE__);
                $ip = '';
                Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

                return false;
            }
        }

        return true;
    }

    public function createAttachments($fileString, $patientID)
    {
        $filearray = json_decode($fileString, true);

        $patientFiles = array_unique($filearray['patient_files']);

        $patientRecords = array_unique($filearray['patient_records']);

        $paths = [];

        if (in_array("CCDA", $patientFiles)) {
            $paths[] = MyCCDA::generateXml($patientID, true) ?: '';
            $key = array_search('CCDA', $patientFiles);
            array_splice($patientFiles, $key, 1);
        }

        for ($i = 0; $i < sizeOf($patientFiles); $i++) {
            try {
                $file = PatientFile::find($patientFiles[$i]);
                $tempFileName = config('constants.paths.ccda.temp_ccda') . $file->display_name . '.' . $file->extension;

                $fileContent = Storage::get($file->treepath . '/' . $file->name . '.' . $file->extension);

                $myfile = fopen($tempFileName, "w");
                $ss = fwrite($myfile, $fileContent);
                $paths[] = $tempFileName;
            } catch (Exception $e) {
                Log::error($e);

                event(new CreateAttachmentFailure(
                    [
                        'patientID' => $patientID,
                        'description' => $e->getMessage(),
                    ]

                ));
            }
        }

        for ($i = 0; $i < sizeOf($patientRecords); $i++) {
            try {
                $pdfObj = $this->createPDF($patientRecords[$i]);

                $PDFPaths = config('constants.paths.pdf');
                $tempFileName = $PDFPaths['temp_dir'] . 'record-' . $patientRecords[$i] . $PDFPaths['ext'];
                $pdfObj->save($tempFileName);
                $paths[] = $tempFileName;
            } catch (Exception $e) {
                Log::error($e);

                event(new CreateAttachmentFailure(
                    [
                        'patientID' => $patientID,
                        'description' => $e->getMessage(),
                    ]

                ));
            }
        }
        return $paths;
    }
}
