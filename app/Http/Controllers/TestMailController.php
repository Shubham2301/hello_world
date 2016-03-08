<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller {
	public function testMail() {
		$user = Auth::user();

		$result = Mail::send('emails.test', ['user' => $user], function ($m) use ($user) {
			$m->from('support@ocuhub.com', 'Ocuhub');
			$m->to('kd@coloredcow.in', 'KD')->subject('Test Application');
		});
		dd($result);
	}
}
