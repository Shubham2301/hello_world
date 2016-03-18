<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller {
	public function testMail() {
		$data = [];
		return view('emails.appt-confirmation-patient')->with('data', $data);
		$user = Auth::user();

		$result = Mail::send('emails.appt-confirmation-patient', ['user' => $user], function ($m) use ($user) {
			$m->from('support@ocuhub.com', 'Ocuhub');
			$m->to('kd@coloredcow.in', 'KD')->subject('Your Appointment has been scheduled');
		});
		dd($result);
	}
}
