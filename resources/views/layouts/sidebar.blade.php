@if(Auth::check())
<div class="sidebar_user_info center"><h4>{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</h4>

</div>
<div class="sidebar_menu center">
<img src="{{URL::asset('images/sidebar/care_coordinator.png')}}">
</div>
<div class="row"><div class="col-md-12">
<ul class="sidebar-item-list">
    <li class="sidebar_menu_item"><a class="sidebar-item" href="/directmail"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="image"></span><span class="menu_item_label">Direct Mail</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="image"></span><span class="menu_item_label">File Exchange</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="image"></span><span class="menu_item_label">Announcements</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="/home"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="image"></span><span class="menu_item_label">Schedule a Patient</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/records.png')}}" class="image"></span><span class="menu_item_label">Patient Records</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="/careconsole"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="image"></span><span class="menu_item_label">Care Console</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="/administration/practices"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="image"></span><span class="menu_item_label">Administration</span></a></li>
    </ul>
    </div>
</div>
@endif
