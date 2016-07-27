<?php

namespace myocuhub\Listeners\Patient;

use myocuhub\Events\Patient\PatientRecordCreation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use SES;
use DateTime;
use Event;
use Log;
use Exception;
use myocuhub\Http\Controllers\Traits\PatientRecords\PatientRecordsTrait;
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
        $this->sendRecordToProvider($contactHistoryID);
    }


    private function sendRecordToProvider($contactHistoryID) {
        try{

            $currentUser = Auth::user();

            if(!SES::isDirectID($currentUser->sesemail))
            {
                return false;
            }

            $pdfObj = $this->createPDF($contactHistoryID);

            $PDFPaths = config('constants.paths.pdf');
            $fileName = $PDFPaths['temp_dir'].'record-'.$contactHistoryID.$PDFPaths['ext'];

            $path = [];
            $pdfObj->save($fileName);
            $path[] = $fileName;

            $attr = [
                'from' => [
                    'name' => config('constants.support.email_name'),
                    'email' => config('constants.support.email_id'),
                ],
                'to' => [
                    'name' =>  $currentUser->getName(),
                    'email' => $currentUser->sesemail,
                ],
                'subject' => config('constants.message_views.send_record_provider.subject'),
                'body' =>'',
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
