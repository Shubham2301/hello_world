<div class="row content-row-margin user_admin_search">
		<p class="page_title">Users</p>
        <a href="/administration/users/create"><button type="button" class="btn add-btn">Add New</button></a>
    <span class="search_input_box">
        <input type="text" class="" id="search_user_input" placeholder="search">
        <span class="glyphicon glyphicon-search glyp" id="search_user_button" aria-hidden="true"></span>
    </span>
    <span class="glyphicon glyphicon-remove" id="refresh_users" area-hidden="true"></span>
    <div class="dropdown admin_delete_dropdown" data-toggle="tooltip" title="Disable Users" data-placement="top"><span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row admin_delete"><img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}"><img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to deactivate the selected users?</strong></p></li><li class="confirm_buttons"><button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button" class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div>
</div>
