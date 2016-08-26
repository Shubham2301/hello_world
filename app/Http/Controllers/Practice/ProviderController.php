<?php

namespace myocuhub\Http\Controllers\Practice;

use DateTime;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Facades\WebScheduling4PC;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Http\Controllers\Traits\ValidateFPCRequestParams;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\ProviderType;
use myocuhub\Models\ReferralHistory;
use myocuhub\Patient;
use myocuhub\ReferralType;
use myocuhub\User;

class ProviderController extends Controller
{
    use ValidateFPCRequestParams;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $data = array();
        $data['admin'] = false;
        $data['schedule-patient'] = true;
        if ($request->has('referraltype_id')) {
            $data['referraltype_id'] = $request->input('referraltype_id');
        }
        if ($request->has('action')) {
            $data['action'] = $request->input('action');
        }
        if ($request->has('action_result_id')) {
            $data['action_result_id'] = $request->input('action_result_id');
        }
        if ($request->has('patient_id')) {
            $data['patient_id'] = $request->input('patient_id');
        }

        $patientInsurance = PatientInsurance::where('patient_id', $data['patient_id'])->first();

        if (sizeof($patientInsurance) > 0) {
            $insurance['insurance_carrier'] = $patientInsurance->insurance_carrier;
            $insurance['insurance_carrier_key'] = $patientInsurance->insurance_carrier_fpc_key;
            $insurance['subscriber_name'] = $patientInsurance->subscriber_name;
            $insurance['subscriber_id'] = $patientInsurance->subscriber_id;
            $dob = new DateTime($patientInsurance->subscriber_birthdate);
            $insurance['subscriber_birthdate'] = $dob->format('m/d/Y');
            $insurance['subscriber_relation'] = $patientInsurance->subscriber_relation;
            $insurance['insurance_group_no'] = $patientInsurance->insurance_group_no;
        } else {
            $insurance['insurance_carrier'] = '';
            $insurance['insurance_carrier_key'] = '';
            $insurance['subscriber_name'] = '';
            $insurance['subscriber_id'] = '';
            $insurance['subscriber_birthdate'] = '';
            $insurance['subscriber_relation'] = '';
            $insurance['insurance_group_no'] = '';
        }
        $data['previous_selected'] = $request->has('selected_previous_provider');

        $data['selectedfiles'] = $request->input('selectedfiles');

        return view('provider.index')->with('data', $data)->with('insurance', $insurance);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $provider_id = $request->input('provider_id');
        $practice_id = $request->input('practice_id');

        $provider = User::find($provider_id);
        $practice_name = Practice::find($practice_id)->name;
        $practice_locations = Practice::find($practice_id)->locations;

        $data['practice_name'] = $practice_name;
        $data['practice_id'] = $practice_id;
        $data['provider'] = $provider;
        $data['provider_type'] = ProviderType::getName($provider->provider_type_id);
        $data['locations'] = $practice_locations;
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

    public function search(Request $request)
    {
        $showSpecialist = false;
        $filters = json_decode($request->input('data'), true);
        if ($request->has('show_specialist')) {
            $showSpecialist = $request->show_specialist['show'];
            if ($showSpecialist == 'true') {
                $referraltypeID = $request->show_specialist['referraltype_id'];
                $referraltype = ReferralType::find($referraltypeID);
                $filters[] = [
                    'type' => 'specialty',
                    'value' => $referraltype->display_name,
                ];
            }
        }

        if ($request->has('provider_types')) {
            $providerTypes = $request->provider_types;
            $filters[] = [
                'type' => 'provider_types',
                'value' => $providerTypes,
            ];
        }

        $providers = User::providers($filters);
        $data = [];
        $i = 0;

        foreach ($providers as $provider) {
            if (!$provider->id || !$provider->user_id) {
                continue;
            }

            $data[$i]['id'] = $provider->user_id;
            $data[$i]['practice_id'] = $provider->id;
            $data[$i]['name'] = $provider->getName();
            $data[$i]['speciality'] = $provider->speciality ?: 'Unlisted';
            $data[$i]['provider_type'] = ProviderType::getName($provider->provider_type_id);
            $data[$i]['practice_name'] = $provider->name;
            $data[$i]['location_name'] = $provider->locationname;
            $i++;
        }

        return json_encode($data);
    }

    public function getAppointmentTypes(Request $request)
    {
        $providerInfo = array();
        $providerKey = $request->input('provider_id');
        $locationKey = $request->input('location_id');
        $providerInfo['LocKey'] = $locationKey;
        $providerInfo['AcctKey'] = $providerKey;
        $apptTypes = WebScheduling4PC::getApptTypes($providerInfo);

        if (!isset($apptTypes->GetApptTypesResult->ApptType)) {
            $action = 'No data received for Provider = ' . $providerKey . ' Location = ' . $locationKey;
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return json_encode($apptTypes);
        }

        $aptAsJson = json_encode($apptTypes);
        $aptAsArray = json_decode($aptAsJson, true);

        if (!array_key_exists('ApptType', $aptAsArray['GetApptTypesResult'])) {
            return $aptAsJson;
        }

        $checkaptFormat = array_key_exists(0, $aptAsArray['GetApptTypesResult']['ApptType']);

        if (!$checkaptFormat) {
            $data['ApptTypeName'] = $aptAsArray['GetApptTypesResult']['ApptType']['ApptTypeName'];
            $data['ApptTypeKey'] = $aptAsArray['GetApptTypesResult']['ApptType']['ApptTypeKey'];
            unset($aptAsArray['GetApptTypesResult']['ApptType']['ApptTypeKey']);
            unset($aptAsArray['GetApptTypesResult']['ApptType']['ApptTypeName']);
            $aptAsArray['GetApptTypesResult']['ApptType'][0] = $data;
            $aptAsJson = json_encode($aptAsArray);
        }

        return $aptAsJson;
    }

    public function getInsuranceList(Request $request)
    {
        $providerInfo = array();

        $providerKey = $request->input('provider_id');
        $locationKey = $request->input('location_id');

        $providerInfo['LocKey'] = $locationKey;
        $providerInfo['AcctKey'] = $providerKey;

        $insList = WebScheduling4PC::getInsList($providerInfo);

        return json_encode($insList);
    }

    public function getOpenSlots(Request $request)
    {
        $providerInfo = array();

        $providerKey = $request->input('provider_id');
        $locationKey = $request->input('location_id');
        $AppointmentType = $request->input('appointment_type');
        $week_advance = $request->input('week');
        $selected_date = $request->input('selected_date');

        $providerInfo['LocKey'] = $locationKey;
        $providerInfo['AcctKey'] = $providerKey;
        $providerInfo['ApptTypeKey'] = $AppointmentType;

        $dates = $this->getDatesOfWeek($week_advance, $selected_date);

        $slots = [];
        $i = 0;
        foreach ($dates as $date) {
            $slots[$i]['date'] = $date;
            $providerInfo['GetSlotsOnDate'] = $date;
            $slots[$i]['slots'] = WebScheduling4PC::getOpenApptSlots($providerInfo);
            $i++;
        }
        return json_encode($slots);
    }
    public function administration(Request $request)
    {
        $data = array();
        $data['admin'] = true;
        $data['provider_active'] = true;
        return view('provider.admin')->with('data', $data);
    }

    protected function getDatesOfWeek($week_advance, $selected_date)
    {

        //        $date = date("m/d/Y");
        $date = date("d-m-Y", strtotime($selected_date) + (86400 * $week_advance * 7));
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);
        //        $var = 7*$week_advance;
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $ts = strtotime($year . 'W' . $week . $i);
            $dates[] = date("m/d/Y", $ts);
        }
        return $dates;
    }

    public function getPreviousProviders(Request $request)
    {
        $patientID = $request->patient_id;
        $providers = Patient::getPreviousProvidersList($patientID);
        $data = [];
        $i = 0;
        foreach ($providers as $provider) {
            if (!$provider->provider_id || !$provider->practice_id) {
                continue;
            }
            $data[$i]['id'] = $provider->provider_id;
            $data[$i]['name'] = $provider->getName();
            $data[$i]['practice_id'] = $provider->practice_id;
            $data[$i]['practice_name'] = $provider->name;
            $data[$i]['speciality'] = $provider->speciality ?: 'Unlisted';
            $data[$i]['provider_type'] = ProviderType::getName($provider->provider_type_id);
            $data[$i]['location_name'] = $provider->locationname;
            $i++;
        }
        return json_encode($data);
    }

    public function getNearByProviders(Request $request)
    {
        $patientID = $request->patient_id;
        $patientLocation = Patient::find($patientID)->getLocation();

        if (isset($patientLocation['error'])) {
            return $patientLocation['error'];
        }

        $providerTypes = $request->has('provider_types') ? $request->provider_types : [];

        $lat = $patientLocation['latitude'];
        $lng = $patientLocation['longitude'];
        $providers = [];
        if ($lat != '') {
            $providers = User::getNearByProviders($lat, $lng, config('constants.providerNearPatient.providerRadius'), $providerTypes);
        }
        $data = [];
        $i = 0;
        foreach ($providers as $provider) {
            if ($i > config('constants.providerNearPatient.providerNumber')) {
                break;
            }
            if (!$provider->user_id || !$provider->practice_id) {
                continue;
            }
            $data[$i]['id'] = $provider->user_id;
            $data[$i]['name'] = $provider->getName();
            $data[$i]['practice_id'] = $provider->practice_id;
            $data[$i]['practice_name'] = $provider->name;
            $data[$i]['speciality'] = $provider->speciality ?: 'Unlisted';
            $data[$i]['location_name'] = $provider->locationname;
            $data[$i]['provider_type'] = ProviderType::getName($provider->provider_type_id);
            $data[$i]['distance'] = number_format((float) $provider->distance, 2, '.', '') . ' Miles';
            $i++;
        }
        return json_encode($data);
    }

    public function getReferringProviderSuggestions(Request $request)
    {
        $searchString = $request->provider;
        $providers = User::where('name', 'LIKE', '' . $searchString . '%')->where('usertype_id', 1);

        if (session('user-level') > 1) {
            $providers = $providers->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
                ->where('network_user.network_id', session('network-id'));
        }

        $providers = $providers->pluck('users.name')->toArray();
        $fromReferring = ReferralHistory::where('referred_by_provider', 'LIKE', '' . $searchString . '%');
        if (session('user-level') > 1) {
            $fromReferring = $fromReferring->where('network_id', session('network-id'));
        }
        $fromReferring = $fromReferring->pluck('referred_by_provider')->toArray();
        $suggestions = array_unique(array_merge($providers, $fromReferring));
        $data = [];
        $i = 0;
        foreach ($suggestions as $key => $value) {
            $data[$i] = $value;
            $i++;
        }
        return json_encode($data);
    }
}
