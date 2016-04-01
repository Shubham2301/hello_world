<div class="row content-row-margin arial" data-id="">

    <div class="col-xs-12">

        <div class="row">
            <div class="col-xs-12">
                <button type="button" class="btn back user_back">Back</button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 center-align">
                <img src="{{asset('images/patient.png')}}" alt="">
            </div>
            <div class="col-xs-8">
                <p class="bold arial_bold" id="user_name">{{ $data['user']['lastname'].','.$data['user']['firstname'] }}</p>

				<div class="row section_header">
                    <div class="col-xs-9 arial_bold">
                        <p>General Information</p>
                        <p class="section-break"></p>
                    </div>
                </div>

				<div class="row row_content_margin">
                    <div class="col-xs-6">
                        <p><span class="bold arial_bold">Email</span>
                            <br><span class="user_detail_info" id="user_email"> {{$data['user']['email']}}</span></p>
                    </div>
                    <div class="col-xs-6">
                        <p><span class="bold arial_bold">Address</span>
                            <br><span class="user_detail_info" id="user_add1">{{ $data['user']['address1'] }}</span>
                            <br><span class="user_detail_info" id="user_add2">{{ $data['user']['address2'] }}</span>
                            <br><span class="user_detail_info" id="user_add2">{{ $data['user']['city'].' '.$data['user']['zip'] }}</span>
                    </div>
                </div>

				<div class="row row_content_margin">
                    <div class="col-xs-6">
                        <p><span class="bold arial_bold">Phone</span>
                            <br><span class="user_detail_info" id="user_phone">{{ $data['user']['cellphone'] }}</span></p>
                    </div>
                    <div class="col-xs-6">
                        <p><span class="bold arial_bold">NPI</span>
                            <br><span class="user_detail_info" id="user_ssn"> {{ $data['user']['npi'] }} </span> </p>
                    </div>
                </div>

				<div class="row section_header">
					<div class="col-xs-9 arial_bold">
                        <p>Roles and User Access</p>
                        <p class="section-break"></p>
                    </div>
                </div>

				<div class="row row_content_margin">
                    <div class="col-xs-6">
                        <p><span class="bold arial_bold">User Type</span>
                            <br><span class="user_detail_info">{{ $data['usertype'] }}</span></p>
                    </div>
                    <div class="col-xs-6">
                        <p><span class="bold arial_bold">Network/Practice</span>
                            <br><span class="user_detail_info"> {{ $data['network'] }} </span> </p>
                    </div>
                </div>

				<div class="row row_content_margin">
                    <div class="col-xs-6">
						<p><span class="bold arial_bold" >Roles</span><br>

                                @foreach ($data['Roles'] as $roles)
                                <sapn class='arial'>{{ $roles['display_name'] }}</span><br>
                                @endforeach

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
