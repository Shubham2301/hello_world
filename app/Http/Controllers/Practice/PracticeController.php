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
             $practicelocation->locationname =$location['locationname'];
             $practicelocation->practice_id =$practiceid;
             $practicelocation->phone =$location['phone'];
             $practicelocation->addressline1 =$location['addressline1'];
             $practicelocation->addressline2 =$location['addressline2'];
             $practicelocation->city =$location['city'];
             $practicelocation->state =$location['state'];
             $practicelocation->zip =$location['zip'];
             $practicelocation->save();
        }

        return json_encode($practiceid);
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
    public function edit(Request $request)
    {
        $practicedata   = json_decode($request->input('data'),true);
        $practicename   = $practicedata[0]['practice_name'];
        $practiceid     = $practicedata[0]['practice_id'];
        $locations      = $practicedata[0]['locations'];

        foreach($locations as $location){
            $practicelocation = '';
            if (array_key_exists('id', $location))
            $practicelocation = PracticeLocation::find($location['id']);
            else
                $practicelocation = new PracticeLocation;

             $practicelocation->locationname =$location['locationname'];
             $practicelocation->practice_id =$practiceid;
             $practicelocation->phone =$location['phone'];
             $practicelocation->addressline1 =$location['addressline1'];
             $practicelocation->addressline2 =$location['addressline2'];
             $practicelocation->city =$location['city'];
             $practicelocation->state =$location['state'];
             $practicelocation->zip =$location['zip'];
             $practicelocation->save();

        }

        return json_encode($practiceid);



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
