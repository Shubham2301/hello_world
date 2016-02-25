<div class="no-padding">
    <div class="row sidebar_header center">
        <div class="col-lg-2 col-md-2">
            <div class="dropdown" >
                <button class="dropdown-toggle admin_button" type="button" data-toggle="dropdown" >
                <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
                <span class="caret"></span></button>
                <ul class="dropdown-menu sidebar" >
                    <li class="hello"><a href="/directmail"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a></li>
                    <li><a href="file_exchange"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a></li>
                    <li><a href="#" id="open_announcement"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image"></a></li>
                    <li><a href="/home"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a></li>
                    <li><a href="#"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a></li>
                    <li><a href="/careconsole"><img src="{{URL::asset('images/sidebar/care-coordination.png')}}" class="drop_image"></a></li>
                    <li><a href="/administration/practices"><img src="{{URL::asset('images/sidebar/administration.png')}}" class="drop_image"></a></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-9 col-md-10">
            <h3 class="title">Care Console</h3>
            <div class="c3_overview_link"><img src="{{URL::asset('images/overview_icon.png')}}"><p>Overview</p></div>
        </div>
    </div>
    <ul class="c3_sidebar_list sidebar_item_list">
        @foreach($overview['stages'] as $stage)
        <li class="sidebar_menu_item">
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
    <div class="console_buckets" style="color:black">
        <img src="{{URL::asset('images/recall-icon.png')}}" alt="">
        <p>Recalll</p>
    </div>
    <div class=" console_buckets" style="color:black">
        <img src="{{URL::asset('images/archive-icon.png')}}" alt="">
        <p>Archive</p>
    </div>
    <div class=" console_buckets" style="color:black">
        <img src="{{URL::asset('images/priority-icon.png')}}" alt="">
        <p>Priority</p>
    </div>
</div>
    <div class="control_section"></div>
</div>
