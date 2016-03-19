<div class="row content-row-margin user_listing">

	<p id="search_results" class="search_result"><strong></strong></p>
	<div class="row search_header">
		<div class="col-xs-3 search_name">
			<input type="checkbox" id="checked_all_users">&nbsp;&nbsp;
			<p style="color:black"><strong>Name</strong></p>
		</div>
		<div class="col-xs-3">
			<p style="color:black"><strong>Email</strong></p>
		</div>
		<div class="col-xs-2">
			<p style="color:black"><strong>Level</strong></p>
		</div>
		<div class="col-xs-2">
			<p style="color:black"><strong>Organization</strong></p>
		</div>
		<div class="col-xs-2">
			<input type="hidden" id="dropdown_natural_img" value="{{asset('images/dropdown-natural-01.png')}}">
			<input type="hidden" id="dropdown_onhover_img" value="{{asset('images/dropdown-hover-01.png')}}">
			<input type="hidden" id="dropdown_active_img" value="{{asset('images/dropdown-active-01.png')}}">
			<input type="hidden" id="active_user_img" value="{{asset('images/deactivate.png')}}">
			<p class="pagination"><span class="glyphicon glyphicon-chevron-left p_left" id="search_practice_button" aria-hidden="true"></span> <span class="page_info"><strong></strong></span>
			<span class="glyphicon glyphicon-chevron-right p_right" id="search_practice_button" aria-hidden="true"></span></p>
		</div>
	</div>
	<form action="">
		<input type="hidden" id="assign_role_image_path" value="{{URL::asset('images/permissions.png')}}">
		<input type="hidden" id="assign_user_image_path" value="{{URL::asset('images/impersonate.png')}}">
	</form>
	<div class="user_search_content">




	<!--



<div class="row search_item" data-id="' + user.id + '">
<div class="col-xs-3 search_name">
<input type="checkbox">&nbsp;&nbsp;
<p>' + user.name + '</p>
</div>
<div class="col-xs-3">' + user.email + '</div>
<div class="col-xs-1"></div>
<div class="col-xs-3">
<p>' + user.practice + '</p>
</div>
<div class="col-xs-2 search_edit">
<p>
<div class="dropdown dropdown_action">
<span  area-hidden="true" data-toggle="dropdown" class="dropdown-toggle">
<img class="action_dropdown" src="{{asset('images/dropdown-natural-01.png')}}" alt="">
</span>
<ul class="dropdown-menu" id="row_action_dropdown">
<li>
<a href=""><img src="{{URL::asset('images/assign-role-icon-01.png')}}" class="assign_role_image" style="width:20px">Assign Roles</a>
</li>
<li>
<a href=""><img src="{{URL::asset('images/assign-user-icon-01.png')}}" class="assign_user_image" style="width:20px">Assign Users</a>
</li>
</ul>
</div>
</p>
<p class="editnetwork_from_row">Edit</p>
<div class="dropdown">
<span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row">
<img src="{{asset('images/delete-active-01.png')}}" alt="" class="removeuser_img">
</span>
<ul class="dropdown-menu" id="row_remove_dropdown">
<li class="confirm_text">
<p><strong>Do you really want to delete this?</strong></p>
</li>
<li class="confirm_buttons">
<button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button>
<button type="button"  class="btn btn-info btn-lg confirm_no">NO</button>
</li>
</ul>
</div>
</div>
</div>



-->





	</div>
</div>
<div class="row content-row-margin no_item_found">
	<p>No results found matching :</p>
	<p></p>
</div>
