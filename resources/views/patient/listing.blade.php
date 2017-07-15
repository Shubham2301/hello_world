<div class="row content-row-margin patient_list no_top_margin  auto_scroll side_padding">
    <form action="">
        <input type="hidden" id="assign_role_image_path" value="{{URL::asset('images/assign-role-icon-01.png')}}">
        <input type="hidden" id="assign_user_image_path" value="{{URL::asset('images/assign-user-icon-01.png')}}">
    </form>
    <div class="patient_search_content">
    </div>
</div>
<div class="row content-row-margin no_item_found">
    <p>No results found matching :</p>
    <p></p>
</div>
<div class="row content-row-margin patient_info arial {{array_key_exists('referraltype_id', $data) ? '' : 'side_padding' }}" data-id="">
    @include('patient.patient_info')
</div>