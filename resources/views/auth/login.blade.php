<!-- resources/views/auth/login.blade.php -->

<form method="POST" action="/auth/login">
    {!! csrf_field() !!}
    
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <p class="white-text">Please sign in </p>
        </div>
    </div>
    
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="EMAIL/USERNAME" class="input white-border input-text white-bg">
        </div>
    </div>
    
    <div class="row content-row-margin">
        <div class="col-sm-12">
            <input type="password" name="password" id="password" placeholder="PASSWORD" class="input white-border input-text white-bg">
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="col-sm-12">
            <button class="white-border signin-button white-border white-text" type="submit">SIGN IN</button>
        </div>
    </div> 

    <div class="row content-row-margin">
        <div class="col-sm-12">
            <input type="checkbox" name="remember" class="white-bg">&nbsp;Remember me on this computer<br> 
        </div>
    </div> 
    
    <div class="row content-row-margin">
        <div class="col-sm-12 bottom-margin">
            <p class="white-text">Forgot password or username?</p>
        </div>
    </div>
    
</form>




