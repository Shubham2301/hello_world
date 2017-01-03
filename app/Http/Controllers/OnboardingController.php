<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use myocuhub\Jobs\Onboarding\SendOnboardingNotificationEmail;
use myocuhub\Models\OnboardPractice;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeNetwork;
use myocuhub\Network;
use myocuhub\User;

class OnboardingController extends Controller
{
    public function addLocation(Request $request)
    {
        $id = $request->input('id');
        $token = $request->input('token');
        $onboardPractice = OnboardPractice::find($id);
        $practiceID = $onboardPractice->practice_id;
        $data = array();
        $data['practice_active'] = true;
        $data['id'] = $practiceID;
        $data['location_index'] = -1;
        $data['edit'] = true;
        $practiceNetworks = PracticeNetwork::where('practice_id', $practiceID)->get();
        $data['network_id'] = [];
        $networkData = [];

        foreach ($practiceNetworks as $practiceNetwork) {
            $data['network_id'][] = $practiceNetwork->network_id;
            $networkData[$practiceNetwork->network_id] = $practiceNetwork->network->name;
        }

        $data['onboarding_id'] = $onboardPractice->id;
        $data['onboarding_token'] = $onboardPractice->token;

        return view('practice.create')->with('data', $data)->with('networks', $networkData);
    }

    public function showPracticeInfo(Request $request)
    {
        $data = array();
        $practice_id = $request->input('practice_id');
        $practice_name = Practice::find($practice_id)->name;
        $practice_email = Practice::find($practice_id)->email;
        $data['practice_name'] = $practice_name;
        $data['practice_email'] = $practice_email;
        $data['practice_id'] = $practice_id;
        $data['locations'] = [];
        $data['users'] = [];

        return json_encode($data);
    }

    public function storePracticeData(Request $request)
    {
        $practicedata = $request->input('data');
        $id = $request->input('id');
        $onboardPractice = OnboardPractice::find($id);
        $onboardPractice->practice_form_data = json_encode($practicedata[0]);
        $onboardPractice->save();

        $practice = Practice::find($onboardPractice->practice_id);
        $this->dispatch(new SendOnboardingNotificationEmail($practice));

        session(['onboard_success' => 'Thankyou for submitting your response!']);
        return json_encode(true);
    }
}
