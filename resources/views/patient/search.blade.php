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
<div class="row content-row-margin patient_admin_search active">
	<div class="col-xs-1">
		<p style="font-size:1.3em;padding-top: 2px;color: #fff;padding-left: 15px;">Patients</p>
	</div>
    <div class="col-xs-2">
        <button id="open_patient_form" type="button" class="btn add-btn" >Add New</button>
    </div>
    <div class="col-xs-2 search_input_box">
        <input type="text" class="" id="search_patient_input" placeholder="search">
        <span class="glyphicon glyphicon-search glyp" id="search_patient_button" aria-hidden="true"></span>
    </div>
    <div class="col-xs-1">
        <span class="glyphicon glyphicon-remove" id="refresh_patients" area-hidden="true"></span>
    </div>
    <div class="col-xs-6">
        <button type="button" data-toggle="modal" data-target="#importModal"  class="btn import-btn">Import</button>
    </div>
</div>

<div class="row content-row-margin patient_admin_back">
    <div class="col-xs-2">
        <button type="button" class="btn add-btn back patient_back" >Back</button>
    </div>
    <div class="col-xs-10">

    </div>
</div>

@endif
