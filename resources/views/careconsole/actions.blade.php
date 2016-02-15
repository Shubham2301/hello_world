<div class="modal fade" id="actionModal" role="dialog">
    <div class="modal-dialog form_model">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="action_modal_title" id="action_header" style="color:black;text-align:center">Care Console Action</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active" id="patients_section">
                    <div class="active">
                        <div class="row input_row" id="action_results">
                            <input type="hidden" value="" name="patient_id" id="action_patient_id">
                            <input type="hidden" value="" name="action_id" id="action_id">
                            <input type="hidden" value="" name="console_id" id="action_console_id">
                            <input type="hidden" value="" name="stage_id" id="action_stage_id">
                            <div class="col-md-3 form-group">
                                <lable for="action_result_id"><strong style="padding-left:3em;">Result</strong></lable>
                            </div>
                            <div class="col-md-7 form-group">
                                <select class="form-control" name="action_result_id" id="action_result_id"></select>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <lable for="action_notes"><strong style="padding-left:3em;">Notes</strong></lable>
                            </div>
                            <div class="col-md-7 form-group">
                                <textarea class="form-control" name="action_notes" id="action_notes" rows="5"></textarea>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <p class="success_message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" onclick="action()" class="btn btn-primary confirm_action active">Confirm</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
