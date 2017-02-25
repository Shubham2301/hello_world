<!-- Modal -->
<div class="modal fade" id="compareCcda" role="dialog">
	<div class="modal-dialog compare_model" style="background-color: transparent;">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header " style="border-bottom:none;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="update_header active"><strong>Please select the patient information you
                    <br> want to update from CCDA file into illuma
                     </strong>
                </p>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    <div class="compare_form active">
                        {!! Form::open(array('url' => 'update/ccda', 'method' => 'POST', 'files'=>true,'id'=>'compare_ccda_form')) !!} {!! Form::hidden('patient_id', '', array('id' => 'compared_patient_id')) !!}

                        <div class="row row_header">
                            <div class="col-md-1 col-xs-1 col-xm-1">
                                <input type="checkbox" id="checked_all">
                            </div>
                            <div class="col-md-3 col-xs-3 col-xm-3">
                                <p><strong>All</strong></p>
                            </div>
                            <div class="col-md-4 col-xs-4 col-xm-4">
                                <p style="font-size:20px;"><strong>illuma</strong></p>
                            </div>
                            <div class="col-md-4 col-xs-4 col-xm-4">
                                <p style="font-size:20px;"><strong>CCDA file</strong></p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 col-xs-12 col-xm-12 campare_items">
                                <div class="row compare_row_item ccda_title">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="title" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Title</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_firstname">
                                    <div class="col-md-1 col-xs-1 col-xm-1 ">
                                        <input type="checkbox" name="firstname" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3  ">
                                        <p><strong>Firstname</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4 ">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_lastname ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="lastname" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Lastname</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_workphone">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="workphone" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Workphone</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_homephone">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="homephone" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Homephone</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_cellphone">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="cellphone" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Cellphone</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_email">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="email" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Email</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_add1">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="addressline1" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Addressline1</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_add2 ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="addressline2" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Addressline2</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_city ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="city" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>City</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_zip ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="zip" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Zip</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_country ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="country" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Country</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_bithdate ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="birthdate" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Birthdate</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_gender ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="gender" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Gender</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row compare_row_item ccda_lang ">
                                    <div class="col-md-1 col-xs-1 col-xm-1">
                                        <input type="checkbox" name="preferredlanguage" value="">
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-xm-3">
                                        <p><strong>Language</strong></p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ocuhub_data">Ocuhub@gmail.com</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-xm-4">
                                        <p class="ccda_data">CCDAfile@gmail.com</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {!! Form::close() !!}

                    </div>
                    <p class="success_message"></p>
                    <div class="view_patient_ccda">

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" class="btn compare_ccda_button active">Update</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal">cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>
