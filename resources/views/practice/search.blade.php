<div class="row content-row-margin active top_margin practice_action_header">
    <div class="practice_action">
		<p class="page_title arial_bold">Practices</p>
        <button id="open_practice_form" type="button" class="btn add-btn" >Add New</button>
        <span class="search_input_box">
            <input type="text" class="arial_italic" id="search_practice_input" placeholder="search">
            <span class="glyphicon glyphicon-search glyp" id="search_practice_button" aria-hidden="true"></span>
        </span>
        <span class="glyphicon glyphicon-remove" id="refresh_practices" area-hidden="true"></span>
        <div class="dropdown admin_delete_dropdown" data-toggle="tooltip" title="Delete Practice" data-placement="top"><span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row admin_delete"><img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}"><img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete the selected practices?</strong></p></li><li class="confirm_buttons"><button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button" class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div>
    </div>

        <p id="search_results" class="search_result"></p>
    <div class="row search_header arial top_margin_large">
        <div class="col-xs-3 search_name_header">
            <div>
                <input id="checked_all_practice" type="checkbox">&nbsp;&nbsp;</div>
            <div class="">
                <p style="color:#333">Name</p>
            </div>
        </div>
        <div class="col-xs-4">
            <p style="color:#333">Address</p>
        </div>
        <div class="col-xs-3">
            <p style="color:#333">Email</p>
        </div>
        <div class="col-xs-2">
            <input type="hidden" id="schedule_practice_img" value="{{asset('images/schedule.png')}}">
            <input type="hidden" id="delete_practice_img" value="{{asset('images/delete-natural-hover.png')}}">
        </div>
    </div>
</div>
