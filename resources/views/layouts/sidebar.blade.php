<!--
@if(Auth::check())
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    @can('view-report')
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="item-1">
        <h4 class="panel-title">
            <a class="sidebar-item" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-1" aria-expanded="true" aria-controls="collapse-1">Reporting</a>
        </h4>
        </div>
        <div id="collapse-1" class="panel-collapse collapse " role="tabpanel" aria-labelledby="item-1">
            <div class="panel-body">
                <ul class="sidebar-item-list">
                    <li><a class="sidebar-item" href="#">View</a></li>
                </ul>
            </div>
        </div>
    </div>
    @endcan
    @can('create-user')
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="item-2">
        <h4 class="panel-title">
            <a class="sidebar-item" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-2" aria-expanded="true" aria-controls="collapse-2">Administration</a>
        </h4>
        </div>
        <div id="collapse-2" class="panel-collapse collapse " role="tabpanel" aria-labelledby="item-2">
            <div class="panel-body">
                <ul class="sidebar-item-list">
                    <li><a class="sidebar-item" href="/users">Users</a></li>
                    <li><a class="sidebar-item" href="/roles">Roles</a></li>
                    <li><a class="sidebar-item" href="/permissions">Permissions</a></li>
                </ul>
            </div>
        </div>
    </div>
    @endcan
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="item-3">
        <h4 class="panel-title">
            <a class="sidebar-item" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-3" aria-expanded="true" aria-controls="collapse-3">Integrated Care</a>
        </h4>
        </div>
        <div id="collapse-3" class="panel-collapse collapse " role="tabpanel" aria-labelledby="item-3">
            <div class="panel-body">
                <ul class="sidebar-item-list">
                    <li><a class="sidebar-item" href="/directmail">Direct Mail</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

-->

@if(Auth::check())
<div class="sidebar_header center">
<h3 class="title"><span><img src="{{URL::asset('images/sidebar/care_coordination_small.png')}}" class="image"></span>Care Coordination</h3>
</div>
<div class="sidebar_user_info center"><h4>{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</h4>

</div>
<div class="sidebar_menu center">
<img src="{{URL::asset('images/sidebar/care_coordinator.png')}}">
</div>
<div class="container"><div class="row"><div class="col-md-12">
<ul class="sidebar-item-list">
    <li class="sidebar_menu_item"><a class="sidebar-item" href="/directmail"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="image"></span><span class="menu_item_label">Messages</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="image"></span><span class="menu_item_label">File Update</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="image"></span><span class="menu_item_label">Announcements</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="image"></span><span class="menu_item_label">Schedule a Patient</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="#"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/records.png')}}" class="image"></span><span class="menu_item_label">Records</span></a></li>
    <li class="sidebar_menu_item"><a class="sidebar-item" href="/home"><span class="menu_item_icon"><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="image"></span><span class="menu_item_label">Care Coordination</span></a></li>
    </ul>
    </div>
</div>
</div>

<!--
<div class="container no-padding">
<div class="row sidebar_header center">
<div class="col-lg-2 col-md-2">
<div class="dropdown" >
    <button class="dropdown-toggle admin_button" type="button" data-toggle="dropdown" ><img src="{{URL::asset('images/sidebar/care_coordination_small.png')}}">
    <span class="caret"></span></button>
    <ul class="dropdown-menu" >
      <li class="hello"><a href="#"><img src="{{URL::asset('images/sidebar/messages.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/records.png')}}" class="drop_image"></a></li>
      <li><a href="#"><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="drop_image"></a></li>
    </ul>
    </div>
    </div>
    <div class="col-lg-9 col-md-10"> <h3 class="title">Administration</h3></div>
</div>
    <div class="row admin">
        <a class="sidebar-item" href="#">
            <div class="col-lg-6 col-md-6 patients font_change">Patients</div><div class="col-lg-6 col-md-6"><span><img src="{{URL::asset('images/sidebar/patient.png')}}" class="patient"></span>add<span><img src="{{URL::asset('images/sidebar/patient_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin">
        <a class="sidebar-item" href="#">
            <div class="col-lg-6 col-md-6 practice font_change">Practice</div><div class="col-lg-6 col-md-6"><span><img src="{{URL::asset('images/sidebar/practice.png')}}" class="patient"></span>add<span><img src="{{URL::asset('images/sidebar/practice_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin">
        <a class="sidebar-item" href="#">
            <div class="col-lg-6 col-md-6 users font_change">Users</div><div class="col-lg-6 col-md-6"><span><img src="{{URL::asset('images/sidebar/users.png')}}" class="patient"></span>add<span><img src="{{URL::asset('images/sidebar/users_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin">
        <a class="sidebar-item" href="#">
            <div class="col-lg-6 col-md-6 files font_change">Files</div><div class="col-lg-6 col-md-6"><span><img src="{{URL::asset('images/sidebar/files.png')}}" class="patient"></span>add<span><img src="{{URL::asset('images/sidebar/files_add.png')}}" class="image"></span></div></a>
    </div>
</div>
-->
@endif
