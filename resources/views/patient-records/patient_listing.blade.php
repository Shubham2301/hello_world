<div class="col-xs-6">
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
<div class="col-xs-6" style="margin-top:2em;" >
@for($i=0; $i<3; $i++)
   <div class="row">
        <div class="col-xs-1">
        	1,2016<br>
        	March
        </div>
   		<div class="col-xs-2" style="margin-top:1.5em">
   			<div class="timeline">
  			<ul>
    			<li class="inactive">
    			</li>
  			</ul>
			</div>
   		</div>
   		<div class="col-xs-6">
   		      <div>
     				<span data-toggle="collapse" data-target="#{{'left'.$i}}" class="patient_status">{{ 'Hello' }}
     				</span>
					<div class="row collapse" id="{{ 'left'.$i }}" >
						<div class="col-xs-6">
	                      hello
						</div>
						<div class="col-xs-6">
						</div>
					</div>
			   </div>
   		</div>
   </div>
   @endfor
</div>
