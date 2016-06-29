<div style="width:100%;">
	<div style="width: 100%;display: inline-block;padding: 1%;font-family: arial, sans-serif;border: solid 1px transparent;border-radius: 2px;background-color: white;">
		
		<div>
			<div style="width: 100%;display: inline-block;text-align:left">
				<p>{{ $to['name'] }}</p>
			</div>
			<div style="width: 100%;display: inline-block;text-align:left">
				@yield('content')
			</div>
			<div style="width: 30%;display: inline-block;text-align:left">
				<br>
				<p>Support Staff, <br>Ocuhub LLC.</p>
				<img src="{{ config('constants.production_url').'/images/ocuhub-logo.png' }}" style="width:10em" alt="">
				<hr style="border: solid 1px #ddd;">
				<p><a href="{{ config('constants.support.contact_form') }}">{{ config('constants.support.contact_form') }}</a><br>Email - {{ config('constants.support.email_id') }}<br>Call - {{ config('constants.support.phone') }}</p>
			</div>
		</div>
	</div>
</div>
