<!-- resources/views/auth/login.blade.php -->

@if (count($errors) > 0)
    <div class="row content-row-margin alert alert-danger" style="margin: 1em 15px;">
            <p>{{ $errors->first() }}</p>
            <p>If you need help please contact us via email: <span class="arial_bold">support@ocuhub.com</span> or telephone:  <span class="arial_bold">844-605-8243</span></p>
    </div>
@endif

<form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div class="row content-row-margin">
        <div class="col-sm-12">
            <p class="white-text">Please sign in </p>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::email('email', old('email'), array('class' => 'login_form_input', 'name' => 'email', 'placeholder' => 'EMAIL/USERNAME', 'id' => 'email')) !!}
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::password('password', array('class' => 'login_form_input', 'name' => 'password','placeholder' => 'PASSWORD', 'id' => 'password')) !!}
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::submit('SIGN IN', array('class' => 'button')) !!}
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::checkbox('remember') !!}&nbsp;Remember me on this computer<br>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="col-sm-12 bottom-margin">
            <a href="password/email"><p class="white-text">Forgot password or username?</p></a>
        </div>
    </div>

</form>




