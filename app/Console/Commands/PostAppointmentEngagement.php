<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use myocuhub\Jobs\PostAppointmentPatientMail;
use myocuhub\Jobs\PostAppointmentPatientPhone;
use myocuhub\Models\Appointment;


class PostAppointmentEngagement extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pee:post-appt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $appointments = Appointment::pastAppointmentsForEngagement();
        foreach ($appointments as $appt) {
            if($this->authorizeEngagement(Patient::find($appt['patient_id'])))
                $this->engagePatient($appt);
            }
        }
    }

    public function engagePatient($appt){
        switch ($appt['patient_preference']) {
            case config('patient_engagement.type.sms'):
                dispatch((new PostAppointmentPatientSms($appt))->onQueue('sms'));
                break;
            case config('patient_engagement.type.phone'):
                dispatch((new PostAppointmentPatientPhone($appt))->onQueue('phone'));
                break;
            case config('patient_engagement.type.email'):
            default:
                dispatch((new PostAppointmentPatientMail($appt))->onQueue('email'));
                break;
        }
    }

    public function authorizeEngagement($patient){
        $policy = new EngagePatientPolicy($patient)->authorize();
        $stage = config('patient_engagement.stage.post_appointment');
        return $policy->authorize($appt['patient_preference'], $stage);
    }

}
