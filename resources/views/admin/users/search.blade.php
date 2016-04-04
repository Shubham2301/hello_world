<div class="row content-row-margin top_margin user_admin_index_header">
    <div class="user_admin_search">
		<p class="page_title arial_bold">Users</p>
        <a href="/administration/users/create"><button type="button" class="btn add-btn">Add New</button></a>
    <span class="search_input_box">
        <input type="text" class="arial_italic" id="search_user_input" placeholder="search">
        <span class="glyphicon glyphicon-search glyp top_margin" id="search_user_button" aria-hidden="true"></span>
    </span>
    <span class="glyphicon glyphicon-remove" id="refresh_users" area-hidden="true"></span>
    <div class="dropdown admin_delete_dropdown" data-toggle="tooltip" title="Deactivate Users" data-placement="top"><span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row admin_delete"><img class="cancel_image" src="{{URL::asset('images/deactivate.png')}}"><img class="cancel_image-hover" src="{{URL::asset('images/deactivate.png')}}"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to deactivate the selected users?</strong></p></li><li class="confirm_buttons"><button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button" class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div>
    </div>
    	<p id="search_results" class="search_result"></p>
	<div class="row search_header arial top_margin_large">
		<div class="col-xs-3 search_name">
			<input type="checkbox" id="checked_all_users">&nbsp;&nbsp;
			<p style="color:#333">Name</p>
		</div>
		<div class="col-xs-3">
			<p style="color:#333">Email</p>
		</div>
		<div class="col-xs-2">
			<p style="color:#333">Level</p>
		</div>
		<div class="col-xs-2">
			<p style="color:#333">Organization</p>
		</div>
		<div class="col-xs-2 pagination_col">
			<input type="hidden" id="dropdown_natural_img" value="{{asset('images/dropdown-natural-new.png')}}">
			<input type="hidden" id="dropdown_onhover_img" value="{{asset('images/dropdown-hover-new.png')}}">
			<input type="hidden" id="dropdown_active_img" value="{{asset('images/dropdown-active-new.png')}}">
			<input type="hidden" id="active_user_img" value="{{asset('images/deactivate-icon.png')}}">
<!--
			<p class="pagination"><span class="glyphicon glyphicon-chevron-left p_left" id="search_practice_button" aria-hidden="true"></span> <span class="page_info"></span>
			<span class="glyphicon glyphicon-chevron-right p_right" id="search_practice_button" aria-hidden="true"></span></p>
-->
		</div>
	</div>
</div>
