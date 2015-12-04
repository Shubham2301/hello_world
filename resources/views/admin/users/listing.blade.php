<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-10">
            <p class="subheading">Users</p>
        </div>
        <div class="col-sm-2">
            {!! Form::button('Add User', array('type' => 'button', 'data-id' => "admin-create-user" ,'class' => 'button admin-console-item')) !!}
        </div>
    </div>
</div>
<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table">
                    <colgroup>
                        <col class="col-xs-2">
                        <col class="col-xs-3">
                        <col class="col-xs-3">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                    </colgroup>
                    <thead class="table-header">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organization</th>
                            <th>User Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <th>{{ $user->title }} {{ $user->firstname }} {{ $user->lastname }}</th>
                            <th>{{ $user->email }}</th>
                            <th>Ocuhub LLC. <br><small>Sushant Lok 1</small></th>
                            <th>Provider</span></th>
                            <th>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
