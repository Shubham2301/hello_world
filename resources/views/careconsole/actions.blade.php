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
                    <div class="import_form active">
                        {!! Form::open(array('url' => 'import/xlsx', 'method' => 'POST', 'files'=>true,'id'=>'import_form')) !!}
                        <div class="row input_row">
                            <input type="hidden" value="" name="patient_id" id="action_patient_id">
                            <input type="hidden" value="" name="action_id" id="action_id">
                            <input type="hidden" value="" name="post_action_id" id="action_post_action_id">
                            <input type="hidden" value="" name="console_id" id="action_console_id">
                            <input type="hidden" value="" name="stage_id" id="action_stage_id">
                            <div class="col-md-3 form-group">
                                <lable for="action_date"><strong style="padding-left:3em;">Date</strong></lable>
                            </div>
                            <div class="col-md-7 form-group">
                                <div class='input-group date' id='datetimepicker_action_date'>
                                    <input type='text' class="form-control" id="action_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
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
                    {!! Form::close()!!}
                    <p class="success_message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" onclick="action()" class="btn confirm_action active">Confirm</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>