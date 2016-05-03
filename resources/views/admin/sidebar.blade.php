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
 -->@can('care-cordination')
                    <li>
                        <a href="/careconsole" data-toggle="tooltip" title="Care Console" data-placement="right"><img src="{{URL::asset('images/sidebar/care-coordination.png')}}" class="drop_image"></a>
                    </li>
                    @endcan @if(2 == Auth::user()->usertype_id)
                    <li>
                        <a href="/administration/patients" data-toggle="tooltip" title="Administration" data-placement="right"><img src="{{URL::asset('images/sidebar/administration.png')}}" class="drop_image"></a>
                    </li>
                    @endif @can('view-reports')
                    <li>
                        <a href="/careconsole_reports" data-toggle="tooltip" title="Reports" data-placement="right"><img src="{{URL::asset('images/sidebar/reports.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
        <div>
            <h3 class="title"> Administration</h3></div>
    </div>

    <ul class="sidebar_item_list">
        <li class="admin_sidebar_menu_item">
            <a class="sidebar_button_subsection subsection_admin_add" href="/administration/patients/create">
                <span class="img_not_hover"><img src="{{URL::asset('images/sidebar/admin-patient-icon.png')}}" style="width:100%"></span>
                <span class="img_on_hover"><img src="{{URL::asset('images/sidebar/admin-patient-icon-hover.png')}}" style="width:100%"></span>
                <span class="add_text">add<span class="arial_bold" style="color:#de3c4b;">+</span></span>
            </a>
            <a class="sidebar_button_subsection subsection_admin_title patients" href="/administration/patients" id="{{ array_key_exists('patient_active', $data) ? 'button_active' : '' }}">
                <span>Patients</span>
            </a>
        </li>
        <!-- <li class="admin_sidebar_menu_item">
                                            <a class="sidebar_button_subsection subsection_admin_add" href="/administration/providers">
                                                <span class="img_not_hover"><img src="{{URL::asset('images/sidebar/admin-provider-icon.png')}}" style="width:100%"></span>
                                                <span class="img_on_hover"><img src="{{URL::asset('images/sidebar/admin-provider-icon-hover.png')}}" style="width:100%"></span>
                                                <span class="add_text">add+</span>
                                            </a>
                                            <a class="sidebar_button_subsection subsection_admin_title" href="/administration/providers" id="{{ array_key_exists('provider_active', $data) ? 'button_active' : '' }}">
                                                <span>Providers</span>
                                            </a>
                                        </li> -->
        @if(2 >= Auth::user()->level)
        <li class="admin_sidebar_menu_item">
            <a class="sidebar_button_subsection subsection_admin_add" href="/administration/practices/create">
                <span class="img_not_hover"><img src="{{URL::asset('images/sidebar/admin-practice-icon.png')}}" style="width:100%"></span>
                <span class="img_on_hover"><img src="{{URL::asset('images/sidebar/admin-practice-icon-hover.png')}}" style="width:100%"></span>
                <span class="add_text">add<span class="arial_bold" style="color:#7e6551;">+</span></span>
            </a>
            <a class="sidebar_button_subsection subsection_admin_title practices" href="/administration/practices" id="{{ array_key_exists('practice_active', $data) ? 'button_active' : '' }}">
                <span>Practices</span>
            </a>
        </li>
        @endif @if(1 >= Auth::user()->level)
        <li class="admin_sidebar_menu_item">
            <a class="sidebar_button_subsection subsection_admin_add" href="/administration/networks/create">
                <span class="img_not_hover"><img src="{{URL::asset('images/sidebar/admin-network-icon.png')}}" style="width:100%"></span>
                <span class="img_on_hover"><img src="{{URL::asset('images/sidebar/admin-network-icon-hover.png')}}" style="width:100%"></span>
                <span class="add_text">add<span class="arial_bold" style="color:#808080;">+</span></span>
            </a>
            <a class="sidebar_button_subsection subsection_admin_title networks" href="/administration/networks" id="{{ array_key_exists('network_active', $data) ? 'button_active' : '' }}">
                <span>Networks</span>
            </a>
        </li>
        @endif
        <li class="admin_sidebar_menu_item">
            <a class="sidebar_button_subsection subsection_admin_add" href="/administration/users/create">
                <span class="img_not_hover"><img src="{{URL::asset('images/sidebar/admin-user-icon.png')}}" style="width:100%"></span>
                <span class="img_on_hover"><img src="{{URL::asset('images/sidebar/admin-user-icon-hover.png')}}" style="width:100%"></span>
                <span class="add_text">add<span class="arial_bold" style="color:#6b31d7;">+</span></span>
            </a>
            <a class="sidebar_button_subsection subsection_admin_title users" href="/administration/users" id="{{ array_key_exists('user_active', $data) ? 'button_active' : '' }}">
                <span>Users</span>
            </a>
        </li>
        @if(1 >= Auth::user()->level)
        <li class="admin_sidebar_menu_item">
            <a class="files_sidebar_menu_item" href="/auditreports">
                <span class="sidebar_img"><img src="{{URL::asset('images/sidebar/audit_icon.png')}}" style="width:90%;height:90%;"></span>
                <span class="sidebar_title">Admin Reports</span>
            </a>
        </li>
        @endif
    </ul>
</div>
