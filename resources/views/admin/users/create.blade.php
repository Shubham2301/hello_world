@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <strong>Whoops! Something went wrong!</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/users">
    {!! csrf_field() !!}
    {{ method_field('POST') }}

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('usertype', 'User Type') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('usertype', old('usertype'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('title', 'Title*') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('title', old('title'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('firstname', 'First Name*') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('firstname', old('firstname'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('middlename', 'Middle Name') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('middlename', old('middlename'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('lastname', 'Last Name*') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('lastname', old('lastname'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('email', 'Email*') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::email('email', old('email'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('npi', 'NPI*') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('npi', old('npi'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('cellphone', 'Cell Phone') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('cellphone', old('cellphone'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('sesemail', 'Direct Address') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::email('sesemail', old('sesemail'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('calendar', 'Calendar') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::checkbox('calendar', old('calendar')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('address1', 'Address 1') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('address1', old('address1'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('address2', 'Address 2') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('address2', old('address2'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('city', 'City') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('city', old('city'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('zip', 'Zip') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('zip', old('zip'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('password', 'Password') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::password('password', old('password'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>


    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('password_confirmation', 'Confirm Password') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::password('password_confirmation', old('password_confirmation'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('role', 'Role Name') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('role', old('role'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::submit('Save', array('class' => 'button')) !!}
            </div>
            <div class="col-sm-10"></div>
        </div>
    </div>

</form>
