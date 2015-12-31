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
<!--sidebar begin-->
@if(Auth::check())
<div class="section_1 center">
    <h3 class="title"><span><img src="{{URL::asset('images/sidebar/care_coordination_small.png')}}" class="image"></span>Care Coordination</h3>
</div>
<div class="section_2 center"><h4>Stephen Kendig</h4>

</div>
<div class="section_3 center">
<img src="{{URL::asset('images/sidebar/care_coordinator.png')}}">
</div>
<div class="container"><div class="row"><div class="col-lg-offset-2">
<ul class="sidebar-item-list">
    <li class="section_4"><a class="sidebar-item" href="#"><span><img src="{{URL::asset('images/sidebar/messages.png')}}" class="image"></span>Messages</a></li>
    <li class="section_4"><a class="sidebar-item" href="#"><span><img src="{{URL::asset('images/sidebar/file_update.png')}}" class="image"></span>File Update</a></li>
    <li class="section_4"><a class="sidebar-item" href="#"><span><img src="{{URL::asset('images/sidebar/announcements.png')}}" class="image"></span>Announcements</a></li>
    <li class="section_4"><a class="sidebar-item" href="#"><span><img src="{{URL::asset('images/sidebar/schedule.png')}}" class="image"></span>Schedule a Patient</a></li>
    <li class="section_4"><a class="sidebar-item" href="#"><span><img src="{{URL::asset('images/sidebar/records.png')}}" class="image"></span>Records</a></li>
    <li class="section_4"><a class="sidebar-item" href="#"><span><img src="{{URL::asset('images/sidebar/care_coordination.png')}}" class="image"></span>Care Coordination</a></li>
    </ul>
    </div>
</div>
</div>
@endif

<!--sidebar end-->
