<div class="row content-row-margin">
    <div class="col-xs-10 search_input">
        <input type="text" class="" id="search_practice_input" >
        <span class="glyphicon glyphicon-search" id="search_practice_button" aria-hidden="true"></span>
        <span class="glyphicon glyphicon-plus-sign add_search_option" id="add_practice_search_option" aria-hidden="true">    </span>
    </div>
    <div class="col-xs-2 search_dropdown" patient-id="{!! $data['patient_id']!!}">
        <!-- TODO: Create custom dropdown and write css in style.less and write js in main.js -->
        <select type="text" class="" id="search_practice_input_type">
            <option value="all">All</option>
            <option value="name">Practice Name</option>
            <option value="location">Location</option>
            <option value="docter name">Doctor name</option>
            <option value="zip">zip code</option>
        </select>
    </div>

  <div class="col-xs-12 search_filter">
        <!--
        <div class="search_filter_item">
           <span class="item_type">name</span>:
            <span class="item_value">Provider</span>
            <span class="remove_option">x</span>
        </div>
         -->


    </div>
</div>
