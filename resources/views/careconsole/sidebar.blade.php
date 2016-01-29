<div class="no-padding">
<div class="row sidebar_header center">
<div class="col-lg-2 col-md-2">
<div class="dropdown" >
    <button class="dropdown-toggle admin_button" type="button" data-toggle="dropdown" ><img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini">
    <span class="caret"></span></button>
    <ul class="dropdown-menu" >
      <li class="hello"><a href="/directmail"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image"></a></li>
      <li><a href="/home"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a></li>
      <li><a href="/careconsole"><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="drop_image"></a></li>
      <li><a href="/administration/practices"><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="drop_image"></a></li>
    </ul>
    </div>
    </div>
    <div class="col-lg-9 col-md-10">
        <h3 class="title"> Care Console</h3>
        <div class="c3_overview_link"><img src="{{URL::asset('images/overview_icon.png')}}"><p>Overview</p></div>
    </div>
</div>
    <ul class="c3_sidebar_list">
        <li>
            <div class="stage box" id="0" data-name="Contact Status"><p>Contact Status</p></div>
            <ul>
                <div class="info_box" id="0" data-name="Contact Status">
                    <div class="top">
                        <div class="info_section" id="0" data-name="Contact Attempted">
                            <div class="info_section_title">Contact Attempted</div>
                            <div class="right">
                                <div class="circle green"></div>
                                <div class="info_section_number">7</div>
                            </div>
                        </div>
                        <div class="section_break"></div>
                        <div class="info_section" id="1" data-name="Contact Pending">
                            <div class="info_section_title">Contact Pending</div>
                            <div class="right">
                            <div class="circle red"></div>
                            <div class="info_section_number">9</div>
                            </div>
                        </div>
                    </div>
                </div>
            </ul>
        </li>
        <li>
            <div class="stage box" id="11" data-name="Schedule for appointment"><p>Schedule for appointment</p></div>
            <ul>
                <div class="info_box" id="11" data-name="Schedule for appointment">
                   <div class="top">
                        <div class="info_section" id="2" data-name="Future Appointment">
                            <div class="info_section_title">Future Appointment</div>
                            <div class="right">
                            <div class="circle green"></div>
                            <div class="info_section_number">15</div>
                            </div>
                        </div>
                        <div class="section_break"></div>
                        <div class="info_section" id="3" data-name="Appointment Tommorow">
                            <div class="info_section_title">Appointment Tommorow</div>
                            <div class="right">
                            <div class="circle yellow"></div>
                            <div class="info_section_number">8</div>
                            </div>
                        </div>
                        <div class="section_break"></div>
                        <div class="info_section" id="4" data-name="Past Appointment">
                            <div class="info_section_title">Past Appointment</div>
                            <div class="right">
                            <div class="circle red"></div>
                            <div class="info_section_number">5</div>
                            </div>
                        </div>
                    </div>
                </div>
            </ul>
        </li>
    </ul>
<div class="control_section">
    <div class="C3_day_row control_header"><p>Controls</p></div>
    <div class="C3_day_row control_label">
        <p>Days Pending</p>
        <p class="no_of_patients">6 patients</p>
    </div>
    <div class="C3_day_row">
        <div class="C3_day_box low">
            <h4><4</h4>
                <p>Low</p>
        </div>
        <div class="C3_day_box normal">
            <h4>4-8</h4>
                <p>Normal</p>
        </div>
        <div class="C3_day_box urgent">
           <h4><4</h4>
                <p>Urgent</p>
        </div>
    </div>
    <div class="C3_day_row control_label">
        <p>Show vs No Show</p>
    </div>
    <div class="C3_day_row">
        <div class="C3_day_box show">
            <div class="show_bar"></div>
            <p>4</p>
            <p>Show</p>
        </div>
        <div class="C3_day_box no_show">
           <div class="no_show_bar"></div>
           <p>6</p>
           <p>No Show</p>
        </div>
        <div class="empty">

        </div>
    </div>
</div>
</div>
