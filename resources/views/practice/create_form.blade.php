<div class="row content-row-margin add_header">
    <div>
        @if(Auth::check())
        <button type="button" id="back_to_select_practice_btn" class="btn back back_practice">Back</button>
        @endif
    </div>
    <div>
        <p class="add_title" @if(!Auth::check()) style="margin-left:0" @endif>
            @if(Auth::check())
                @if(isset($data['onboard']))
                    Onboard Practice Location
                @elseif (isset($data['edit']))
                    Edit Practice
                @else
                    Add New Practice
                @endif
            @else
                Add Location Information
            @endif
        </p>
    </div>
</div>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        General Information</a>
      </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="row content-row-margin">
                    <div class="col-sm-6 col-xs-12">
                        @if(!Auth::check())
                            <input id="onboarding_id" type="hidden" value="{{$data['onboarding_id']}}">
                            <input id="onboarding_token" type="hidden" value="{{$data['onboarding_token']}}">
                        @endif
                        <input id="editmode" type="hidden" value="{{$data['id']}}">
                        <input id="location_index" type="hidden" value="{{$data['location_index']}}">
                        {!! Form::text('practice_name', old('practice_name'), array('class' => 'add_practice_input', 'required' => 'required', 'placeholder' => 'Practice Name*', 'id' => 'practice_name' , 'data-toggle' => 'tooltip', 'title' => 'Practice Name', 'data-placement' => 'right', 'maxlength' => '50')) !!}
                        {!! Form::text('practice_email', old('practice_email'), array('class' => 'add_practice_input', 'required' => 'required', 'placeholder' => 'Practice Email*', 'id' => 'practice_email', 'data-toggle' => 'tooltip', 'title' => 'Practice Email', 'data-placement' => 'right')) !!}
                    </div>
                    <div class="col-sm-6 col-xs-12 ocuapps_options" style="color:#fff;">
                        <h4>Networks*</h4>
                            @foreach($networks as $key => $network)
                                {!! Form::checkbox('network[]', $network, in_array($key, $data['network_id']) ? true : null, array('id' => $key, 'class' => 'practice_network', in_array($key, $data['network_id']) ? 'disabled' : '')); !!}
                                {!! Form::label($network, $network); !!}
                                <br>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        Location Information</a>
      </h4>
        </div>
        <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row content-row-margin">
                    <div class="col-xs-12 col-sm-12 add_location_header">
                        <br>
                        <p style="color:#fff;font-size:1.2em;margin:0">Location Details</p>
                        <div class="change_location">
<!--                            <span class="glyphicon glyphicon-chevron-up glyph_design" id="location_next"></span>-->
                           <img class="create_location_toggle" id="location_next" src="{{URL::asset('images/triangle-up.png')}}">
                            <span><p class="location_counter">0</p></span>
                            <img class="create_location_toggle" id="location_previous" src="{{URL::asset('images/triangle-down.png')}}">
<!--                            <span class="glyphicon glyphicon-chevron-down glyph_design" id="location_previous"></span>-->

                        </div>
                        <div>
                            <button id="add_location" class="btn add_location_button "> add +</button>
                            <button id="remove_location" class="btn remove_location_button">remove -</button>
                        </div>
                    </div>
                    <div class="row content-row-margin">
                        <div class="col-sm-6 col-xs-12">
                            <input class="add_practice_input" id="locationname" type="text" placeholder="Location Name*" data-toggle="tooltip" title="Location Name" data-placement="right">
                            <input class="add_practice_input" id="location_code" type="text" placeholder="Location Code*"  data-toggle="tooltip" title="Location Code" data-placement="right">
                            <input class="add_practice_input" id="location_email" type="text" placeholder="Notification Email*" data-toggle="tooltip" title="Notification Email" data-placement="right">
							<input class="add_practice_input" id="phone" type="text" placeholder="Phone*"  data-toggle="tooltip" title="Phone" data-placement="right" pattern = "[^A-Za-z]+">
                            <input class="add_practice_input" id="addressline1" type="text" placeholder="Address*"  data-toggle="tooltip" title="Address" data-placement="right">
                            <input class="add_practice_input" id="city" type="text" placeholder="City*" data-toggle="tooltip" title="City" data-placement="right">
                            <input class="add_practice_input" id="state" type="text" placeholder="State*" data-toggle="tooltip" title="State" data-placement="right">
                            <input class="add_practice_input" id="zip" type="text" placeholder="Zip*" data-toggle="tooltip" title="ZIP" data-placement="right">
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <input class="add_practice_input" id="addressline2" type="text" placeholder="AddressLine2" style="display:none;">
                            <div class="special_instruction_lable">Special Instructions</div>
                            <div class="special_instructions">
                                <textarea id="special_instruction_text">
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('user-level') == 1)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">License Information</a>
      </h4>
        </div>
        <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row content-row-margin">
                    <div class="col-sm-6 col-xs-12">
                        {!! Form::text('location_license', old('location_license'), array('class' => 'add_practice_input', 'placeholder' => 'Location License Count', 'id' => 'location_license' , 'data-toggle' => 'tooltip', 'title' => 'Location License', 'data-placement' => 'right')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="row content-row-margin ">
    <div class="col-xs-12 create_practice_buttons">
        <button type="button" class="btn save_practice_button" id="savepractice">Save</button>
        @if(session('user-level') == 1 && !isset($data['edit']))
        <button type="button" class="btn save_practice_button" id="onboardpractice" data-toggle="tooltip" title="Save practice and send notification to add locations to the email address provided" data-placement="bottom">Onboard</button>
        @endif
        @if(session('user-level') == 1 && isset($data['onboard']))
        <button type="button" class="btn save_practice_button" id="discardOnboard" data-toggle="tooltip" title="Discard the location information received from the practice" data-placement="bottom">Discard</button>
        @endif
        <button type="button" class="btn" id="dontsave_new_practice">Don't Save</button>
    </div>
</div>
