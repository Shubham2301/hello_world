<html>

    <body>

    {!! Form::open(array('url' => '/saveccd', 'method' => 'post','files'=>true)) !!}

    {!!Form::file('ccda')!!}
    {!!Form::submit('save')!!}

    {!! Form::close() !!}


    </body>


</html>
