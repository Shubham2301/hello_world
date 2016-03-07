@extends('layouts.master') @section('title', 'My Ocuhub - File Exchange') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/file_exchange.css')}}">
<script type="text/javascript" src="{{elixir('js/file_exchange.js')}}"></script>
@endsection
@section('sidebar')
@include('file_exchange.sidebar')
@endsection
@section('content')
@if (Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>
    <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
    </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="content-section active">
    <div class="row">
        <div class="col-xs-offset-1">
            <div class="file_exchange_navbar">
                <span class="file_exchange_navbar_content_left">
                    <button id="" type="button" class="btn add-btn" data-toggle="modal" data-target="#newfolderModal">Add Folder</button>&nbsp;
                    <button id="" type="button" class="btn add-doc-btn file_input" data-toggle="modal" data-target="#newfileModal">Add Document</button>
                </span>
                <span class="file_exchange_navbar_content_right">
                    <span class="file_exchange_button share-button" data-toggle="tooltip" title="Share" data-placement="bottom"><img src="{{URL::asset('images/sidebar/share-icon.png')}}" style="width:30px;"></span>
                    <span class="file_exchange_button trash-button" data-toggle="tooltip" title="Trash" data-placement="bottom"><img src="{{URL::asset('images/sidebar/trash-icon.png')}}" style="width:30px;"></span>
                    <span class="file_exchange_button download-button" data-toggle="tooltip" title="Download" data-placement="bottom"><img src="{{URL::asset('images/sidebar/download-icon.png')}}" style="width:30px;"></span>
                    <span class="file_exchange_button info-button" data-toggle="tooltip" title="Details" data-placement="bottom" id="details"><img src="{{URL::asset('images/sidebar/details-icon.png')}}" style="width:30px;"></span>
                </span>
            </div>
            <div class="folder_path active">
                <!-- Folder 2 > Subfolder 1 -->
                @for($i = 0; $i< sizeof($breadcrumbs); $i++)
                @if($i !== 0)
                <span>&nbsp;>&nbsp;</span>
                @endif
                <a href="/file_exchange?id={{ $breadcrumbs[$i]['id'] }}"> {{ $breadcrumbs[$i]['name'] }}</a>
                @endfor
            </div>
        </div>
    </div>
    <div class="files">
        <div class="row arial_bold col_title">
            <div class="col-xs-1"></div>
            <div class="col-xs-7 no-padding">Name</div>
            <div class="col-xs-2 no-padding">Modified by</div>
            <div class="col-xs-2 no-padding">Date Modified</div>
        </div>
        <hr class="main">
        @foreach($folderlist as $folder)
        <div class="row arial col_content">
            <div class="col-xs-1" style="text-align: center;">
                <input type="checkbox" class="checkbox file-exchange folder-check" style="display: inline;" data-id="{{ $folder['id'] }}">
            </div>
            <div class="col-xs-7 no-padding">
                <a href="file_exchange?id={{$folder['id']}}"><img src="{{ URL::asset('images/folder-white.png') }}" style="width: 2em;margin:0 0.5em 0.25em 0.25em">{{ $folder['name'] }}</a>
            </div>
            <div class="col-xs-2 no-padding">{{ $folder['modified_by'] }}</div>
            <div class="col-xs-2 no-padding">{{ $folder['updated_at'] }}</div>
            <div class="col-xs-11 col-xs-offset-1 no-padding description_text arial_italic" >{{ $folder['description'] }}</div>
        </div>
        <hr>
        @endforeach
        @foreach($filelist as $file)
        <div class="row arial col_content">
            <div class="col-xs-1" style="text-align: center;">
                <input type="checkbox" class="checkbox file-exchange file-check" style="display: inline;" data-id="{{ $file['id'] }}">
            </div>
            <div class="col-xs-7 no-padding"><img src="{{URL::asset('images/files-white.png')}}" style="width: 2em;margin:0 0.5em 0.25em 0.25em">{{ $file['name'] }}</div>
            <div class="col-xs-2 no-padding">{{ $file['modified_by'] }}</div>
            <div class="col-xs-2 no-padding">{{ $file['updated_at'] }}</div>
            <div class="col-xs-11 col-xs-offset-1 no-padding description_text arial_italic" >{{ $file['description'] }}</div>
        </div>
        <hr>
        @endforeach
    </div>
    <div class="item_info">
        <span class="title arial_bold">
            <span>Folder 1</span>
            <span class="glyphicon glyphicon-remove" id="close_item_info"></span>
        </span>
        <br>
        <span class="modifications arial_bold">
            Modifications
        </span>
        <br>
        <span class="modification_history">
            <span>Me</span>
            <span>16-Feb-2016</span>
        </span>
        <span class="modification_history">
            <span>Eric Hoell</span>
            <span>10-Feb-2016</span>
        </span>
        <span class="modification_history">
            <span>John Doe</span>
            <span>26-Jan-2016</span>
        </span>
        <br>
        <span class="modifications arial_bold">
            Edit Description
        </span>
        <br>
        <span class="description arial_italic">
            Description is a really important part of this segment, more important than the file name maybe. One line of the description will be shown on opening the portalbut by clicking on that, the complete description will be available.
        </span>
    </div>
</div>
@include('file_exchange.addFolder')
@include('file_exchange.addFile')
@include('file_exchange.share')
@endsection