<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use myocuhub\Patient;

class PatientInvalidDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patientinvaliddates {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear all invalid birthdates for the patiens';

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
        if ($this->option('clear')) {
            $this->clear();
            $this->info('All Invalid patient birthdates has been set to default');
        } else {
            $this->show();
        }
    }


    public function clear()
    {
        $patients = Patient::all();
        $data = array();
        $i = 0;
        foreach ($patients as $patient) {
            if (!$this->validateDate($patient->birthdate)) {
                $patient->birthdate = '0000-00-00 00:00:00';
                $patient->save();
            }
        }
    }

    public function show()
    {
        $patients = Patient::all();
        $headers = ['id', 'Email', 'Birthdate'];
        $data = array();
        $i = 0;
        foreach ($patients as $patient) {
            if (!$this->validateDate($patient->birthdate)) {
                $data[$i]['id'] =  $patient->id;
                $data[$i]['Email'] =  $patient->email;
                $data[$i]['Birthdate'] = $patient->birthdate;
                $i++;
            }
        }
        if (sizeof($data) > 0) {
            $this->table($headers, $data);
        } else {
            $this->info('No invalid dates found');
        }
    }



    public function validateDate($date)
    {
        $defaultDate = '0000-00-00 00:00:00';
        if ($date === $defaultDate) {
            return true;
        }
        $timeStamp = strtotime($date);
        if (!$timeStamp) {
            return false;
        }
        $date = date('Y-m-d', $timeStamp);
        $test_arr  = explode('-', $date);
        $checked  =   checkdate($test_arr[1], $test_arr[2], $test_arr[0]);
        $last_date =  strtotime("1902-01-31");
        return  (strtotime($date) > $last_date) && $checked;
    }
}
