<div class="row content-row-margin practice_list">

    <form action="">
            <input type="hidden" id="assign_role_image_path" value="{{URL::asset('images/assign-role-icon-01.png')}}">
            <input type="hidden" id="assign_user_image_path" value="{{URL::asset('images/assign-user-icon-01.png')}}">
        </form>
    <div class="practice_search_content">
        <!--
        <div class="row search_item" data-id="11">
        <div class="col-xs-3" style="display:inline-flex">
            <div>
                <input type="checkbox">&nbsp;&nbsp;
            </div>
            <div class="search_name">
                <p> practice.name </p>
            </div>
        </div>
        <div class="col-xs-3">practice.address </div>
        <div class="col-xs-1">
			<span class="glyphicon glyphicon-chevron-up glyph_design" id="location_address_next"></span>
			<span><p class="location_counter">0</p></span>
			<span class="glyphicon glyphicon-chevron-down glyph_design" id="location_address_previous"></span>
        </div>
        <div class="col-xs-3">
            <p>practice.ocuapps </p>
        </div>
        <div class="col-xs-2 search_edit">
            <p>
                <div class="dropdown">
                    <span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;">
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
            </p>&nbsp;&nbsp;
            <p class="editPractice_from_row" data-toggle="modal" data-target="#create_practice">Edit</p>
            <div class="dropdown">
                <span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepractice_from_row">
                    <img src="{{asset('images/delete-active-01.png')}}" alt="" class="removepractice_img">
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
<div class="row content-row-margin practice_info" data-id="" >
    <div class="col-xs-12">
        <div class="row practice_info_header">
            <div class="col-md-2">
                <button type="button" id="back" class="btn back">Back</button>
            </div>
            <div class="col-md-5" style="font-size: 0.8em;">
                <p id="the_practice_name" class="the_practice_name">Wichita Optometry</p>
            </div>
            <div class="col-md-4 top_assign">
                <p>Assign roles</p>
                &nbsp;&nbsp;&nbsp;
                <p>Assign User</p>
                &nbsp;&nbsp;&nbsp;
                <p id="edit_practice" class="btn" data-toggle="modal" data-target="#create_practice" data-id="">Edit</p>
                &nbsp;
                <div class="dropdown pracice_remove_dropdown">
                    <span id="remove_practice" area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle">
                        <img src="{{asset('images/delete-natural-hover.png')}}" alt="" class="removepractice_img" style="    width:28%;margin: auto 0;">
                   </span>
                    <ul class="dropdown-menu" id="remove_action_dropdown">
                        <li class="confirm_text">
                            <p><strong>Do you really want to delete this?</strong></p>
                        </li>
                        <li class="confirm_buttons">
                            <button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button>
                            <button type="button" class="btn btn-info btn-lg confirm_no">NO</button>
                        </li>
					</ul>
                </div>
            </div>
        </div>
        <div class="row location_info_header">
            <div class="col-xs-3">
                <p class="table_header">Location</p>
            </div>
            <div class="col-xs-4">
                <center>
                    <button type="button" class="btn add" id="new_location" >add+</button>
                </center>
            </div>
            <div class="col-md-5">
                <p class="table_header">Users</p>
            </div>
        </div>
        <div class="practice_location_item_list">
            <div class="row practice_location_item">
                <div class="col-md-3">
                    <p>WichitaOptometry_3801</p>
                    <br>
                    <br>
                    <p>2330 N amidon</p>
                    <p>wichita,kanas</p>
                    <br>
                    <p>316-942-7496 Fax</p>
                </div>
                <div class="col-md-2">
                    <p>Assign roles </p>
                    <p>Assign users</p>
                    <p>edit</p>
                    <img src="" alt="x">
                </div>
                <div class="col-md-7">
                    <div class="practice_users">
                        <input type="checkbox"> <span><p class="user_name">practice user1</p></span><span><img src="" alt="0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
