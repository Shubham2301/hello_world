@if(!$data['admin'])
<div class="row content-row-margin-scheduling">
   <div class="search_bar">
    <div class="col-xs-10 search_input">
        <input type="text" class="" id="search_patient_input">
        <span class="glyphicon glyphicon-search" id="search_patient_button" aria-hidden="true"></span>
        <span class="glyphicon glyphicon-plus-sign add_search_option" id="add_search_option" aria-hidden="true"></span>

    </div>
    <div class="col-xs-2 search_dropdown">
        <!-- TODO: Create custom dropdown and write css in style.less and write js in main.js -->
        <select type="text" class="" id="search_patient_input_type">
            <option value="all">All</option>
            <option value="name">Name</option>
            <option value="ssn">SSN</option>
            <option value="email">Email</option>
            <option value="phone">Phone</option>
            <option value="address">Address</option>
        </select>
    </div>
    </div >
    <div class="col-xs-12 search_filter">
        <!--
        <div class="search_filter_item">
           <span class="item_type">name</span>:
            <span class="item_value">Nishanth</span>
            <span class="remove_option">x</span>
        </div>
    -->

    </div>
</div>
@else
<div class="content-section active side_padding">
    <div class="row content-row-margin top_margin patient_admin_index_header active">
        <div class="patient_admin_search">
            <p class="page_title arial_bold">Patients</p>
            <button id="open_patient_form" type="button" class="btn add-btn" >Add New</button>
            <span class="search_input_box">
                <input type="text" class="arial_italic" id="search_patient_input" placeholder="search">
                <img src="{{asset('images/search-icon.png')}}" class="admin_seacrh_icon" id="search_patient_button">
            </span>
            <span class="glyphicon glyphicon-remove" id="refresh_patients" area-hidden="true"></span>
            <span class="admin_delete" data-toggle="tooltip" title="Delete Patients" data-placement="top">
                <img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}">
                <img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}">
            </span>
            <button type="button" data-toggle="modal" data-target="#importModal"  class="btn import-btn open_import">Import</button>
            <input type="hidden" id="clear_image_path" value="{{URL::asset('images/close-active.png')}}">
        </div>
        <p id="search_results" class="search_result"></p>
        <div class="row search_header arial top_margin_large">
            <div class="col-xs-3 search_name_header">
                <div>
                    <input id="checked_all_patients" type="checkbox">&nbsp;&nbsp;</div>
                    <p style="color:#333">Name</p>
            </div>
            <div class="col-xs-4">
                <p style="color:#333">Address</p>
            </div>
            <div class="col-xs-3">
                <p style="color:#333">Email</p>
            </div>
            <div class="col-xs-2">
                <input type="hidden" id="delete_practice_img" value="{{asset('images/delete-natural-hover.png')}}">
                <input type="hidden" id="schedule_patient_img" value="{{asset('images/schedule-icon-01.png')}}">
    <!--            <p class="" style="color:#333"><span class="glyphicon glyphicon-chevron-left p_left" id="search_practice_button" aria-hidden="true"></span> <span class="page_info"></span><span class="glyphicon glyphicon-chevron-right p_right" id="search_practice_button" aria-hidden="true"></span></p>-->
            </div>
        </div>
    </div>

    <div class="row content-row-margin patient_admin_back">
        <div class="col-xs-2">
            <button type="button" class="btn back patient_back" >Back</button>
        </div>
        <div class="col-xs-10">

        </div>
    </div>
</div>
@endif
