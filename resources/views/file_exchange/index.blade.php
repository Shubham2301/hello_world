@extends('layouts.master') @section('title', 'My Ocuhub - File Exchange') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/file_exchange.css')}}">
<script type="text/javascript" src="{{elixir('js/file_exchange.js')}}"></script>
@endsection @section('sidebar') @include('file_exchange.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

    <strong>
    <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
    </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="content-section active" style="min-height: 40vh;">
    <div class="row no-margin">
        <div class="col-xs-offset-1">
            <input type="hidden" value="{{$openView}}" id='current_view'>
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
					<span class="file_exchange_button restore-button" data-toggle="tooltip" title="Restore" data-placement="bottom" id="restore"><img src="{{URL::asset('images/sidebar/restore-icon.png')}}" style="width:30px;"></span>
                </span>
            </div>
            <div class="folder_path active">
                <!-- Folder 2 > Subfolder 1 -->
                @if(sizeof($breadcrumbs)>0)
                <a href="{{$accessLink}}"> {{ 'All' }}</a> @endif @for($i = 0; $i
                < sizeof($breadcrumbs); $i++) <span>&nbsp;>&nbsp;</span>
			<a href="{{$accessLink}}?id={{ $breadcrumbs[$i]['id'] }}"> {{ $breadcrumbs[$i]['name'] }}</a> @endfor
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
        <hr class="main"> @foreach($folderlist as $folder)
        <div class="row arial col_content no-margin">
            <div class="col-xs-1" style="text-align: center;">
                <input type="checkbox" class="checkbox file-exchange folder-check" style="display: inline;" data-id="{{ $folder['id'] }}" data-name="folder">
            </div>
            <div class="col-xs-7 no-padding">
				@if($openView != 'trash')
				<a href="{{$accessLink}}?id={{$folder['id']}}"><img src="{{ URL::asset('images/folder-white.png') }}" style="width: 2em;margin:0 0.5em 0.25em 0.25em"><span id="{{ $folder['id'] }}_folder_name">{{ $folder['name'] }}</span></a>
				@else
				<a href="#" data-id="{{$folder['id']}}" class="restore_item"><img src="{{ URL::asset('images/folder-white.png') }}" style="width: 2em;margin:0 0.5em 0.25em 0.25em"><span id="{{$folder['id'] }}_folder_name">{{ $folder['name'] }}</span></a>
				@endif
            </div>
            <div class="col-xs-2 no-padding">{{ $folder['modified_by'] }}</div>
            <div class="col-xs-2 no-padding">{{ $folder['updated_at'] }}</div>
            <div class="row">
                @if(strlen($folder['description']) > 90)
                <div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $folder['id'] }}_folder" style="cursor:pointer;" data-clickable="true">
                    <p style="margin-left: 8%;">{{ $folder['description'] }}</p>
                </div>

                @elseif(strlen($folder['description']) > 0)
                <div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $folder['id'] }}_folder">
                    <p style="margin-left: 8%;">{{ $folder['description'] }}</p>
                </div>
                @endif
            </div>
        </div>
        <hr> @endforeach @foreach($filelist as $file)
        <div class="row arial col_content no-margin">
            <div class="col-xs-1" style="text-align: center;">
                <input type="checkbox" class="checkbox file-exchange file-check" name="checkbox" style="display: inline;" data-id="{{ $file['id'] }}" data-name="file">
            </div>
			<div class="col-xs-7 no-padding"><img src="{{URL::asset('images/files-white.png')}}" style="width: 2em;margin:0 0.5em 0.25em 0.25em"><span id="{{ $file['id'] }}_file_name">{{ $file['name'] }}</span></div>
            <div class="col-xs-2 no-padding">{{ $file['modified_by'] }}</div>
            <div class="col-xs-2 no-padding">{{ $file['updated_at'] }}</div>
            <div class="row">
                @if(strlen($file['description']) > 90)
				<div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $file['id'] }}_file" style="cursor:pointer;" data-clickable="true">
                    <p style="margin-left: 8%;">{{ $file['description'] }}</p>
                </div>

                @elseif(strlen($file['description'] ) > 0)
                <div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $file['id'] }}_file">
                    <p style="margin-left: 8%;">{{ $file['description'] }}</p>
                </div>
                @endif
            </div>
        </div>
        <hr> @endforeach
    </div>
    <div class="item_info" id="item_info">
    </div>
</div>
@include('file_exchange.addFolder') @include('file_exchange.addFile') @include('file_exchange.share') @if($empty == 'true')
<div style="display: flex;flex-direction: row;justify-content: center;">
    <span>No files or folder found</span>
</div>

@endif
{!! Form::open(array('url' => 'deleteFilesFolders', 'method' => 'POST', 'id'=>'delete_files_folders')) !!}
<input type="hidden" name="delete_folders" id="delete_folders" value="">
<input type="hidden" name="delete_files" id="delete_files" value="">
{!! Form::close()!!}

{!! Form::open(array('url' => 'restoreFilesFolders', 'method' => 'POST', 'id'=>'restore_files_folders')) !!}
<input type="hidden" name="restore_folders" id="restore_folders" value="">
<input type="hidden" name="restore_files" id="restore_files" value="">
{!! Form::close()!!}
@endsection
@section('mobile_sidebar_content')
@include('file_exchange.sidebar')
@endsection
