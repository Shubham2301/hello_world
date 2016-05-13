<div style="width:100%;">
	<div style="max-width:80%;width: 98%;display: inline-block;padding: 1%;font-family: arial, sans-serif;border: solid 1px transparent;border-radius: 2px;margin: 1% 10%;background-color: white;box-shadow: 0 0 5px #ddd;">
		<div>
			<div style="width: 50%;display: inline-block;margin-bottom:1em;">
				@if( Auth::check()  && session('user-level') == 2 )
            	<img src="{{ url('images/networks/network_'. Auth::user()->getNetwork(Auth::user()->id)->id .'.png')}}" style="width:10em" alt="">
            	@endif
			</div>
			<div style="width: 49%;display: inline-block;margin-bottom:1em;">
				<p style="display: inline-block;margin:0;font-weight: bold;float: right;font-size: 130%;colo:#333">{{ $appt['practice_name'] }}</p>
			</div>
		</div>
		<div>
			<div style="width: 90%;display: inline-block;margin:1em 5%;">
				<p style="font-size: 130%;color:#333">Hi {{ $appt['patient_name'] }}</p>
			</div>
		</div>
		<div>
			<div style="width: 90%;display: inline-block;margin:0 5%;text-align:center;">
				<p style="font-size: 120%;color:#4d4d4d">Your appointment has been scheduled.</p>
				<p style="font-size: 120%;color:#0071bc">{{ $appt['appt_type'] }}</p>
				<hr style="border: solid 1px #ddd;">
				<p style="font-size: 120%;color:#4d4d4d">This appointment request was generated by  <br> <span style="font-weight:bold">{{ $appt['user_name'] }}</span>, at <span style="font-weight:bold">{{ $appt['user_network'] }}</span> via the Ocuhub Platform. </p>
				<p style="font-size: 120%;color:#4d4d4d"> To cancel or reschedule this appointment please call <br> <span style="font-weight:bold">{{ $appt['user_name'] }}</span> or email at <span style="font-weight:bold">{{ $appt['user_email'] }}</span>. </p>
				<div style="display: inline-block;margin:0.5em 0;text-align:center;border-radius:2px;padding: 1em 2em;border:solid 2px #ddd">
					<p style="font-size: 120%;font-weight:bold;color:#333">PROVIDER</p>
					<img src="{{ url('images/emails/email-provider-icon.png') }}" style="width:50%" alt="">
					<p style="color:#4d4d4d;">{{ $appt['provider_name'] }}</p>
				</div>
			</div>
		</div>
		<div>
			<div style="width: 90%;display: inline-block;margin:1em 5%;">
				<div style="width: 60%;display: inline-block;">
					<div style="position: absolute;color:#333;font-weight:bold;display:inline-block;width:30%;position: absolute;">
						<span>WHERE</span>
					</div>
					<div style="color:#4d4d4d;display:inline-block;width:60%;border-left: solid 4px rgba(0, 113, 188, 0.4);padding-left: 1em;margin-left:30%;">
						<span style="font-weight:bold">{{ $appt['location_name'] }}</span><br>
						{{ $appt['location_address'] }} <br>
						{{ $appt['practice_phone'] }} <br>
					</div>
				</div>
				<div style="width: 39%;display: inline-block;position: absolute;float:right">
					<div style="position: absolute;color:#333;font-weight:bold;display:inline-block;width:30%;">
						<span>WHEN</span>
					</div>
					<div style="color:#4d4d4d;display:inline-block;width:60%;border-left: solid 4px rgba(0, 113, 188, 0.4);padding-left: 1em;margin-left:30%;">
						{{ $appt['appt_startdate'] }} <br>
						{{ $appt['appt_starttime'] }}
					</div>
				</div>
			</div>
		</div>
		<div>
			<div style="width: 35%;display: inline-block;margin-bottom:1em;">

			</div>
			<div style="width: 30%;display: inline-block;margin:3em 0;text-align:center">
				<img src="{{ url('images/ocuhub-logo.png') }}" style="width:10em" alt="">
				<hr style="border: solid 1px #ddd;">
				<p><a href="{{ config('constants.support.contact_form') }}">{{ config('constants.support.contact_form') }}</a><br>Email - {{ config('constants.support.email_id') }}<br>Call - {{ config('constants.support.phone') }}</p>
			</div>
		</div>
	</div>
</div>
