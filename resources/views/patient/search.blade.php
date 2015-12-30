<div class="row content-row-margin">
    <div class="col-xs-10 search_input">
        <input type="text" class="" id="search_patient_input" >
        <span class="glyphicon glyphicon-search" id="search_patient_button"  aria-hidden="true"></span>
        <span class="glyphicon glyphicon-plus-sign" id="add_search_option"  aria-hidden="true" style="position: inherit"></span>

    </div>


    <div class="col-xs-2 search_dropdown">
        <!-- TODO: Create custom dropdown and write css in style.less and write js in main.js -->
        <select type="text" class="" id="search_patient_input_type">
            <option value="all">All</option>
            <option value="name">Name</option>
            <option value="ssn">SSN</option>
        </select>
    </div>


<div class="col-xs-12 search_filter">

    <div class="search_filter_item"><span class="item_type">name</span>:
        <span class="item_value">Nishanth</span>
        <span class="remove_option">x</span>
    </div>
    <div class="search_filter_item">
        <span class="item_type">ssn</span>:
        <span class="item_value">5151</span>
        <span class="remove_option">x</span>
    </div>

</div>


</div>
