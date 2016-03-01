<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Network;
use myocuhub\Patient;
use myocuhub\User;

class BulkImportController extends Controller {

	public function index() {
		$networks = Network::all()->lists('name', 'id');
		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$data['id'] = $network->network_id;
		$data['name'] = $network->name;
		$super_admin =User::find(5)->hasRole('administrator');
		$data['super_admin'] = $super_admin;
		if($super_admin)
			$data['networks'] = Network::all();


		return view('layouts.import')->with('network', $data);
	}

	public function getLocations(Request $request) {

		$practice_id = $request->input('practice_id');
		$locations = PracticeLocation::where('practice_id', $practice_id)->get();
		$data = [];
		$i = 0;
		foreach ($locations as $location) {
			$data[$i]['id'] = $location->id;
			$data[$i]['name'] = $location->locationname;
			$i++;
		}

		return json_encode($data);
	}

	public function importPatientsXlsx(Request $request) {
//		$userID = Auth::user()->id;
//		$network = User::getNetwork($userID);
		$networkID = $request->network_id;

		if ($request->hasFile('patient_xlsx')) {
			$i = 0;
			$excels = Excel::load($request->file('patient_xlsx'))->get();
			$importHistory = new ImportHistory;
			$importHistory->network_id = $networkID;
			$importHistory->save();
			foreach ($excels as $sheet) {
				$title = $sheet->getTitle();
				switch ($title) {
					case 'Users':
						foreach ($sheet as $data) {
							$users = [];
							if (array_filter($data->toArray())) {
								$users['name'] = $data['name'];
								$users['email'] = $data['contact_email'];
								$users['cellphone'] = $data['phone_number'];
								$users['cellphone'] = $data['cell_number'];
								//npi cannot be null
								$users['npi'] = '1234'; 	//$data['npi'];
								$users['state'] = $data['state'];
								$users['address1'] = $data['address_1'];
								$users['address2'] = $data['address_2'];
								$users['city'] = $data['city'];
								$users['zip'] = $data['zip_code'];
								$user = User::firstOrCreate($users);
								//map user with organization
							}
						}
						break;
					case 'Accounts':
						foreach ($sheet as $data) {
							$practices = [];
							$locations = [];
							if (array_filter($data->toArray())) {
								$practices['name'] = $data['practice_name'];
								//$practices['email']           = '';
								$practice = Practice::firstOrCreate($practices);
								$locations['practice_id'] = $practice->id;
								$locations['locationname'] = $data['location_name'];
								$locations['phone'] = $data['phone_number'];
								$locations['addressline1'] = $data['address_1'];
								$locations['addressline2'] = $data['address_2'];
								$locations['city'] = $data['city'];
								$locations['state'] = $data['state'];
								$locations['zip'] = $data['zip'];
								$location = PracticeLocation::firstOrCreate($locations);
							}

						}
						break;
					case 'Patients':

						foreach ($sheet as $data) {
							$patients = [];
							if (array_filter($data->toArray())) {
								$name = explode(' ', $data['patient_name']);
								$patients['title'] = 'Mr';
								$patients['firstname'] = $name[0];
								$patients['lastname'] = $name[1];
								$patients['cellphone'] = $data['phone_number'];
								$patients['email'] = $data['email'];
								$patients['addressline1'] = $data['address_1'];
								$patients['addressline2'] = $data['address_2'];
								$patients['city'] = $data['city'];
								$patients['zip'] = $data['zip'];
								$patients['lastfourssn'] = $data['ssn_last_digits'];
								$patients['birthdate'] = $data['birthdate'];
								$patients['gender'] = $data['gender'];
								//$patients['insurancecarrier'] = $data['insurance_type'];
								$patient = Patient::firstOrCreate($patients);
								$careconsole = new Careconsole;
								$careconsole->import_id = $importHistory->id;
								$careconsole->patient_id = $patient->id;
								$careconsole->stage_id = 1;
								$careconsole->stage_id = $data['priority'];
								$date = new \DateTime();
								$careconsole->stage_updated_at = $date->format('Y-m-d H:m:s');
								$careconsole->save();
								$i++;
							}
						}
						break;
				}
			}

			$action = 'Bulk import Patients';
			$description = '';
			$filename = basename(__FILE__);
			$ip = $request->getClientIp();
			Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
			return "You have imported " . $i . " patients";
		}
		return "try again";
	}
}
