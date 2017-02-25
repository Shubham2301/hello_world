@extends('layouts.master') @section('title', 'illuma - File Exchange') @section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/file_exchange.css')}}">
<script type="text/javascript" src="{{elixir('js/file_exchange.js')}}"></script>
@endsection @section('sidebar') @include('file_exchange.sidebar') @endsection @section('content') @if (Session::has('success'))
<div class="alert alert-success" id="flash-message">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

    <strong>
    <i class="fa fa-check-circle fa-lg fa-fw"></i> Success. &nbsp;
    </strong> {{ Session::pull('success') }}
</div>
@endif
<div class="content-section active">
   <span class="files_head_section">
    <div class="row no-margin">
            <input type="hidden" value="{{$openView}}" id='current_view'>
            <input type="hidden" value="{{$isEditable}}" id='parent_editable'>
            <div class="file_exchange_navbar">
                <span class="file_exchange_navbar_content_left">
                    <span class="arial_bold page_title">{{ $active_link['title'] }}</span>
                    <span class="buttons">
                        @if($currentNetwork != -1)
                        <button id="" type="button" class="btn add-btn" data-toggle="modal" data-target="#newfolderModal">Add Folder</button>&nbsp;
                        <button id="" type="button" class="btn add-doc-btn file_input" data-toggle="modal" data-target="#newfileModal">Add Document</button>
                        @endif
                    </span>
                </span>
                <span class="file_exchange_navbar_content_right">
                <span class="file_exchange_button share-button" data-toggle="tooltip" title="Share" data-placement="bottom"><img src="{{elixir('images/sidebar/share-icon.png')}}"></span>
                <span class="file_exchange_button trash-button" data-toggle="tooltip" title="Trash" data-placement="bottom"><img src="{{elixir('images/sidebar/trash-icon.png')}}"></span>
                <span class="file_exchange_button download-button" data-toggle="tooltip" title="Download" data-placement="bottom"><img src="{{elixir('images/sidebar/download-icon.png')}}"></span>
                <span class="file_exchange_button info-button" data-toggle="tooltip" title="Details" data-placement="bottom" id="details"><img src="{{elixir('images/sidebar/details-icon.png')}}"></span>
					<span class="file_exchange_button restore-button" data-toggle="tooltip" title="Restore" data-placement="bottom" id="restore"><img src="{{elixir('images/sidebar/restore-icon.png')}}"></span>
                </span>
            </div>
            @if($currentNetwork != -1)
            <div class="folder_path active">
                <!-- Folder 2 > Subfolder 1 -->
                @if(sizeof($breadcrumbs) > 0 || sizeof($networkList) > 1)
                    <a href="{{ $accessLink }}"> {{ 'All' }}</a>
                    @if(sizeof($networkList) > 1)
                    <span>&nbsp;>&nbsp;</span>
                    <a href="{{ $accessLink }}?network_id={{ $currentNetwork }}"> {{ $networkList[$currentNetwork] }}</a>
                    @endif
                @endif
                @for($i = 0; $i < sizeof($breadcrumbs); $i++)
                <span>&nbsp;>&nbsp;</span>
			    <a href="{{$accessLink}}?id={{ $breadcrumbs[$i]['id'] }}&network_id={{ $currentNetwork }}"> {{ $breadcrumbs[$i]['name'] }}</a>
                @endfor
            </div>
            @endif
    </div>
        @if($currentNetwork != -1)
        <div class="row arial_bold col_title no-margin">
            <div class="col-xs-1 no-padding"></div>
            <div class="col-xs-7 no-padding">Name</div>
            <div class="col-xs-2 no-padding">Modified by</div>
            <div class="col-xs-2 no-padding">Date Modified</div>
        </div>
        <hr class="main">
        @endif
    </span>
    @if($currentNetwork != -1 && $empty == 'false')
    <div class="files">
     @foreach($folderlist as $folder)
        <div class="row arial col_content no-margin">
            <div class="col-xs-1 no-padding" style="text-align: center;">
                <input type="checkbox" class="checkbox file-exchange folder-check" style="display: inline;" data-id="{{ $folder['id'] }}" data-name="folder">
            </div>
            <div class="col-xs-7 no-padding">
				@if($openView != 'trash')
				<a href="{{$accessLink}}?id={{$folder['id']}}&network_id={{$currentNetwork}}"><img src="{{ URL::asset('images/sidebar/folder-white.png') }}" style="margin:0 0.5em 0.25em 0.25em"><span id="{{ $folder['id'] }}_folder_name">{{ $folder['name'] }}</span></a>
				@else
				<a href="#" data-id="{{$folder['id']}}" class="restore_item"><img src="{{ URL::asset('images/sidebar/folder-white.png') }}" style="margin:0 0.5em 0.25em 0.25em"><span id="{{$folder['id'] }}_folder_name">{{ $folder['name'] }}</span></a>
				@endif
            </div>
            <div class="col-xs-2 no-padding">{{ $folder['modified_by'] }}</div>
            <div class="col-xs-2 no-padding">{{ $folder['updated_at'] }}</div>
<!--            <div class="row">-->
                @if(strlen($folder['description']) > 90)
                <div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $folder['id'] }}_folder" style="cursor:pointer;" data-clickable="true">
                    <p style="margin-left: 8%;">{{ $folder['description'] }}</p>
                </div>

                @elseif(strlen($folder['description']) > 0)
                <div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $folder['id'] }}_folder">
                    <p style="margin-left: 8%;">{{ $folder['description'] }}</p>
                </div>
                @endif
<!--            </div>-->
        </div>
        <hr> @endforeach @foreach($filelist as $file)
        <div class="row arial col_content no-margin">
            <div class="col-xs-1 no-padding" style="text-align: center;">
                <input type="checkbox" class="checkbox file-exchange file-check" name="checkbox" style="display: inline;" data-id="{{ $file['id'] }}" data-name="file">
            </div>
			<div class="col-xs-7 no-padding"><img src="{{URL::asset('images/sidebar/files-white.png')}}" style="margin:0 0.5em 0.25em 0.25em"><span id="{{ $file['id'] }}_file_name">{{ $file['name'] }}</span></div>
            <div class="col-xs-2 no-padding">{{ $file['modified_by'] }}</div>
            <div class="col-xs-2 no-padding">{{ $file['updated_at'] }}</div>
<!--            <div class="row">-->
                @if(strlen($file['description']) > 90)
				<div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $file['id'] }}_file" style="cursor:pointer;" data-clickable="true">
                    <p style="margin-left: 8%;">{{ $file['description'] }}</p>
                </div>

                @elseif(strlen($file['description'] ) > 0)
                <div class="col-xs-7 col-xs-offset-1 no-padding description_text arial_italic" id="{{ $file['id'] }}_file">
                    <p style="margin-left: 8%;">{{ $file['description'] }}</p>
                </div>
                @endif
<!--            </div>-->
        </div>
        <hr> @endforeach
    </div>
    @endif
    <div class="item_info" id="item_info">
    </div>
    @if($currentNetwork != -1)
    @include('file_exchange.addFolder')
    @include('file_exchange.addFile')
    @include('file_exchange.share')
    @if($empty == 'true')
    <div style="display: flex;flex-direction: row;justify-content: center;">
        <span>No files or folder found</span>
    </div>
    @endif
    @endif
    @if($currentNetwork == -1)
        <div class="select_network">
        <h4 class="arial_bold">Select a network to add files and folders</h4>
        <ul>
        @foreach ( $networkList as $id => $networkName)
            <li><a href="{{ $accessLink }}?network_id={{ $id }}">{{ $networkName }}</a></li>
        @endforeach
        </ul>
        </div>
    @endif
</div>


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
