<div class="row content-row-margin top_margin network_admin_search_header">
    <div class="network_admin_search">
		<p class="page_title arial_bold">Network</p>
        <a href="/administration/networks/create"><button type="button" class="btn add-btn">Add New</button></a>
    <span class="search_input_box">
        <input type="text" class="arial_italic" id="search_network_input" placeholder="search">
        <img src="{{asset('images/search-icon.png')}}" class="admin_seacrh_icon" id="search_network_button">
    </span>
        <span class="glyphicon glyphicon-remove" id="refresh_networks" area-hidden="true"></span>
    </div>
        <p id="search_results" class="search_result"></p>
    <div class="row search_header arial top_margin_large">
        <div class="col-xs-3 search_name">
            <input type="checkbox" id="checked_all_networks">&nbsp;&nbsp;
            <p style="color:#333">Name</p>
        </div>
        <div class="col-xs-4">
            <p style="color:#333">Contact</p>
        </div>
        <div class="col-xs-3">
            <p style="color:#333">Address</p>
        </div>
        <div class="col-xs-2">
            <input type="hidden" id="dropdown_natural_img" value="{{asset('images/dropdown-natural-new.png')}}">
            <input type="hidden" id="dropdown_onhover_img" value="{{asset('images/dropdown-hover-new.png')}}">
            <input type="hidden" id="dropdown_active_img" value="{{asset('images/dropdown-hover-new.png')}}">
            <input type="hidden" id="delete_network_img" value="{{asset('images/delete-natural-hover.png')}}">
        </div>
    </div>
</div>
