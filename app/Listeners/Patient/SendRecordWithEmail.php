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
use myocuhub\Models\WebFormTemplate;
use myocuhub\Patient;
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
        $currentUser = Auth::user();
        $PDFPaths = config('constants.paths.pdf');

        if(!SES::isDirectID($currentUser->sesemail))
        {
            return false;
        }

        try{

            $pdfObj = $this->createPDF($contactHistoryID);
            $fileName = $PDFPaths['temp_dir'].'record-'.$contactHistoryID.$PDFPaths['ext'];

            $path = [];
            $pdfObj->save($fileName);
            $path[] = $fileName;

            $attr = [
                'from' => [
                    'name' => config('constants.support.ses.email.display_name'),
                    'email' => config('constants.support.ses.email.id'),
                ],
                'to' => [
                    'name' =>  $currentUser->getName(),
                    'email' => $currentUser->sesemail,
                ],
                'subject' => "$templateName : $patientName",
                'body' =>"Attached is the $templateName for $patientName. Please forward this patient document as required.",
                'view' => config('constants.message_views.send_record_provider.view'),
                'attachments' => $path,
                'appt' => [],
            ];

            return SES::send($attr);

        }catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

}
