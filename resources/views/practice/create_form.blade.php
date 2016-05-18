<div class="row content-row-margin add_header">
    <div>
        <button type="button" id="back_to_select_practice_btn" class="btn back back_practice">Back</button>
    </div>
    <div>
        <p class="add_title">
            @if(isset($data['edit']))
                Edit Practice
            @else
                Add New Practice
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
                        <input id="editmode" type="hidden" value="{{$data['id']}}">
                        <input id="location_index" type="hidden" value="{{$data['location_index']}}"> {!! Form::text('practice_name', old('practice_name'), array('class' => 'add_practice_input', 'required' => 'required', 'placeholder' => 'Practice Name*', 'id' => 'practice_name')) !!} {!! Form::text('practice_email', old('practice_email'), array('class' => 'add_practice_input', 'required' => 'required', 'placeholder' => 'Practice Email*', 'id' => 'practice_email')) !!}

                    </div>
                    <div class="col-sm-6 col-xs-12 ocuapps_options">
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
                            <input class="add_practice_input" id="locationname" type="text" placeholder="Location Name*">
                            <input class="add_practice_input" id="location_code" type="text" placeholder="Location Code*">
                            <input class="add_practice_input" id="addressline1" type="text" placeholder="Address*">
                            <input class="add_practice_input" id="city" type="text" placeholder="City*">
                            <input class="add_practice_input" id="state" type="text" placeholder="State*">
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <input class="add_practice_input" id="location_email" type="text" placeholder="Notification Email*">
                            <input class="add_practice_input" id="zip" type="text" placeholder="Zip*">
                            <input class="add_practice_input" id="phone" type="text" placeholder="Phone*">
                            <input class="add_practice_input" id="addressline2" type="text" placeholder="AddressLine2" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row content-row-margin ">
    <div class="col-xs-8 col-sm-8 col-md-8">
        <button type="button" class="btn save_practice_button" id="savepractice">Save</button>
        <button type="button" class="btn add-btn " id="dontsave_new_practice">Don't Save</button>
    </div>
    <div class="col-xs-4 col-sm-4"></div>
</div>
