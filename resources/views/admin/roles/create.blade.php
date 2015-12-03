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

{!! Form::open(array('url' => 'foo/bar', 'method' => 'POST')) !!}

    {!! csrf_field() !!}
    {{ method_field('POST') }}

   <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('name', 'Name') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('name', old('name'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>
    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('display_name', 'Display Name') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('display_name', old('display_name'), array('class' => 'input')) !!}
            </div>
        </div>
    </div>

    <div class="row content-row-margin">
        <div class="form-group">
            <div class="col-sm-2">
                {!! Form::label('description', 'Description') !!}
            </div>
            <div class="col-sm-10">
                {!! Form::text('description', old('description'), array('class' => 'input')) !!}
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

{!! Form::close() !!}
