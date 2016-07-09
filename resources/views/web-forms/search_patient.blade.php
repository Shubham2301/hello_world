@foreach($patients as $patient)
<div class="col-xs-12 patient_list_item" data-id="{{$patient->id}}" data-name = "{{ $patient['lastname'].','. $patient['firstname']}}">
    <div class="row content-row-margin arial">
        <div class="col-xs-12 arial_bold patient_list_name"> {{ $patient['lastname'].','. $patient['firstname']}} </div>
		<div class="col-xs-6 patient_list_data">{{ ($patient->birthdate && (bool)strtotime($patient->birthdate))? date('F d, Y', strtotime($patient->birthdate)) : '-'  }}
            <br> {{$patient['phone']}} </div>
        <div class="col-xs-6 patient_list_data"> {{ $patient['email'] }}
            <br> {{ $patient['city']}} </div>
    </div>
</div>
@endforeach
