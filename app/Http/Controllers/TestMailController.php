<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller {
	public function testMail() {
		$appt = [];

		return view('emails.appt-confirmation-patient')->with('appt', $appt);
		$user = Auth::user();

		$mailToPatient = Mail::send('emails.appt-confirmation-patient', ['appt' => $appt], function ($m) use ($user) {
			$m->from('support@ocuhub.com', 'Ocuhub');
			$m->to('kd@coloredcow.in', 'Eric Hoell')->subject('Your Appointment has been scheduled');
		});
		$mailToProvider = Mail::send('emails.appt-confirmation-patient', ['appt' => $appt], function ($m) use ($user) {
			$m->from('support@ocuhub.com', 'Ocuhub');
			$m->to('kd@coloredcow.in', 'Eric Hoell')->subject('Your Appointment has been scheduled');
		});

		dd($result);
	}
}
