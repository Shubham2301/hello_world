<?php

namespace myocuhub\Http\Controllers\Admin;

use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Network;

class NetworkController extends Controller {

	public function __construct() {
		$this->middleware('role:network-admin');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
        //$roles = Role::all();
		//return view('admin.networks.index')->with('roles', $roles);
		if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        $data = array();
        $data['network_active'] = true;
		return view('admin.networks.index')->with('data', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $data = [];
		$data = Network::getColumnNames();
        $data['id']= -1;
        $data['url']='/administration/network/add';
        return view('admin.networks.create')->with('data', $data);
	}
	public function add(Request $request) {
		if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $network = new Network;
		$network->name = $request->input('name');
		$network->email = $request->input('email');
		$network->phone = $request->input('phone');
        $network->addressline1 = $request->input('addressline1');
        $network->addressline2 = $request->input('addressline2');
		$network->city = $request->input('city');
		$network->state = $request->input('state');
		$network->country = $request->input('country');
		$network->zip = $request->input('zip');
		$network->enable_console = 0;
		if($request->has('enable_console')){
			$network->enable_console =1;
		}
		$network->save();

		$action = 'New network created';
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
		return redirect('/administration/networks');
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

		$action = 'Store a network';
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $data = [];
        $data = Network::find($id);
        $data['id']=$id;
        $data['url']='/networks/update/'.$id;
        return view('admin.networks.create')->with('data', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $network = Network::find($id);
        $network->update($request->input());
		$network->enable_console =0;
		if($request->has('enable_console')){
			$network->enable_console =1;
		}
		$network->save();
		$action = 'update network of id =' . $id;
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        return redirect('/administration/networks');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
    public function destroy(Request $request, $id) {
    	if(!policy(new Network)->administration()){
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $network = Network::where('id', $id)->delete();
		$action = 'delete network of id =' . $id;
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
	}

	public function search(Request $request) {
        $tosearchdata = json_decode($request->input('data'), true);
		$networks = Network::where('name', 'like', '%' . $tosearchdata['value'] . '%')->get();
		$data = [];
		$i = 0;
		foreach ($networks as $network) {
			$data[$i]['id'] = $network->id;
			$data[$i]['name'] = $network->name;

            if($network->email !== null)
                $data[$i]['email'] = $network->email;
            else
                $data[$i]['email'] = '-';

            if($network->phone !== null)
                $data[$i]['phone'] = $network->phone;
            else
                $data[$i]['phone'] = '-';

            if($network->addressline1 !== null)
                $data[$i]['addressline1'] = $network->addressline1;
            else
                $data[$i]['addressline1'] = '-';

            if($network->addressline2 !== null)
                $data[$i]['addressline2'] = $network->addressline2;
            else
                $data[$i]['addressline2'] = '-';
			$i++;
		}
		return json_encode($data);
	}
}
