<div class="no-padding">
    <div class="row sidebar_header center">
        <div class="col-lg-2 col-md-2">
            <div class="dropdown">
                <button class="dropdown-toggle admin_button" type="button" data-toggle="dropdown">
                <img src="{{URL::asset('images/users/user_'. Auth::user()->id .'.jpg')}}" class="profile_img_sidebar_mini" onerror="this.src = '{{URL::asset('images/sidebar/care_coordinator.png')}}'">
                <span class="caret"></span></button>
                <ul class="dropdown-menu sidebar">
                    <li class="hello">
                        <a href="/directmail"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="/file_exchange"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="#" id="menu-announcements"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="/home"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a>
                    </li>
                    <li>
                        <a href="#"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a>
                    </li>
                    @can('care-console')
                    <li>
                        <a href="/careconsole"><img src="{{URL::asset('images/sidebar/care-coordination.png')}}" class="drop_image"></a>
                    </li>
                    @endcan
                    @if(2 == Auth::user()->usertype_id)
                    <li>
                        <a href="/administration/practices"><img src="{{URL::asset('images/sidebar/administration.png')}}" class="drop_image"></a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-lg-9 col-md-10">
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
