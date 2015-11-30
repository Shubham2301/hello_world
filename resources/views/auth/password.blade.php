<!-- resources/views/auth/password.blade.php -->

<form method="POST" action="/password/email">
    {!! csrf_field() !!}

    @if (count($errors) > 0)
    <div class="row">
        @foreach ($errors->all() as $error)
        <div class="col-sm-12 left-padding top-margin">
            <p class="white-text">{{ $error }}</p>
        </div>
        @endforeach
    </div>
    @endif
    
    <div class="row">
        <div class="col-sm-12 left-padding box-margin">
            <input type="email" name="email" placeholder="EMAIL" value="{{ old('email') }}" class="input white-border input-text white-bg">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 left-padding box-margin">
            <button type="submit" class="white-border signin-button white-border white-text" type="submit">Send Password Reset Link</button>
        </div>
    </div> 
    
</form>




