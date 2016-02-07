<?php

namespace myocuhub\Http\Controllers\Admin;

use Illuminate\Http\Request;
use myocuhub\Network;
use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $roles = Role::all();
//        return view('admin.networks.index')->with('roles', $roles);
        return view('admin.networks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.networks.create');
    }
    public function add(Request $request)
    {
        $network = new Network;
        $network->name = $request->input('name');
        $network->email = $request->input('email');
        $network->phone = $request->input('phone');
        $network->addressline1 = $request->input('address_1');
        $network->addressline2 = $request->input('address_2');
        $network->city = $request->input('city');
        $network->state = $request->input('state');
        $network->country = $request->input('country');
        $network->zip = $request->input('zip');
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
    public function store(Request $request)
    {
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $action = 'update network of id ='.$id;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();

        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = 'delete network of id ='.$id;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();

        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
    }

    public function search(Request $request){
        $tosearchdata = json_decode($request->input('data'),true);
        $networks = Network::where('name','like','%'.$tosearchdata['value'].'%')->paginate(5);
        $data = [];
        $data[0]['total'] = $networks->total();
        $data[0]['lastpage']=$networks->lastPage();
        $data[0]['currentPage']=$networks->currentPage();
        $i=0;
        foreach($networks as $network){
            $data[$i]['id'] = $network->id;
            $data[$i]['name'] = $network->name;
            $data[$i]['email'] =  $network->email;
            $data[$i]['phone'] =  $network->phone;
            $data[$i]['addressline1'] =  $network->addressline1;
            $data[$i]['addressline2'] =  $network->addressline2;
            $i++;
        }
        return json_encode($data);
    }
}
