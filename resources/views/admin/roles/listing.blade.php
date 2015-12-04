<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-10">
            <p class="subheading">Roles</p>
        </div>
        <div class="col-sm-2">
            {!! Form::button('Add Role', array('type' => 'button', 'data-id' => "admin-create-role" ,'class' => 'button admin-console-item')) !!}
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
                            <th>Role</th>
                            <th class="table-check"></th>
                            <th class="table-check"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Super Admin</th>
                            <th class="table-check"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </th>
                            <th class="table-check"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </th>
                        </tr>
                        <tr>
                            <th>Admin</th>
                            <th class="table-check"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </th>
                            <th class="table-check"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
