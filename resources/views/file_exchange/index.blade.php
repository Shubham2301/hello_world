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
            <button id="" type="button" class="btn add-doc-btn file_input">Add Document</button>
        </span>
                <span class="file_exchange_navbar_content_right">
            <span class="file_exchange_button" data-toggle="tooltip" title="Share" data-placement="bottom"><img src="{{URL::asset('images/sidebar/share-icon.png')}}" style="width:30px;"></span>
                <span class="file_exchange_button" data-toggle="tooltip" title="Trash" data-placement="bottom"><img src="{{URL::asset('images/sidebar/trash-icon.png')}}" style="width:30px;"></span>
                <span class="file_exchange_button" data-toggle="tooltip" title="Download" data-placement="bottom"><img src="{{URL::asset('images/sidebar/download-icon.png')}}" style="width:30px;"></span>
                <span class="file_exchange_button" data-toggle="tooltip" title="Details" data-placement="bottom" id="details"><img src="{{URL::asset('images/sidebar/details-icon.png')}}" style="width:30px;"></span>
                </span>
            </div>
            <div class="folder_path active">
                Folder 2 > Subfolder 1
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
        <div class="row arial col_content">
            <div class="col-xs-1">
                <input type="checkbox" class="checkbox file-exchange">
            </div>
            <div class="col-xs-7 no-padding" id="item_name">Folder 1</div>
            <div class="col-xs-2 no-padding" id="item_last_change_author">Eric Hoell</div>
            <div class="col-xs-2 no-padding" id="item_last_change_date">02/20/2016</div>
            <div class="col-xs-11 col-xs-offset-1 no-padding description_text arial_italic">Description is a really important part of this segment, more important than the file name maybe. One line of the description will be shown on opening the portalbut by clicking on that, the complete description will be available.</div>
        </div>
        <hr>
        <div class="row arial col_content">
            <div class="col-xs-1">
                <input type="checkbox" class="checkbox file-exchange">
            </div>
            <div class="col-xs-7 no-padding">Folder 2</div>
            <div class="col-xs-2 no-padding">John Doe</div>
            <div class="col-xs-2 no-padding">02/17/2016</div>
            <div class="col-xs-11 col-xs-offset-1 no-padding description_text arial_italic" >Description is a really important part of this segment, more important than the file name maybe. One line of the description will be shown on opening the portalbut by clicking on that, the complete description will be available.</div>
        </div>
        <hr>
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
@include('file_exchange.add')

@endsection
