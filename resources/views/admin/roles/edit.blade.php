@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('content')
    <div class="content-section active" id="admin-role-console">

        <div class="admin-console-section active" id="admin-create-role">
            <div class="row content-row-margin">
                <div class="form-group">
                    <div class="col-sm-12">
                        <h3>Edit Role</h3>
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

            {!! Form::open(array('url' => '/administration/roles', 'method' => 'PUT')) !!}

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
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <colgroup>
                                        <col class="col-xs-11">
                                        <col class="col-xs-1">
                                    </colgroup>
                                    <thead class="table-header">
                                        <tr>
                                            <th>Permissions</th>
                                            <th class="table-check"><!-- {!! Form::checkbox('permission_all','value') !!} --></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                        <tr>
                                            <th>{{ $permission->display_name }}</th>
                                            <th class="table-check">{!! Form::checkbox('permissions[]', $permission->id) !!}</th>
                                        </tr>
                                        @endforeach
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
         </div>
     </div>
@endsection
