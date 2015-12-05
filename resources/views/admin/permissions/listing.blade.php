<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-10">
            <p class="subheading">Permissions</p>
        </div>
        <div class="col-sm-2">
            <a href="/permissions/create" class="button">Add Permissions</a>
        </div>
    </div>
</div>
<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table">
                    <colgroup>
                        <col class="col-xs-10">
                        <col class="col-xs-1">
                        <col class="col-xs-1">
                    </colgroup>
                    <thead class="table-header">
                        <tr>
                            <th>Permission</th>
                            <th class="table-check"></th>
                            <th class="table-check"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <th>{{ $permission->display_name }}</th>
                            <th class="table-check"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </th>
                            <th class="table-check"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>        
                </table>
            </div>

        </div>
    </div>
</div>
