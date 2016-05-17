<div class="no-padding">
    <div class="row sidebar_header center">
        <div>
            <div class="dropdown">
                <span class="dropdown-toggle admin_button sidebar_user_img_dropdown" type="button" data-toggle="dropdown">
                <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
                <span class="caret"></span></span>
                <ul class="dropdown-menu sidebar">
					<li class="hello" data-toggle="tooltip" title="Direct Mail" data-placement="right">
                        <a href="/directmail"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a>
                    </li>
                    <li>
						<a href="/file_exchange" data-toggle="tooltip" title="File Exchange" data-placement="right"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a>
                    </li>
                    <li>
						<a href="#" id="menu-announcements" data-toggle="tooltip" title="Announcements" data-placement="right"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image menu-announcements"></a>
                    </li>
                    <li>
						<a href="/referraltype" data-toggle="tooltip" title="Schedule Patients" data-placement="right"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a>
                    </li>
<!--
                    <li>
                        <a href="#"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a>
                    </li>
-->
                    @can('care-cordination')
                    <li>
						<a href="/careconsole" data-toggle="tooltip" title="Care Console" data-placement="right"><img src="{{URL::asset('images/sidebar/care-coordination.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                    @if(2 == Auth::user()->usertype_id)
                    <li>
						<a href="/administration" data-toggle="tooltip" title="Administration" data-placement="right"><img src="{{URL::asset('images/sidebar/administration.png')}}" class="drop_image"></a>
                    </li>
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
            <h3 class="title">Files Exchange</h3>
        </div>
    </div>
    <ul class="sidebar_item_list arial">
        <li>
            <a class="files_sidebar_menu_item" href="/file_exchange">
                <span class="sidebar_img"><img src="{{URL::asset('images/sidebar/myfiles-icon.png')}}" style="width:100%;height:100%;"></span>
                <span class="sidebar_title">My Files</span>
            </a>
        </li>
        <li>
            <a class="files_sidebar_menu_item" href="/sharedWithMe">
                <span class="sidebar_img"><img src="{{URL::asset('images/sidebar/sharedwithme-icon.png')}}" style="width:100%;height:100%;"></span>
                <span class="sidebar_title">Shared With Me</span>
            </a>
        </li>
        <li>
            <a class="files_sidebar_menu_item" href="/recentShareChanges">
                <span class="sidebar_img"><img src="{{URL::asset('images/sidebar/sharechanges-icon.png')}}" style="width:100%;height:100%;"></span>
                <span class="sidebar_title">Recent Share Changes</span>
            </a>
        </li>
        <li>
            <a class="files_sidebar_menu_item" href="/trash">
                <span class="sidebar_img"><img src="{{URL::asset('images/sidebar/trash-icon.png')}}" style="width:100%;height:100%;"></span>
                <span class="sidebar_title">Trash</span>
            </a>
        </li>
    </ul>
</div>
