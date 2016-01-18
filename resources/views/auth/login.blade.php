<!-- resources/views/auth/login.blade.php -->

@if (count($errors) > 0)
    <div class="row content-row-margin alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class="error">Email and password do not match. Please try again.</p>
            @endforeach
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
            {!! Form::email('email', old('email'), array('class' => 'input', 'placeholder' => 'EMAIL/USERNAME', 'id' => 'password')) !!}
        </div>
    </div>
    
    <div class="row content-row-margin">
        <div class="col-sm-12">
            {!! Form::password('password', array('class' => 'input', 'placeholder' => 'PASSWORD', 'id' => 'password')) !!}
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




