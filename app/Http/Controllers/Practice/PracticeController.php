<?php

namespace myocuhub\Http\Controllers\Practice;

use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Models\PracticeNetwork;
use myocuhub\Models\ReferralHistory;
use myocuhub\Network;
use myocuhub\User;
use Auth;

class PracticeController extends Controller
{

    public function __construct()
    {
        //$this->middleware('role:practice-admin,1,Administrator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return view('practice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $id = -1;
        $data = array();
        $data['practice_active'] = true;
        $data['id'] = $id;
        $data['location_index'] = -1;
        $data['network_id'] = session('network-id');
        $networkData = [];
        if(session('network-id')) {
            $networkId = session('network-id');
            $networkData[$networkId] = Network::find($networkId)->name;
        }
        else {
            $networks = Network::all()->sortBy("name");
            foreach ($networks as $network) {
                $networkData[$network->id] = $network->name;
            }
        }


        return view('practice.create')->with('data', $data)->with('networks', $networkData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

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
            $practicelocation->email = $location['email'];
            $practicelocation->addressline1 = $location['addressline1'];
            $practicelocation->addressline2 = $location['addressline2'];
            $practicelocation->city = $location['city'];
            $practicelocation->state = $location['state'];
            $practicelocation->zip = $location['zip'];
            $practicelocation->location_code = $location['location_code'];

            $address = urlencode($practicelocation->addressline1.' '.$practicelocation->addressline1.' '.$practicelocation->city.' '.$practicelocation->zip.' '.$practicelocation->state);

            try {
                $json = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.env('MAP_API_KEY')), true);
            } catch (Exception $e) {
            }
            if (isset($json['results'][0]['geometry']['location']['lat'])) {
                $practicelocation->latitude = $json['results'][0]['geometry']['location']['lat'];
                $practicelocation->longitude = $json['results'][0]['geometry']['location']['lng'];
            }

            $practicelocation->save();
        }
        if(isset($practicedata[0]['practice_network'])) {
            $practiceNetwork = new PracticeNetwork;
            $practiceNetwork->practice_id = $practice->id;
            $practiceNetwork->network_id = $practicedata[0]['practice_network'];
            $practiceNetwork->save();
        } else {
            session()->flash('warning', 'Practice added without network information! Please contact occuhub support.');
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
    public function show(Request $request)
    {
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
    public function edit($id, $location)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $data = array();
        $data['practice_active'] = true;
        $data['id'] = $id;
        $data['location_index'] = $location;
        $data['edit'] = true;
        $network = PracticeNetwork::where('practice_id', $id)->first();
        $data['network_id'] = $network ? $network->network_id :  null ;
        $networkData = [];
        if($network) {
            $networks = Network::find($network->network_id);
            $networkData[$networks->id] = $networks->name;
        }
        else {
            $networks = Network::all()->sortBy("name");
            foreach ($networks as $network) {
                $networkData[$network->id] = $network->name;
            }
        }
        return view('practice.create')->with('data', $data)->with('networks', $networkData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $practicedata = json_decode($request->input('data'), true);
        $practicename = $practicedata[0]['practice_name'];
        $practiceemail = $practicedata[0]['practice_email'];
        $practiceid = $practicedata[0]['practice_id'];
        $locations = $practicedata[0]['locations'];
        $practice = Practice::find($practiceid);
        $practice->name = $practicename;
        $practice->email = $practiceemail;
        $practice->save();

        $practiceNetwork = PracticeNetwork::where('practice_id', $practiceid)->first();
        if(!$practiceNetwork) {
            $practiceNetwork = new PracticeNetwork;
            $practiceNetwork->practice_id = $practiceid;
            $practiceNetwork->network_id = $practicedata[0]['practice_network'];
            $practiceNetwork->save();
        }

        foreach ($locations as $location) {
            if (isset($location['id'])) {
                $practicelocation = PracticeLocation::find($location['id']);
            } else {
                $practicelocation = new PracticeLocation;
            }
            
            $practicelocation->locationname = $location['locationname'];
            $practicelocation->practice_id = $practiceid;
            $practicelocation->phone = $location['phone'];
            $practicelocation->email = $location['email'];
            $practicelocation->addressline1 = $location['addressline1'];
            $practicelocation->addressline2 = $location['addressline2'];
            $practicelocation->city = $location['city'];
            $practicelocation->state = $location['state'];
            $practicelocation->zip = $location['zip'];
            $practicelocation->location_code = $location['location_code'];
            $practicelocation->save();

            $address = urlencode($practicelocation->addressline1.' '.$practicelocation->addressline1.' '.$practicelocation->city.' '.$practicelocation->zip.' '.$practicelocation->state);

            try {
                $json = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.env('MAP_API_KEY')), true);
            } catch (Exception $e) {
                Log::error($e);
            }
            if (isset($json['results'][0]['geometry']['location']['lat'])) {
                $practicelocation->latitude = $json['results'][0]['geometry']['location']['lat'];
                $practicelocation->longitude = $json['results'][0]['geometry']['location']['lng'];
            }
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
    public function destroy(Request $request)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $i = 0;
        while (1) {
            if ($request->input($i)) {
                $practice_id = $request->input($i);
                $practicelocation = PracticeLocation::where('practice_id', $practice_id)->delete();
                $practices = Practice::where('id', $practice_id)->delete();
                $i++;
            } else {
                break;
            }
        }

        $action = 'deleted practice';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
    }

    public function search(Request $request)
    {
        $tosearchdata = json_decode($request->input('data'), true);

        if (Auth::user()->isSuperAdmin()) {
            $practices = Practice::where('name', 'like', '%' . $tosearchdata['value'] . '%')->get();
        } elseif (Auth::user()->checkUserLevel(config('constants.user_levels.network'))) {
            $practices = Network::practicesByName($tosearchdata['value']);
        } else {
            $userID = Auth::user()->id;
            $practices = Practice::getPracticeByUserID($userID);
        }
        $data = [];
        $i = 0;
        foreach ($practices as $practice) {
            $data[$i]['id'] = $practice->id;
            $data[$i]['name'] = $practice->name;
            $data[$i]['email'] = ($practice->email) ? $practice->email : '-';
            $data[$i]['address'] = '';
            $data[$i]['locations'] = PracticeLocation::where('practice_id', $practice->id)->get();
            $i++;
        }
        return json_encode($data);
    }

    public function administration(Request $request)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        $data = array();
        $data['practice_active'] = true;
        return view('practice.admin')->with('data', $data);
    }

    public function removelocation(Request $request)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $data = PracticeLocation::find($request->location_id)->delete();

        return json_encode($data);
    }

    public function practiceUsers(Request $request)
    {
        $practiceId = $request->id;
        $practiceUsers = User::practiceUserById($practiceId);
        $i = 0;
        $users = [];
        foreach ($practiceUsers as $user) {
            $users[$i]['id'] = $user->user_id;
            $users[$i]['name'] = $user->lastname . ', ' . $user->firstname;
            $i++;
        }

        return json_encode($users);
    }

    public function getReferringPracticeSuggestions(Request $request)
    {
        $searchString = $request->practice;
        $practices = Practice::where('name', 'LIKE', ''.$searchString.'%');

        if (session('user-level') > 1) {
            $practices = $practices->leftjoin('practice_network', 'practices.id', '=', 'practice_network.practice_id')
                ->where('practice_network.network_id', session('network-id'));
        }

        $practices = $practices->pluck('practices.name')->toArray();

        $fromReferring = ReferralHistory::where('referred_by_practice', 'LIKE', ''.$searchString.'%');
        if (session('user-level') > 1) {
            $fromReferring = $fromReferring->where('network_id', session('network-id'));
        }
        $fromReferring = $fromReferring->pluck('referred_by_practice')->toArray();
        $data = [];
        $i = 0;
        $suggestions = array_unique(array_merge($practices, $fromReferring));
        foreach ($suggestions as $key => $value) {
            $data[$i]= $value;
            $i++;
        }
        return json_encode($data);
    }

    public function getPracticesByNetwork($networkID)
    {
        $networkPractices = Network::find($networkID)->practices()->get(['practices.id', 'practices.name']);
        $practices = [];
        foreach ($networkPractices as $practice) {
            $practices[$practice->id] = $practice->name;
        }

        return json_encode($practices);
    }
}
