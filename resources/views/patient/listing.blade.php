<!-- TODO: Add pagination to patient list to avoid scrolling of elements -->
<div class="row content-row-margin patient_list no_top_margin  auto_scroll side_padding">


    <form action="">
        <input type="hidden" id="assign_role_image_path" value="{{URL::asset('images/assign-role-icon-01.png')}}">
        <input type="hidden" id="assign_user_image_path" value="{{URL::asset('images/assign-user-icon-01.png')}}">
    </form>
    <div class="patient_search_content">


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
            <div class="col-xs-3">practice.address1<br>practice.address1 </div>
        <div class="col-xs-1"></div>
        <div class="col-xs-3">
            <p>practice.email </p>
        </div>
        <div class="col-xs-2 search_edit">
            <p>
                <div>
                    <span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle">
                        <img class="action_dropdown_img" src="{{asset('images/dropdown-natural-01.png')}}" alt="">
                    </span>
                </div>
            </p>&nbsp;&nbsp;
            <p class="editPatient_from_row" data-toggle="modal" data-target="#create_practice">Edit</p>
            <div class="dropdown">
                <span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepatient_from_row">
                <img src="{{asset('images/delete-active-01.png')}}" alt="" class="removepatient_img">
                </span>
                <ul class="dropdown-menu" id="row_remove_dropdown">
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
       -->



    </div>
</div>
<div class="row content-row-margin no_item_found">
    <p>No results found matching :</p>
    <p></p>
</div>
<div class="row content-row-margin patient_info arial {{array_key_exists('referraltype_id', $data) ? '' : 'side_padding' }}" data-id="">

	@include('patient.patient_info')

</div>
