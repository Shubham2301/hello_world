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

    <div>
        User Type
        <input type="text" name="usertype" value="{{ old('usertype') }}">
    </div>

    <div>
        Title*
        <input type="text" name="title" value="{{ old('title') }}">
    </div>

    <div>
        First Name*
        <input type="text" name="firstname" value="{{ old('firstname') }}">
    </div>

    <div>
        Middle Name
        <input type="text" name="middlename" value="{{ old('middlename') }}">
    </div>

    <div>
        Last Name*
        <input type="text" name="lastname" value="{{ old('lastname') }}">
    </div>

    <div>
        Email*
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        NPI*
        <input type="text" name="npi" value="{{ old('npi') }}">
    </div>

    <div>
        Cell Phone
        <input type="text" name="cellphone" value="{{ old('celphone') }}">
    </div>

    <div>
        Direct Address
        <input type="email" name="sesemail" value="{{ old('sesemail') }}">
    </div>

    <div>
        Calendar
        <input type="checkbox" name="calendar" value="{{ old('calendar') }}">
    </div>  

    <div>
        Address1
        <input type="text" name="address1" value="{{ old('address1') }}">
    </div>

    <div>
        Address2
        <input type="text" name="address2" value="{{ old('address2') }}">
    </div>

    <div>
        City
        <input type="text" name="city" value="{{ old('city') }}">
    </div>

    <div>
        State
        <input type="text" name="state" value="{{ old('state') }}">
    </div>

    <div>
        Zip
        <input type="text" name="zip" value="{{ old('zip') }}">
    </div>

    <div>
        Password
        <input type="password" name="password">
    </div>

    <div>
        Confirm Password
        <input type="password" name="password_confirmation">
    </div>

    <div>
        Role Name
        <input type="role" name="role">
    </div>

    <div>
        <button type="submit">Save</button>
    </div>
</form>