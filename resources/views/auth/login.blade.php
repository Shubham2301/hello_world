<!-- resources/views/auth/login.blade.php -->

@if (count($errors) > 0)
    <div class="row content-row-margin alert alert-danger">
            <p>{{ $errors->first() }}</p>
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
            <p class="white-text">Forgot password or username?</p>
        </div>
    </div>
    
</form>




