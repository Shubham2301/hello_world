<div class="no-padding">
<div class="row sidebar_header center">
<div class="col-lg-2 col-md-2">
<div class="dropdown" >
    <button class="dropdown-toggle admin_button" type="button" data-toggle="dropdown" ><img src="{{URL::asset('images/sidebar/care_coordination_small.png')}}">
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
    <div class="col-lg-9 col-md-10"> <h3 class="title"> Administration</h3></div>
</div>
    <div class="row admin @yield('patients-active')">
        <a class="sidebar-item" href="/administration/patients">
            <div class="col-lg-6 col-md-6 patients font_change">Patients</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/patient.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/patient_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin @yield('practices-active')">
        <a class="sidebar-item" href="/administration/practices">
            <div class="col-lg-6 col-md-6 users font_change">Practices</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/users.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/users_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin @yield('providers-active')">
        <a class="sidebar-item" href="/administration/providers">
            <div class="col-lg-6 col-md-6 users font_change">Providers</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/users.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/users_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin @yield('users-active')">
        <a class="sidebar-item" href="/administration/users">
            <div class="col-lg-6 col-md-6 practice font_change">Users</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/practice.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/practice_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin @yield('roles-active')">
        <a class="sidebar-item" href="/administration/roles">
            <div class="col-lg-6 col-md-6 files font_change">Roles</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/files.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/files_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin @yield('roles-active')">
        <a class="sidebar-item" href="/administration/networks">
            <div class="col-lg-6 col-md-6 files font_change">Networks</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/files.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/files_add.png')}}" class="image"></span></div></a>
    </div>
    <div class="row admin @yield('permissions-active')">
        <a class="sidebar-item" href="/administration/permissions">
            <div class="col-lg-6 col-md-6 files font_change">Permissions</div><div class="col-lg-6 col-md-6 sidebar-item-left"><span><img src="{{URL::asset('images/sidebar/files.png')}}" class="patient"></span>&nbsp;add<span><img src="{{URL::asset('images/sidebar/files_add.png')}}" class="image"></span></div></a>
    </div>
</div>
