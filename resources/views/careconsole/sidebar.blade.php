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
        @foreach($overview['stages'] as $stage)
        <li>
            <div class="stage box" id="sidebar_{{ $stage['name'] }}" data-id="{{ $stage['id'] }}" data-name="{{ $stage['display_name'] }}"><p>{{ $stage['display_name'] }}</p></div>
            <ul>
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
            </ul>
        </li>
        @endforeach
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
