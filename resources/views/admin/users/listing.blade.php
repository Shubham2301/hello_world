<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-10">
            <p class="subheading">Users</p>
        </div>
        <div class="col-sm-2">
            <a href="/administration/users/create" class="button">Add User</a>
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
                            <th>NPI</th>
                            <th>User Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <th>{{ $user->title }} {{ $user->firstname }} {{ $user->lastname }}</th>
                            <th>{{ $user->email }}</th>
                            <th>{{ $user->npi }}</small></th>
                            <th>{{ $user->usertype['name'] }}</span></th>
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
