
@if ((!array_key_exists('id', $patients[0])) || sizeof($patients) === 0)

   <div class="row content-row-margin no_item_found active">
    <p class = "text-center">No results found matching : </p>
       <p class = "text-center">{{ $patients[0]['search_value'] }}</p>
   </div>

@else

@foreach($patients as $patient)
<div class="col-xs-12 patient_list_item" data-id="{{$patient['id']}}" data-name = "{{ $patient['name'] }}">
    <div class="row content-row-margin arial">
        <div class="col-xs-12 arial_bold patient_list_name"> {{ $patient['name'] }} </div>
		<div class="col-xs-6 patient_list_data">{{ $patient['birthdate'] }}
            <br> {{ $patient['phone'] }} </div>
        <div class="col-xs-6 patient_list_data"> {{ $patient['email'] }}
            <br> {{ $patient['city']}} </div>
    </div>
</div>
@endforeach

@endif
