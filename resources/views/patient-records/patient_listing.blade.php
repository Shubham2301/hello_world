<div class="col-xs-5">
	<div style="margin:4em;">
		<div class="row search_list_header">
			<div style="margin-bottom:2em;">
				<span style="float:left; padding-left:1em;" class="arial">Name</span>
				<span style="float:right;padding-right:1em;" class="arial" id="pagination">
					<img src="{{URL::asset('images/sidebar/left-active.png')}}" alt="" id="paginate_left" data-index="{{$patients[0]['previouspage']}}">
					<span> {{$patients[0]['result_count_info']}} </span>
					<img src="{{URL::asset('images/sidebar/right-active.png')}}" alt="" id="paginate_right" data-index="{{$patients[0]['nextpage']}}">
				</span>
			</div>
		</div>
		<div class="patient_listing">
			@foreach($patients as $patient)
			<div class="row search_list_item">
				<div class="col-xs-12">
					<span data-toggle="collapse" data-target="#{{$patient['id']}}" class="list_item_name">{{ $patient['name'] }}</span>
					<div class="row collapse" id="{{$patient['id']}}" style="margin-top:1em;">
						<div class="col-xs-6">
							<span class="item_info">
								<span>Email</span>
								<br>
								<p>{{ $patient['email'] }}</p>
							</span>
							<span class="item_info">
								<span>Date of Birth</span>
								<br><p>{{ $patient['birthdate'] }} </p>
							</span>
							<span class="item_info">
								<span>Phone</span>
								<br><p>{{$patient['phone']}} </p>
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
								<br><p>{{$patient['lastfourssn']}} </p>
							</span>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
<div class="col-xs-7 care_timeline">
<div style="">
<?php $i =0; ?>
@foreach($progress as $status)
   <div class="row">
        <div class="col-xs-1"> </div>
        <div class="col-xs-2">
           <p class = "date_left">
        	   {{  $status['date'][0].', '. $status['date'][2]  }}
                <br>
                {{ $status['date'][1] }}
            </p>
        </div>
   		<div class="col-xs-1">
   			<div class="timeline">
  			   <ul>
    			 <li class="active">
    			 </li>
  			   </ul>
			</div>
   		</div>
   		<div class="col-xs-6">
   		      <div class="data_right">
     				<span data-toggle="collapse" data-target="#{{'left'.$i}}" class="patient_status arial_bold">{{$status['name']}}
     				</span>
					<div class="row collapse" id="{{ 'left'.$i }}" >
						<div class="col-xs-12 ">
                            <div class="timeline_notes">
                            @if(sizeOf($status['notes']) == 1)
                            {{ $status['notes'][0] }}
                            @elseif($status['notes'] > 1)
                            <div class="row note_item">
                                <div class="col-xs-6">
                                  <span>Scheduled to</span>
                                </div>
                                <div class="col-xs-6">
                                  <span>{{$status['notes'][0]}}</span>
                                </div>
                            </div>
                             <div class="row note_item">
                                <div class="col-xs-6">
                                  <span>Appointment Date</span>
                                </div>
                                <div class="col-xs-6">
                                  <span>{{$status['notes'][1]}}</span>
                                </div>
                            </div>
                             <div class="row note_item">
                                <div class="col-xs-6">
                                  <span>Appointment Type</span>
                                </div>
                                <div class="col-xs-6">
                                  <span>{{$status['notes'][2]}}</span>
                                </div>
                            </div>
                            <br>
                            {{ $status['notes'][4] }}

                            @endif
                            </div>
						</div>
					</div>
			   </div>
   		</div>
   </div>

   <?php $i++; ?>
   @endforeach
   </div>
   <div style="flex-grow: 1; margin-bottom: 1em;">
      <div class="row">
       <div class="col-xs-3"></div>
       <div class="col-xs-2">
           <p>Show More</p>
       </div>

      </div>
   </div>
</div>
