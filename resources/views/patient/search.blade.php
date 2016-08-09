@if(!$data['admin'])
<div class="row content-row-margin-scheduling">
    <div class="search_bar">
        <div class="col-xs-10 search_input">
            <input type="text" class="" id="search_patient_input">
            <!--        <span class="glyphicon glyphicon-search" id="search_patient_button" aria-hidden="true"></span>-->
            <img src="{{elixir('images/sidebar/search-icon-schedule.png')}}" id="search_patient_button">
            <span class="glyphicon glyphicon-plus-sign add_search_option" id="add_search_option" aria-hidden="true"></span>

        </div>
        <div class="col-xs-2 search_dropdown">
            <!-- TODO: Create custom dropdown and write css in style.less and write js in main.js -->
            <!--
        <select type="text" class="" id="search_patient_input_type">
            <option value="all">All</option>
            <option value="name">Name</option>
            <option value="ssn">SSN</option>
            <option value="email">Email</option>
            <option value="phone">Phone</option>
            <option value="address">Address</option>
        </select>
-->
            <div class="dropdown"><span data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="custom_dropdown"><span id="search_patient_input_type" value="all">All</span><img src="images/sidebar/triangle-down.png" class="custom_dropdown_img_search"></span>
                </span>
                <ul class="dropdown-menu" id="custom_dropdown">
                    <li value="all">All</li>
                    <li value="name">Name</li>
                    <li value="ssn">SSN</li>
                    <li value="email">Email</li>
                    <li value="phone">Phone</li>
                    <li value="address">Address</li>
                    <li value="subscriber_id">Subscriber ID</li>
                </ul>
            </div>
        </div>
    </div>
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
<div id="model_4pc_view"></div>
@else
<div class="content-section active side_padding">
    <div class="row content-row-margin top_margin patient_admin_index_header active">
        <div class="patient_admin_search">
            <p class="page_title arial_bold">Patients</p>
            @can('add-patient')
            <button id="open_patient_form" type="button" class="btn add-btn">Add New</button>
            @endcan
            <span class="search_input_box">
                <input type="text" class="arial_italic" id="search_patient_input" placeholder="search" data-toggle="tooltip" title="Search using patient name, phone number, SSN, address, email, country or Insurance Subscriber ID" data-placement="bottom">
                <img src="{{asset('images/sidebar/search-icon.png')}}" class="admin_seacrh_icon" id="search_patient_button">
            </span>
            <img src="{{URL::asset('images/close-white-small.png')}}" id="refresh_patients">
            <span class="admin_delete" data-toggle="tooltip" title="Delete Patients" data-placement="top">
                <img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}">
                <img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}">
            </span> @can('bulk-import')
            <button type="button" data-toggle="modal" data-target="#importModal" class="btn import-btn open_import">Import</button>
            @endcan

			<input type="hidden" id="triangle_up_image_path" value="{{URL::asset('images/triangle-up.png')}}">
			<input type="hidden" id="triangle_down_image_path" value="{{URL::asset('images/triangle-down.png')}}">
        </div>
        <p id="search_results" class="search_result"></p>
        <div class="row search_header arial top_margin_large">
            <div class="col-xs-3 search_name_header search_name">
                <div>
                    <input id="checked_all_patients" type="checkbox">&nbsp;&nbsp;</div>
                <div class="listing_header">
                    <p style="color:#333;margin-left: 11px;">Name</p>
                    <span data-name="lastname" data-order="SORT_ASC" style="left: 14px;" class="sort_order">
						<img src="/images/triangle-up.png" class="sort_indicator" alt="" style="margin-top: -63px;">
				</span>
                </div>
            </div>
            <div class="col-xs-4 listing_header">
                <p style="color:#333">Address</p>
                <span data-name="addressline1" data-order="SORT_ASC" style="" class="sort_order">
					<img src="/images/triangle-up.png" alt="" class="sort_indicator" >
				</span>
            </div>
            <div class="col-xs-3 listing_header">
                <p style="color:#333">Email</p>
                <span data-name="email" data-order="SORT_ASC" style="" class="sort_order">
					<img src="/images/triangle-up.png" alt="" class="sort_indicator">
				</span>
            </div>
            <div class="col-xs-2">
                <input type="hidden" id="delete_practice_img" value="{{asset('images/delete-natural-hover.png')}}">
                <input type="hidden" id="schedule_patient_img" value="{{asset('images/schedule-icon-01.png')}}">
                <!--            <p class="" style="color:#333"><span class="glyphicon glyphicon-chevron-left p_left" id="search_practice_button" aria-hidden="true"></span> <span class="page_info"></span><span class="glyphicon glyphicon-chevron-right p_right" id="search_practice_button" aria-hidden="true"></span></p>-->
            </div>
        </div>
    </div>
	<input type="hidden" value="" id="current_sort_field">
	<input type="hidden" value="" id="current_sort_order">
    <div class="row content-row-margin patient_admin_back">
        <div class="col-xs-2">
            <button type="button" class="btn back patient_back">Back</button>
        </div>
        <div class="col-xs-10">

        </div>
    </div>
</div>
@endif
