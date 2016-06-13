<div class="no-padding">
    <div class="row sidebar_header center">
        <div>
            <div class="dropdown">
                <span class="dropdown-toggle admin_button sidebar_user_img_dropdown" type="button" data-toggle="dropdown">
                <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
                <span class="caret"></span></span>
                <ul class="dropdown-menu sidebar">
                    @can('access-directmail')
                    <li class="hello">
                        <a href="/directmail" data-toggle="tooltip" title="Direct Mail" data-placement="right"><img src="{{elixir('images/sidebar/messages.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                    <li>
                        <a href="/file_exchange" data-toggle="tooltip" title="File Exchange" data-placement="right"><img src="{{elixir('images/sidebar/file_update.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="#" id="menu-announcements" data-toggle="tooltip" title="Announcements" data-placement="right"><img src="{{elixir('images/sidebar/announcements.png')}}" class="drop_image menu-announcements"></a>
                    </li>
                    <li>
                        <a href="/referraltype" data-toggle="tooltip" title="Schedule Patients" data-placement="right"><img src="{{elixir('images/sidebar/schedule.png')}}" class="drop_image"></a>
                    </li>
                    <!-- <li>
                        <a href="#" data-toggle="tooltip" title="Patients Records" data-placement="right"><img src="{{elixir('images/sidebar/records.png')}}" class="drop_image"></a>
                    </li>
 -->                    @can('care-cordination')
                    <li>
                        <a href="/careconsole" data-toggle="tooltip" title="Care Console" data-placement="right"><img src="{{elixir('images/sidebar/care-coordination.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                    @if(2 == Auth::user()->usertype_id)
                    <li>
                        <a href="/administration/patients" data-toggle="tooltip" title="Administration" data-placement="right"><img src="{{elixir('images/sidebar/administration.png')}}" class="drop_image"></a>
                    </li>
                    @endif
                    @can('view-reports')
                    <li>
                        <a href="/careconsole_reports"  data-toggle="tooltip" title="Reports" data-placement="right"><img src="{{elixir('images/sidebar/reports.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        <div>
        <h3 class="title">Reporting</h3></div>
    </div>
<div class="sidebar_item_list">
<span class="report_sidebar_button sidebar_realtime realtime_header active">
                  <span class="report_sidebar_image">
                      <span><img src="{{URL::asset('images/real-time-icon.png')}}" style="width:80%;height:80%;"></span>
                  </span>
                  <span class="report_sidebar_text">
                      <span class="text">Real Time</span>
                  </span>
              </span>
<div class="expandable_sidebar active" id="population_report_options">
            </div>
<span class="report_sidebar_button sidebar_historical historical_header">
                  <span class="report_sidebar_image">
                      <span><img src="{{URL::asset('images/historical-icon.png')}}" style="width:80%;height:80%;"></span>
                  </span>
                  <span class="report_sidebar_text">
                      <span class="text">Historical</span>
                  </span>
              </span>
    </div>
</div>
