
        <div class="row search_list_header">
            <div style="margin-bottom:2em;">
                <span style="float:left; padding-left:1em;" class="arial">Name</span>
                <span style="float:right;padding-right:1em;" class="arial" id="pagination">
					<img src="{{URL::asset('images/sidebar/left-active.png')}}" alt="" id="paginate_left" data-index="{{$patients[0]['previouspage']}}">
                    <span> {{ $patients[0]['current_result'].'-'. $patients[0]['upper_result'] .' of '.$patients[0]['total'] }} </span>
                <img src="{{URL::asset('images/sidebar/right-active.png')}}" alt="" id="paginate_right" data-index="{{$patients[0]['nextpage']}}">
                </span>
            </div>
        </div>
        <div class="patient_listing panel-group" id="patients_accordion">
            @foreach($patients as $patient)
            <div class="row search_list_item panel panel-default">
                <div class="panel-heading">
                    <span data-toggle="collapse" data-parent="#patients_accordion" data-target="#{{$patient['id']}}" data-id="{{$patient['id']}}" class="panel-title list_item_name">{{ $patient['name'] }}</span>
                </div>
                <div class="row collapse item_info_section" id="{{$patient['id']}}">
                    <div class="col-xs-6">
                        <span class="item_info">
								<span>Email</span>
                        <br>
                        <p>{{ $patient['email'] }}</p>
                        </span>
                        <span class="item_info">
								<span>Date of Birth</span>
                        <br>
                        <p>{{ $patient['birthdate'] }} </p>
                        </span>
                        <span class="item_info">
								<span>Phone</span>
                        <br>
                        <p>{{$patient['phone']}} </p>
                        </span>
                    </div>
                    <div class="col-xs-6">
                        <span class="item_info">
								<span>
									Address</span>
                        <br>
                        <p>{{$patient['addressline1']}},
                            <br>{{$patient['addressline2']}}
                        </p>
                        </span>
                        <span class="item_info">
								<span>
									SSN
								</span>
                        <br>
                        <p>{{$patient['lastfourssn']}} </p>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


