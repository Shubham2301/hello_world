<div class="row content-row-margin">
    <div class="form-group">
        <div class="col-sm-12">
            <h3>Define Permissions</h3>
        </div>
    </div>
</div>
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
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <colgroup>
                            <col class="col-xs-1">
                            <col class="col-xs-11">
                        </colgroup>
                        <thead class="table-header">
                            <tr>
                                <th class="table-check">{!! Form::checkbox('name','value') !!}</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="table-check">{!! Form::checkbox('name','value') !!}</th>
                                <th>Permission 1</th>
                            </tr>
                            <tr>
                                <th class="table-check">{!! Form::checkbox('name','value') !!}</th>
                                <th>Permission 2</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

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
