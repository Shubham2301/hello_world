<div class="row content-row-margin network_listing">

    <p id="search_results" class="search_result"><strong></strong></p>
    <div class="row search_header">
        <div class="col-xs-3 search_name">
            <input type="checkbox" id="checked_all_networks">&nbsp;&nbsp;
            <p style="color:black"><strong>Name</strong></p>
        </div>
        <div class="col-xs-4">
            <p style="color:black"><strong>Contact</strong></p>
        </div>
        <div class="col-xs-3">
            <p style="color:black"><strong>Address</strong></p>
        </div>
        <div class="col-xs-2">
            <input type="hidden" id="schedule_network_img" value="{{asset('images/schedule.png')}}">
            <input type="hidden" id="delete_network_img" value="{{asset('images/delete-active-01.png')}}">
            <p class="pagination"><span class="glyphicon glyphicon-chevron-left p_left" id="search_practice_button" aria-hidden="true"></span> <span class="page_info"><strong>2 of 2</strong></span><span class="glyphicon glyphicon-chevron-right p_right" id="search_practice_button" aria-hidden="true"></span></p>
        </div>
    </div>
    <form action="">
        <input type="hidden" id="assign_role_image_path" value="{{URL::asset('images/assign-role-icon-01.png')}}">
        <input type="hidden" id="assign_user_image_path" value="{{URL::asset('images/assign-user-icon-01.png')}}">
    </form>
    <div class="network_search_content">
        <!--
        <div class="row search_item" data-id="' + network.id + '">
        <div class="col-xs-3 search_name">
            <input type="checkbox">&nbsp;&nbsp;
            <p>' + network.name + '</p>
        </div>
        <div class="col-xs-3">' + network.email + '
            <br>' + network.phone + '</div>
        <div class="col-xs-1"></div>
        <div class="col-xs-3">
            <p>' + network.addressline1 + '
                <br>' + network.addressline2 + '</p>
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
            <p class="editnetwork_from_row">Edit</p>
            <div class="dropdown">
                <span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removenetwork_from_row">
                    <img src="{{asset('images/delete-active-01.png')}}" alt="" class="removenetwork_img">
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
