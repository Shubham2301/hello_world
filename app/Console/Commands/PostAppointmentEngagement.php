<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use myocuhub\Models\Appointment;
use myocuhub\Patient;


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
        if($appointments){
            foreach ($appointments as $appt) {
                $patient = Patient::find($appt['patient_id']);
                $type = $appt['patient_preference'];
                $stage = config('patient_engagement.stage.post_appointment');
                if($patient && policy($patient)->engage($patient, $type, $stage)) {
                    $patient->engagePatient($appt);
                }
            }
        } 
    }

}
