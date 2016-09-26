<?php

namespace myocuhub\Listeners\Patient;

use Auth;
use DateTime;
use Event;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use SES;
use myocuhub\Events\Patient\PatientRecordCreation;
use myocuhub\Http\Controllers\Traits\PatientRecords\PatientRecordsTrait;
use myocuhub\Models\WebFormNotification;
use myocuhub\Models\WebFormTemplate;
use myocuhub\Patient;
use myocuhub\User;
class SendRecordWithEmail
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
     * @param  PatientRecordCreation  $event
     * @return void
     */
    public function handle(PatientRecordCreation $event)
    {

        $contactHistoryID = $event->getContactHistoryID();
        $patientName = Patient::find($event->getPatientID())->getName();
        $templateName = WebFormTemplate::find($event->getTemplateID())->name;

        $PDFPaths = config('constants.paths.pdf');
        $path = [];

        $notificationUsers = array();
        $notificationUsers[] = Auth::user();
        $networkNotificationUser = WebFormNotification::where([
            'network_id' => session('network-id'),
            'web_form_template_id' => $event->getTemplateID()
            ])->first();

        if($networkNotificationUser) {
            $notificationUsers[] = User::find($networkNotificationUser->user_id);
        }

        foreach ($notificationUsers as $user) {
            if(!SES::isDirectID($user->sesemail))
            {
                continue;
            }

            try{
                $attr = [
                    'from' => [
                        'name' => config('constants.support.ses.email.display_name'),
                        'email' => config('constants.support.ses.email.id'),
                    ],
                    'to' => [
                        'name' =>  $user->getName(),
                        'email' => $user->sesemail,
                    ],
                    'subject' => "$templateName : $patientName",
                    'body' =>"Attached is the $templateName for $patientName. Please forward this patient document as required.",
                    'view' => config('constants.message_views.send_record_provider.view'),
                    'attachments' => $path,
                    'appt' => [],
                ];

                SES::send($attr);

            }catch (Exception $e) {
                Log::error($e);
                continue;
            }
        }
        return true;
    }

}
