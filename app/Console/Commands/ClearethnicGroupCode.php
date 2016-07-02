<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use myocuhub\Models\Ccda;

class ClearethnicGroupCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'ethnicgroup {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
			$this->info('All Invalid records have been set to default');
		} else {
			$this->show();
		}
    }

	public function clear()
	{
		$ccdaData = Ccda::all();
		$data = array();
		$i = 0;
		foreach ($ccdaData as $ccda) {
			$jsonData = $ccda->ccdablob;
			$data = json_decode($jsonData, true);
			if($data['demographics']['ethnicity'] == 'Not Hispanic or Latino')
			{
				$data['demographics']['ethnicity'] = '';
			}
			$ccda->ccdablob = json_encode($data);
			$ccda->save();
		}

	}

	public function show()
	{
		$ccdaData = Ccda::all();
		$headers = ['ccdaID', 'PatientID'];
		$data = array();
		$i = 0;
		foreach ($ccdaData as $ccda) {
			$jsonData = $ccda->ccdablob;
			$dataAsJson = json_decode($jsonData, true);
			if ($dataAsJson ['demographics']['ethnicity'] == 'Not Hispanic or Latino') {
				$data[$i]['ccdaID'] = $ccda->id;
				$data[$i]['PatientID'] =  $ccda->patient_id;
				$i++;
			}

		}
		if (sizeof($data) > 0) {
			$this->table($headers, $data);
		} else {
			$this->info('No invalid records found');
		}
	}

}
