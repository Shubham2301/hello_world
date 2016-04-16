<div class="no-padding">
    <div class="row sidebar_header center">
        <div>
            <div class="dropdown">
                <span class="dropdown-toggle admin_button sidebar_user_img_dropdown" type="button" data-toggle="dropdown">
                <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
                <span class="caret"></span></span>
                <ul class="dropdown-menu sidebar">
                    <li class="hello">
                        <a href="/directmail" data-toggle="tooltip" title="Direct Mail" data-placement="right"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="/file_exchange" data-toggle="tooltip" title="File Exchange" data-placement="right"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="#" id="menu-announcements" data-toggle="tooltip" title="Announcements" data-placement="right"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="/referraltype" data-toggle="tooltip" title="Schedule Patients" data-placement="right"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a>
                    </li>
                    <!-- <li>
                        <a href="#" data-toggle="tooltip" title="Patients Records" data-placement="right"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a>
                    </li>
 -->                    @can('care-cordination')
                    <li>
                        <a href="/careconsole" data-toggle="tooltip" title="Care Console" data-placement="right"><img src="{{URL::asset('images/sidebar/care-coordination.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                    @if(2 == Auth::user()->usertype_id)
                    <li>
                        <a href="/administration/patients" data-toggle="tooltip" title="Administration" data-placement="right"><img src="{{URL::asset('images/sidebar/administration.png')}}" class="drop_image"></a>
                    </li>
                    @endif
                    <li><a href="/reports" data-toggle="tooltip" title="Reports" data-placement="right"><img src="{{URL::asset('images/sidebar/reports.png')}}" class="drop_image"></a></li>
                </ul>
            </div>
        </div>
        <div>
        <h3 class="title">Reporting</h3></div>
    </div>

    <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
              <span class="report_sidebar_button sidebar_realtime realtime_header">
                  <span class="report_sidebar_image">
                      <span><img src="{{URL::asset('images/real-time-icon.png')}}" style="width:80%;height:80%;"></span>
                  </span>
                  <span class="report_sidebar_text">
                      <span class="text">Real Time</span>
                  </span>
              </span>
          </a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in">
        <div class="panel-body">
            <div class="expandable_sidebar active" id="population_report_options">
            </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
              <span class="report_sidebar_button sidebar_historical historical_header">
<!--              <span class="report_sidebar_button">-->
                  <span class="report_sidebar_image">
                      <span><img src="{{URL::asset('images/historical-icon.png')}}" style="width:80%;height:80%;"></span>
                  </span>
                  <span class="report_sidebar_text">
                      <span class="text">Historical</span>
                  </span>
              </span>
          </a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <div class="panel-body"></div>
      </div>
    </div>
  </div>

</div>

<!--
   <div class="sidebar_container">
    <div class="sidebar_container_inner">
        <div class="row sidebar_section sidebar_realtime">
            <div class="col-xs-1 remove-padding"></div>
            <div class="col-xs-2 remove-padding">
                <img src="{{URL::asset('images/icon-realtime.png')}}" alt="" class="img-responsive sidebar_icon">
            </div>
            <div class="col-xs-7 remove-padding">
                <p class="sidebar_header reports realtime_header active">Population Report</p>
                <p class="sidebar_sub_header realtime_sub_header active">Real Time</p>
            </div>
            <div class="col-xs-1 remove-padding ">
                <span class="glyphicon glyphicon-chevron-down realtime-glyph" aria-hidden="true"></span>
            </div>
            <div class="expandable_sidebar active" id="population_report_options">
            </div>

        </div>
        <div class="sidebar_separator"></div>

        <div class="row sidebar_historical">
           <div class="sidebar_section">
            <div class="col-xs-1 remove-padding"></div>
            <div class="col-xs-2 remove-padding">
                <img src="{{URL::asset('images/icon-historical.png')}}" alt="" class="img-responsive sidebar_icon">
            </div>
            <div class="col-xs-7 remove-padding">
                <p class="sidebar_header reports historical_header">Population Report</p>
                <p class="sidebar_sub_header historical_sub_header">Historical</p>
            </div>
            <div class="col-xs-1 remove-padding">
                <span class="glyphicon glyphicon-chevron-down historical-glyph" aria-hidden="true"></span>
            </div>
            </div>
            <div class="expandable_sidebar_historical">
                <div class="col-xs-8 col-xs-offset-4 remove-left-padding sidebar_item_historical">Most Referred To</div>
                <div class="row" id="most_referral_to"></div>
                <div class="col-xs-8 col-xs-offset-4 remove-left-padding sidebar_item_historical">Most Referred By</div>
                <div class="row" id="most_referral_by"></div>
                <div class="col-xs-8 col-xs-offset-4 remove-left-padding sidebar_item_historical">Most Appointment Type</div>
                <div class="row" id="most_appointment_type"></div>
            </div>
        </div>
    </div>
</div>
-->
