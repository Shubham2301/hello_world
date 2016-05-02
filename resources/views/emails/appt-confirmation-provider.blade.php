<div style="width:100%;">
	<div style="max-width:80%;width: 98%;display: inline-block;padding: 1%;font-family: arial, sans-serif;border: solid 1px transparent;border-radius: 2px;margin: 1% 10%;background-color: white;box-shadow: 0 0 5px #ddd;">
		<div>
			<div style="width: 50%;display: inline-block;margin-bottom:1em;">
				<img src="{{ url('images/ocuhub-logo.png') }}" style="width:10em" alt="">
			</div>
			<div style="width: 49%;display: inline-block;margin-bottom:1em;">
				<p style="display: inline-block;margin:0;font-weight: bold;float: right;font-size: 130%;colo:#333">{{ $appt['practice_name'] }}</p>
			</div>
		</div>
		<div>
			<div style="width: 90%;display: inline-block;margin:1em 5%;">
				<p style="font-size: 130%;color:#333">Hi {{ $appt['provider_name'] }}</p>
			</div>
		</div>
		<div>
			<div style="width: 90%;display: inline-block;margin:0 5%;text-align:center;">
				<p style="font-size: 120%;color:#4d4d4d">The following Appointment was requested with <br> <span style="font-weight:bold">{{ $appt['provider_name'] }}</span>. </p>
				<p style="font-size: 120%;color:#4d4d4d">Please enter this appointment  <br> into your practice management system. </p>
				<hr style="border: solid 1px #ddd;">
				<p style="font-size: 120%;color:#4d4d4d">This appointment request was generated by  <br> <span style="font-weight:bold">{{ $appt['user_name'] }}</span>, at <span style="font-weight:bold">{{ $appt['user_network'] }}</span> via the Ocuhub Platform. </p>
				<p style="font-size: 120%;color:#4d4d4d"> For questions about this appointment please call <br> <span style="font-weight:bold">{{ $appt['user_name'] }}</span> or email at <span style="font-weight:bold">{{ $appt['user_email'] }}</span>. </p>
				<br>
				<p style="font-size: 120%;color:#0071bc">{{ $appt['appt_type'] }}</p>
				<div style="display: inline-block;margin:0.5em 0;text-align:center;border-radius:2px;padding: 1em 2em;border:solid 2px #ddd">
					<p style="font-size: 120%;font-weight:bold;color:#333">PATIENT</p>
					<img src="{{ url('images/emails/email-provider-icon.png') }}" style="width:50%" alt="">
					<p style="color:#4d4d4d;">{{ $appt['patient_name'] }}</p>
				</div>
			</div>
		</div>
		<div>
			<div style="width: 90%;display: inline-block;margin:1em 5%;">
				<div style="width: 100%;display: block;margin-bottom:1em">
					<div style="position: absolute;color:#333;font-weight:bold;display:inline-block;width:20%;position: absolute;">
						<span>DETAILS</span>

					</div>
					<div style="color:#4d4d4d;display:inline-block;width:30%;border-left: solid 4px rgba(0, 113, 188, 0.4);padding-left: 1em;margin-left:20%;">
						 <span style="font-weight:bold">Email</span><br>
						{{ $appt['patient_email'] }} <br>
						<br>
						 <span style="font-weight:bold">Date Of Birth</span><br>
						{{ $appt['patient_dob'] }} <br>
						<br>
						<span style="font-weight:bold">Phone</span><br>
						{{ $appt['patient_phone'] }} <br>
						<br>
					</div>
					<div style="color:#4d4d4d;display:inline-block;width:30%;position: absolute;">
						<span style="font-weight:bold">Address</span><br>
						{{ $appt['patient_address'] }} <br>
						<br>
						<span style="font-weight:bold">SSN</span><br>
						{{ $appt['patient_ssn'] }} <br>
						<br>
					</div>
				</div>
				<div style="width: 100%;display: inline-block;">
					<div style="position: absolute;color:#333;font-weight:bold;display:inline-block;width:20%;">
						<span>WHEN</span>
					</div>
					<div style="color:#4d4d4d;display:inline-block;width:90%;border-left: solid 4px rgba(0, 113, 188, 0.4);padding-left: 1em;margin-left:20%;">
						{{ $appt['appt_startdate'] }} <br>
						{{ $appt['appt_starttime'] }}
					</div>
				</div>
				<div style="width: 100%;display: block;margin:1em 0;">
					<div style="position: absolute;color:#333;font-weight:bold;display:inline-block;width:20%;position: absolute;">
						<span>INSURANCE <br>DETAILS</span>

					</div>
					<div style="color:#4d4d4d;display:inline-block;width:30%;border-left: solid 4px rgba(0, 113, 188, 0.4);padding-left: 1em;margin-left:20%;">
						 <span style="font-weight:bold">Insurance Carrier</span><br>
						{{ $appt['insurance_carrier'] }} <br>
						<br>
						 <span style="font-weight:bold">Subscriber Name</span><br>
						{{ $appt['subscriber_name'] }} <br>
						<br>
						<span style="font-weight:bold">Group</span><br>
						{{ $appt['insurance_group_no'] }} <br>
						<br>
					</div>
					<div style="color:#4d4d4d;display:inline-block;width:30%;position: absolute;">
						<span style="font-weight:bold">Subscriber ID</span><br>
						{{ $appt['subscriber_id'] }} <br>
						<br>
						<span style="font-weight:bold">Subscriber DOB</span><br>
						{{ $appt['subscriber_birthdate'] }} <br>
						<br>
						<span style="font-weight:bold">Relationship to Patient</span><br>
						{{ $appt['subscriber_relation'] }} <br>
						<br>
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
				<p><a href="http://www.ocuhub.com/contact">www.ocuhub.com/contact</a><br>Email - support@ocuhub.com<br>Call - 844-605-8843</p>
			</div>
		</div>
	</div>
</div>
