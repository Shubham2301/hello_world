<?php

namespace myocuhub\Http\Controllers\Practice;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\User;

class PracticeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		//return view('practice.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$id = -1;
		$data = array();
		$data['practice_active'] = true;
		$data['id'] = $id;
		$data['location_index'] = -1;
		return view('practice.create')->with('data', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$practicedata = json_decode($request->input('data'), true);
		$practice = new Practice;
		$practice->name = $practicedata[0]['practice_name'];
		$practice->email = $practicedata[0]['practice_email'];
		$practice->save();
		$practiceid = $practice->id;
		foreach ($practicedata[0]['locations'] as $location) {
			$practicelocation = new PracticeLocation;
			$practicelocation->locationname = $location['locationname'];
			$practicelocation->practice_id = $practiceid;
			$practicelocation->phone = $location['phone'];
			$practicelocation->addressline1 = $location['addressline1'];
			$practicelocation->addressline2 = $location['addressline2'];
			$practicelocation->city = $location['city'];
			$practicelocation->state = $location['state'];
			$practicelocation->zip = $location['zip'];
			$practicelocation->location_code = $location['location_code'];
			$practicelocation->save();
		}
		$action = 'new practice created';
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
		return json_encode($practiceid);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request) {
		$data = array();
		$practice_id = $request->input('practice_id');
		$practice_name = Practice::find($practice_id)->name;
		$practice_email = Practice::find($practice_id)->email;
		$practice_locations = Practice::find($practice_id)->locations;
		$practice_users = User::practiceUserById($practice_id);
		$data['practice_name'] = $practice_name;
		$data['practice_email'] = $practice_email;
		$data['practice_id'] = $practice_id;
		$data['locations'] = $practice_locations;
		$data['users'] = $practice_users;

		return json_encode($data);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, $location) {
		$data = array();
		$data['practice_active'] = true;
		$data['id'] = $id;
		$data['location_index'] = $location;
		return view('practice.create')->with('data', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request) {
		$practicedata = json_decode($request->input('data'), true);
		$practicename = $practicedata[0]['practice_name'];
		$practiceemail = $practicedata[0]['practice_email'];
		$practiceid = $practicedata[0]['practice_id'];
		$locations = $practicedata[0]['locations'];
		$practice = Practice::find($practiceid);
		$practice->name = $practicename;
		$practice->email = $practiceemail;
		$practice->save();
		$practicelocation = PracticeLocation::where('practice_id', $practiceid)->delete();
		foreach ($locations as $location) {
			$practicelocation = new PracticeLocation;
			$practicelocation->locationname = $location['locationname'];
			$practicelocation->practice_id = $practiceid;
			$practicelocation->phone = $location['phone'];
			$practicelocation->addressline1 = $location['addressline1'];
			$practicelocation->addressline2 = $location['addressline2'];
			$practicelocation->city = $location['city'];
			$practicelocation->state = $location['state'];
			$practicelocation->zip = $location['zip'];
			$practicelocation->location_code = $location['location_code'];
			$practicelocation->save();

		}

		$action = 'Edit practice';
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return json_encode($practiceid);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request) {
		$practice_id = $request->input('practice_id');
		$practicelocation = PracticeLocation::where('practice_id', $practice_id)->delete();
		$practices = Practice::where('id', $practice_id)->delete();

		$action = 'deleted practice';
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

	}

	public function search(Request $request) {
		$tosearchdata = json_decode($request->input('data'), true);
		$practices = Practice::where('name', 'like', '%' . $tosearchdata['value'] . '%')->paginate(5);
		$data = [];
		$data[0]['total'] = $practices->total();
		$data[0]['lastpage'] = $practices->lastPage();
		$data[0]['currentPage'] = $practices->currentPage();
		$i = 0;
		foreach ($practices as $practice) {
			$data[$i]['id'] = $practice->id;
			$data[$i]['name'] = $practice->name;
			$data[$i]['email'] = $practice->email;
			$data[$i]['address'] = '4885 Olde Towne Parkway, Marietta, GA 30076';
			$data[$i]['ocuapps'] = 'Calender Intregation';
			$i++;
		}
		return json_encode($data);
	}

	public function administration(Request $request) {
		$data = array();
		$data['practice_active'] = true;
		return view('practice.admin')->with('data', $data);
	}

	public function removelocation(Request $request) {
		$data = PracticeLocation::find($request->location_id)->delete();

		return json_encode($data);
	}
	public function practiceUsers(Request $request) {
		$practiceId = $request->id;
		$practiceUsers = User::practiceUserById($practiceId);
		$i = 0;

		foreach ($practiceUsers as $user) {
			$users[$i]['id'] = $user->id;
			$users[$i]['name'] = $user->lastname . ', ' . $user->firstname;
			$i++;
		}

		return json_encode($users);
	}
}
