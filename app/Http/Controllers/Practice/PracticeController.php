<?php

namespace myocuhub\Http\Controllers\Practice;

use Auth;
use Event;
use Exception;
use Illuminate\Http\Request;
use Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Jobs\Onboarding\SendPracticeOnboardingEmail;
use myocuhub\Models\NetworkUser;
use myocuhub\Models\OnboardPractice;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeLocation;
use myocuhub\Models\PracticeNetwork;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\ReferralHistory;
use myocuhub\Network;
use myocuhub\User;

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
        $data['network_id'] = [];
        $networkData = [];
        if (session('network-id')) {
            $data['network_id'][] = session('network-id');
            $networkData[session('network-id')] = Network::find(session('network-id'))->name;
        } else {
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
            $practicelocation->city = $location['city'];
            $practicelocation->state = $location['state'];
            $practicelocation->zip = $location['zip'];
            $practicelocation->location_code = $location['location_code'];
            if ($location['special_instructions'] != '') {
                $practicelocation->special_instructions = $location['special_instructions'];
                $practicelocation->special_instructions_plain_text = $location['special_instructions_plain_text'];
            } else {
                $practicelocation->special_instructions = null;
                $practicelocation->special_instructions_plain_text = null;
            }

            $address = urlencode($practicelocation->addressline1.' '.$practicelocation->city.' '.$practicelocation->zip.' '.$practicelocation->state);

            try {
                $json = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.env('MAP_API_KEY')), true);
            } catch (Exception $e) {
                Log::error($e);
            }
            if (isset($json['results'][0]['geometry']['location']['lat'])) {
                $practicelocation->latitude = $json['results'][0]['geometry']['location']['lat'];
                $practicelocation->longitude = $json['results'][0]['geometry']['location']['lng'];
            }

            $practicelocation->save();
        }
        if (isset($practicedata[0]['practice_network'])) {
            foreach ($practicedata[0]['practice_network'] as $network) {
                $practiceNetwork = new PracticeNetwork;
                $practiceNetwork->practice_id = $practice->id;
                $practiceNetwork->network_id = $network;
                $practiceNetwork->save();
            }
        } else {
            session()->flash('warning', 'Practice added without network information! Please contact occuhub support.');
        }

        if ($practicedata[0]['onboard_practice']) {
            $onboard_practice = new OnboardPractice;
            $onboard_practice->practice_id = $practiceid;
            $onboard_practice->token = str_random(50);
            $onboard_practice->save();

            $url = url('/') . '/onboarding?id=' . $onboard_practice->id . '&token=' . $onboard_practice->token;
            $messsage = 'Please use the following link to access form to add locations to your practice '.$url;

            $this->dispatch(new SendPracticeOnboardingEmail($practice, $messsage));
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
        $practice_id = $request->input('practice_id');
        $onboardPractice = OnboardPractice::where('practice_id', $practice_id)->first();
        if ($onboardPractice && $onboardPractice->practice_form_data) {
            $data = json_decode($onboardPractice->practice_form_data);
        } else {
            $data = array();
            $practice_name = Practice::find($practice_id)->name;
            $practice_email = Practice::find($practice_id)->email;
            $practice_locations = Practice::find($practice_id)->locations;
            $practice_users = User::practiceUserById($practice_id);
            $data['practice_name'] = $practice_name;
            $data['practice_email'] = $practice_email;
            $data['practice_id'] = $practice_id;
            $data['locations'] = $practice_locations;
            $data['users'] = $practice_users;
        }

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
        $onboardPractice = OnboardPractice::where('practice_id', $id)->first();
        if ($onboardPractice && $onboardPractice->practice_form_data) {
            $data['onboard'] = true;
        }

        $practiceNetworks = PracticeNetwork::where('practice_id', $id)->get();
        $data['network_id'] = [];
        $networkData = [];

        foreach ($practiceNetworks as $practiceNetwork) {
            $data['network_id'][] = $practiceNetwork->network_id;
        }

        if (session('network-id')) {
            foreach ($practiceNetworks as $practiceNetwork) {
                $networkData[$practiceNetwork->network_id] = $practiceNetwork->network->name;
            }
        } else {
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
        $removedLocations = $practicedata[0]['removed_location'];
        $onboardPractice = OnboardPractice::where('practice_id', $practiceid)->first();
        if ($practicedata[0]['discard_onboard']) {
            $onboardPractice->delete();
            return json_encode($practiceid);
        }

        $practice = Practice::find($practiceid);
        $practice->name = $practicename;
        $practice->email = $practiceemail;
        $practice->save();

        if (isset($practicedata[0]['practice_network'])) {
            foreach ($practicedata[0]['practice_network'] as $network) {
                $practiceNetwork = PracticeNetwork::firstOrCreate(['practice_id' => $practice->id, 'network_id' => $network]);
            }
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
            $practicelocation->city = $location['city'];
            $practicelocation->state = $location['state'];
            $practicelocation->zip = $location['zip'];
            $practicelocation->location_code = $location['location_code'];
            if ($location['special_instructions'] != '') {
                $practicelocation->special_instructions = $location['special_instructions'];
                $practicelocation->special_instructions_plain_text = $location['special_instructions_plain_text'];
            } else {
                $practicelocation->special_instructions = null;
                $practicelocation->special_instructions_plain_text = null;
            }
            $practicelocation->save();

            $address = urlencode($practicelocation->addressline1.' '.$practicelocation->city.' '.$practicelocation->zip.' '.$practicelocation->state);

            try {
                $json = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.env('MAP_API_KEY')), true);
            } catch (Exception $e) {
                Log::error($e);
            }
            if (isset($json['results'][0]['geometry']['location']['lat'])) {
                $practicelocation->latitude = $json['results'][0]['geometry']['location']['lat'];
                $practicelocation->longitude = $json['results'][0]['geometry']['location']['lng'];
                $practicelocation->save();
            }
        }

        foreach ($removedLocations as $location) {
            if (isset($location['id'])) {
                $practicelocation = PracticeLocation::find($location['id']);
                $practicelocation->delete();
            }
        }

        if ($onboardPractice) {
            $onboardPractice->delete();
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
        $onboardPractice = OnboardPractice::where('practice_id', $practiceid)->first();
        if ($onboardPractice) {
            $onboardPractice->delete();
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

        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reactivate($practiceID, Request $request)
    {
        if (!policy(new Practice)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

        $reactivate = Practice::where('id', $practiceID)->restore();
        $practicelocation = PracticeLocation::where('practice_id', $practiceID)->restore();

        $action = 'reactivated practice with ID'. $practiceID;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
    }


    public function search(Request $request)
    {
        $tosearchdata = json_decode($request->input('data'), true);

        if ($tosearchdata['include_deactivated']) {
            if (Auth::user()->isSuperAdmin()) {
                $practices = Practice::withTrashed()->where('name', 'like', '%' . $tosearchdata['value'] . '%')->get();
            } elseif (Auth::user()->checkUserLevel(config('constants.user_levels.network'))) {
                $practices = Network::practicesByName($tosearchdata['value'], $tosearchdata['include_deactivated']);
            } else {
                $userID = Auth::user()->id;
                $practices = Practice::getPracticeByUserID($userID, $tosearchdata['include_deactivated']);
            }
        } else {
            if (Auth::user()->isSuperAdmin()) {
                $practices = Practice::where('name', 'like', '%' . $tosearchdata['value'] . '%')->get();
            } elseif (Auth::user()->checkUserLevel(config('constants.user_levels.network'))) {
                $practices = Network::practicesByName($tosearchdata['value']);
            } else {
                $userID = Auth::user()->id;
                $practices = Practice::getPracticeByUserID($userID);
            }
        }

        $data = [];
        $i = 0;
        foreach ($practices as $practice) {
            $data[$i]['id'] = $practice->id;
            $data[$i]['name'] = $practice->name;
            $data[$i]['email'] = ($practice->email) ? $practice->email : '-';
            $data[$i]['address'] = '';
            $data[$i]['locations'] = PracticeLocation::where('practice_id', $practice->id)->get();
            $data[$i]['deleted'] = ($practice->deleted_at) ? 'true' : 'false';
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
        $networkId = $request->network_id;
        $practiceUsers = User::practiceUserById($practiceId, $networkId);
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

    public function getPracticesByNetwork(Request $request)
    {
        $networkPractices = Practice::getPracticeByNetwork($request->input('networks'));

        $practices = [];
        foreach ($networkPractices as $practice) {
            $practices[$practice->id] = $practice->name;
        }

        return json_encode($practices);
    }
}
