<div class="row content-row-margin-scheduling">
   <div class="search_bar">
    <div class="col-xs-10 search_input">
        <input type="text" class="" id="search_practice_input" >
<!--        <span class="glyphicon glyphicon-search" id="search_practice_button" aria-hidden="true"></span>-->
        <img src="{{elixir('images/sidebar/search-icon-schedule.png')}}" id="search_practice_button">
        <span class="glyphicon glyphicon-plus-sign add_search_option" id="add_practice_search_option" aria-hidden="true">    </span>
    </div>
    <div class="col-xs-2 search_dropdown" patient-id="{!! $data['patient_id']!!}">
        <div class="dropdown"><span  data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="custom_dropdown"><span id="search_practice_input_type" value="all">All</span><img src="/images/triangle-down.png" class="custom_dropdown_img_search"></span></span><ul class="dropdown-menu" id="custom_dropdown">
                <li value="all">All</li>
                <li value="pratice_name">Practice Name</li>
                <li value="location">Location</li>
                <li value="provider_name">Provider Name</li>
                <li value="zip">Zip code</li>
        </ul></div>
    </div>
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
    <div class="row content-row-margin-scheduling patient_info" data-id="">
		@include('patient.patient_info')
	</div>

