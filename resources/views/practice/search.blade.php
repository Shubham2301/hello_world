<!--
<div class="row content-row-margin practice_action active">
	<div class="col-xs-1">
		<p style="font-size:1.3em;padding-top: 2px;color: #fff;padding-left: 15px;">Practices</p>
	</div>
    <div class="col-xs-2">
        <button id ="open_practice_form" type="button" class="btn add-btn">Add New</button>
    </div>
    <div class="col-xs-2 search_input_box">
        <input type="text" class="" id="search_practice_input" placeholder="search">
        <span class="glyphicon glyphicon-search glyp" id="search_practice_button" aria-hidden="true"></span>
    </div>
    <div class="col-xs-1">
        <span class="glyphicon glyphicon-remove" id="refresh_practices" area-hidden="true"></span>

    </div>
    <div class="col-xs-6">
        {{-- <button type="button" class="btn import-btn">Import</button> --}}

    </div>

</div>
-->
<div class="row content-row-margin practice_action active">
		<p class="page_title">Practices</p>
<!--
        <span class="admin_delete" data-toggle="tooltip" title="Delete Practice" data-placement="top">
                    <img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}">
                    <img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}">
        </span>
-->
        <button id="open_practice_form" type="button" class="btn add-btn" >Add New</button>
        <span class="search_input_box">
            <input type="text" class="" id="search_practice_input" placeholder="search">
            <span class="glyphicon glyphicon-search glyp" id="search_practice_button" aria-hidden="true"></span>
        </span>
        <span class="glyphicon glyphicon-remove" id="refresh_practices" area-hidden="true"></span>
        <div class="dropdown admin_delete_dropdown" data-toggle="tooltip" title="Delete Practice" data-placement="top"><span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row admin_delete"><img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}"><img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete the selected practices?</strong></p></li><li class="confirm_buttons"><button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button" class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div>
</div>
