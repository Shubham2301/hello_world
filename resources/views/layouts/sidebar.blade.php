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
</div>
@endif
