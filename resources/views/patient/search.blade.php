@if(!$data['admin'])
<div class="row content-row-margin-scheduling">
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
<div class="row content-row-margin ">
    <div class="col-xs-2">
        <button id="open_patient_form" type="button" class="btn add-btn" style="float:right;">Add New</button>
    </div>
    <div class="col-xs-2 search_input_box">
        <input type="text" class="" id="search_patient_input" placeholder="search">
        <span class="glyphicon glyphicon-search glyp" id="search_patient_button" aria-hidden="true"></span>
    </div>
    <div class="col-xs-1">
        <span class="glyphicon glyphicon-remove" id="refresh_patients" area-hidden="true"></span>
    </div>
    <div class="col-xs-7">
        <button type="button" data-toggle="modal" data-target="#importModal_admin"  class="btn import-btn">Import</button>
    </div>
</div>
@endif
