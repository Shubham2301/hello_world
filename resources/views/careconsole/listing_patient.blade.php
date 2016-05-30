@foreach($listing['patients'] as $patient)
	<div class="row drilldown_item" data-patientid="{{ $patient['patient_id'] }}" data-consoleid="{{ $patient['console_id'] }}">
		@foreach($listing['headers'] as $header)
		@if($header['name'] === 'actions')
		<div class="col-xs-{{ $header['width'] }} center_dropdown_action" data-name="{{ $header['name'] }}">
			<div class="dropdown">
				<span class="glyphicon glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu{{ $patient['patient_id'] }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="float: right;background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span>
				<ul class="dropdown-menu action_dropdownmenu"  data-patient-name="{{ $patient['patient_name'] }}" data-patient-email="{{ $patient['patient_email'] }}" data-patient-phone="{{ $patient['patient_phone'] }}" aria-labelledby="dropdownMenu{{ $patient['patient_id'] }}" data-patientid="{{ $patient['patient_id'] }}" data-consoleid="{{ $patient['console_id'] }}">
					@foreach($actions as $action)
					@if($patient['priority'] == 1 && $action['id'] == 30)

					@elseif($patient['priority'] != 1 && $action['id'] == 31)

					@else
					<li class="careconsole_action" data-id="{{$action['id']}}" data-displayname="{{$action['display_name']}}" data-name="{{$action['name']}}"><a href="#">{{$action['display_name']}}</a></li>
					@endif
					@endforeach

				</ul>
			</div>
		</div>
		@else
		<div class="col-xs-{{ $header['width'] }}" data-consoleid="{{ $patient['console_id'] }}" data-name="{{ $header['name'] }}">
			<p>
				{{ $patient[$header['name']] }}
				@if($header['name'] === 'full-name' && $patient['priority'] === 1)
				<img src="{{URL::asset('images/priority-icon.png')}}" style="width:2em;margin-bottom: 3px;" alt="">
				@endif
			</p>
		</div>
		@endif
		@endforeach
	</div>
	@endforeach
