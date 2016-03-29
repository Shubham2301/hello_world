<div class="modal fade" id="insuranceModal" role="dialog">
    <div class="modal-dialog form_model">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="action_modal_title" id="action_header" style="color:black;text-align:center">Insurance Details</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    <div class="active">
                        <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <lable for="insurance_carrier"><strong style="color:black;padding-left:1em;">Insurance Carrier</strong></lable>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" name="insurance_carrier" id="insurance_carrier" value="{{ $insurance['insurance_carrier'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <label for="subscriber_name"><strong style="color:black;padding-left:1em;">Subscriber Name</strong></label>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" name="subscriber_name" id="subscriber_name" value="{{ $insurance['subscriber_name'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <label for="subscriber_dob"><strong style="color:black;padding-left:1em;">Subscriber DOB</strong></label>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" name="subscriber_dob" id="subscriber_dob" value="{{ $insurance['subscriber_birthdate'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <label for="subscriber_id"><strong style="color:black;padding-left:1em;">Subscriber Id</strong></label>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" name="subscriber_id" id="subscriber_id" value="{{ $insurance['subscriber_id'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                         <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <label for="insurance_group"><strong style="color:black;padding-left:1em;">Group #</strong></label>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" name="insurance_group" id="insurance_group" value="{{ $insurance['insurance_group_no'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <label for="subscriber_relation"><strong style="color:black;padding-left:1em;">Relation to Patient</strong></label>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" name="subscriber_relation" id="subscriber_relation" value="{{ $insurance['subscriber_relation'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" class="btn btn-primary confirm_action active" data-dismiss="modal">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
