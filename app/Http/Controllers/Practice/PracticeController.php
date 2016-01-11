<?php

namespace myocuhub\Http\Controllers\Practice;

use Illuminate\Http\Request;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\User;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class PracticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('practice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $practicedata = json_decode($request->input('data'),true);
        $practice = new Practice;
        $practice->name = $practicedata[0]['practice_name'];
        $practice->save();
        $practiceid = $practice->id;
         foreach($practicedata[0]['locations'] as $location){
             $practicelocation = new PracticeLocation;
             $practicelocation->locationname =$location['location_name'];
             $practicelocation->practice_id =$practiceid;
             $practicelocation->phone =$location['location_phone'];
             $practicelocation->addressline1 =$location['location_address1'];
             $practicelocation->addressline2 =$location['location_address2'];
             $practicelocation->city =$location['location_city'];
             $practicelocation->state =$location['location_state'];
             $practicelocation->zip =$location['location_zip'];
             $practicelocation->save();
        }
        return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = array();
        $practice_id            =$request->input('practice_id');
       // $practice_id            =1;
        $practice_name          =Practice::find($practice_id)->name;
        $practice_locations     =Practice::find($practice_id)->locations;
        $practice_users         =User::practiceUserById($practice_id);
        $data['practice_name']  = $practice_name;
        $data['practice_id']    = $practice_id;
        $data['locations']      = $practice_locations;
        $data['users']          = $practice_users;

        return json_encode($data);









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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request){
        $tosearchdata = json_decode($request->input('data'),true);
        $practices =Practice::where('name','like',$tosearchdata['value'])->get();
        $data = [];
        $i=0;
        foreach($practices as $practice){
            $data[$i]['id'] = $practice->id;
            $data[$i]['name'] = $practice->name;
            $data[$i]['email'] =  $practice->email;
            $data[$i]['address'] = 'asd123,gurgaon';
            $data[$i]['ocuapps'] = 'Calender Intregation';
            $i++;
        }
        return json_encode($data);


    }

}
