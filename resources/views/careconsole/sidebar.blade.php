<div class="no-padding">
    <div class="row sidebar_header center">
        <div>
            <div class="dropdown" >
                <span class="dropdown-toggle admin_button sidebar_user_img_dropdown" type="button" data-toggle="dropdown" >
                <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
                <span class="caret"></span></span>
                <ul class="dropdown-menu sidebar" >
                    @can('access-directmail')
                    <li class="hello"><a href="/directmail" data-toggle="tooltip" title="Direct Mail" data-placement="right"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a></li>
                    @endcan
                    <li><a href="file_exchange" data-toggle="tooltip" title="File Exchange" data-placement="right"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a></li>
                    <li><a href="#" id="menu-announcements" data-toggle="tooltip" title="Announcements" data-placement="right"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image menu-announcements"></a></li>
                    <li><a href="/referraltype" data-toggle="tooltip" title="Schedule Patients" data-placement="right"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a></li>
<!--                    <li><a href="#" data-toggle="tooltip" title="Patients Records" data-placement="right"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a></li>-->
                    @can('care-cordination')
                    <li><a href="/careconsole" data-toggle="tooltip" title="Care Console" data-placement="right"><img src="{{URL::asset('images/sidebar/care-coordination.png')}}" class="drop_image"></a></li>
                    @endcan
                    @if(2 == Auth::user()->usertype_id)
                    <li><a href="/administration" data-toggle="tooltip" title="Administration" data-placement="right"><img src="{{URL::asset('images/sidebar/administration.png')}}" class="drop_image"></a></li>
                    @endif
                    @can('view-reports')
                    <li>
                        <a href="/careconsole_reports"  data-toggle="tooltip" title="Reports" data-placement="right"><img src="{{URL::asset('images/sidebar/reports.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        <div>
            <h3 class="arial_bold title">Care Console</h3>
            <div class="c3_overview_link arial_bold"><img src="{{URL::asset('images/overview_icon.png')}}"><p>Overview</p></div>
        </div>
    </div>
    <ul class="c3_sidebar_list sidebar_item_list">
        @foreach($overview['stages'] as $stage)
        <li class="sidebar_menu_item" data-id="{{ $stage['id'] }}">
            <div class="stage box" id="sidebar_{{ $stage['name'] }}" data-id="{{ $stage['id'] }}" data-name="{{ $stage['display_name'] }}"><span style="background-color:{{ $stage['color_indicator'] }}"><p class="stage-notation">{{ $stage['abbr'] }}</p></span><p>{{ $stage['display_name'] }}</p></div>
            <!-- <ul>
                <div class="info_box">
                    <div class="top">
                        @if(isset($stage['kpis']))
                        @for($i = 0; $i < $stage['kpi_count']; $i++)
                        <div class="info_section" id="{{ $stage['kpis'][$i]['name'] }}" data-name="{{ $stage['kpis'][$i]['display_name'] }}" data-indicator="{{ $stage['kpis'][$i]['color_indicator'] }}">
                            <div class="info_section_title">{{ $stage['kpis'][$i]['display_name'] }}</div>
                            <div class="right">
                                <div class="circle" style="background-color:{{ $stage['kpis'][$i]['color_indicator'] }}"></div>
                                <div class="info_section_number">{{ $stage['kpis'][$i]['count'] }}</div>
                            </div>
                        </div>
                        @if($i < $stage['kpi_count'] - 1)
                        <div class="section_break"></div>
                        @endif
                        @endfor
                        @endif
                    </div>
                </div>
            </ul> -->
        </li>
        @endforeach
        <!--
        <li class="sidebar_menu_item">
            <div class="stage box" id="" data-id="" data-name=""><span></span><p>Status</p></div>
        </li>
        -->
    </ul>
    <div class="C3_day_row console_bucket_row">
        <div class="console_buckets"  data-name="recall" style="color:black">
            <img src="{{URL::asset('images/recall-icon.png')}}" alt="">
            <p>Recall</p>
        </div>
        <div class=" console_buckets" data-name="archived" style="color:black">
            <img src="{{URL::asset('images/archive-icon.png')}}" alt="">
            <p>Archive</p>
        </div>
        <div class=" console_buckets"  data-name="priority" style="color:black">
            <img src="{{URL::asset('images/priority-icon.png')}}" alt="">
            <p>Priority</p>
        </div>
    </div>
    <div class="control_section"></div>
</div>
