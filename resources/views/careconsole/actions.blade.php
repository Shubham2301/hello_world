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
                            <div class="col-md-4 form-group">
                                <lable for="action_result_id"><strong style="color:black;padding-left:1em;">Result</strong></lable>
                            </div>
                            <div class="col-md-7 form-group">
                                <select class="form-control" name="action_result_id" id="action_result_id"></select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row input_row" id="form_recall_date">
                            <div class="col-md-4 form-group">
                                <label for="recall_date"><strong style="color:black;padding-left:1em;">Recall date</strong></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" name="recall_date" id="recall_date" rows="5">
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row input_row" id="form_manual_appointment_date" style="display: none;">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_date"><strong style="color:black;padding-left:1em;">Appointment Date</strong></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" name="manual_appointment_date" id="manual_appointment_date">
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row input_row" id="form_manual_appointment_practice" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_date"><strong style="color:black;padding-left:1em;">Practice</strong></label>
                            </div>
                            <div class="col-md-7 form-group">

                                <select class="form-control" name="manual_appointment_practice" id="manual_appointment_practice">
                                    <option value="0">Select Practice</option>
                                    @foreach($overview['network_practices'] as $practice)
                                    <option value="{{ $practice['id'] }}">{{ $practice['name'] }}</option>
                                    @endforeach
                                    <option value="0">Not listed</option>

                                </select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row input_row" id="form_manual_appointment_provider" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_provider"><strong style="color:black;padding-left:1em;">Provider</strong></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <select class="form-control" name="manual_appointment_provider" id="manual_appointment_provider"></select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row input_row" id="form_manual_appointment_location" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_location"><strong style="color:black;padding-left:1em;">Location</strong></label>
                            </div>
                            <div class="col-md-7 form-group">

                                <select class="form-control" name="manual_appointment_location" id="manual_appointment_location"></select>

                            </div>
                            <div class="col-md-1"></div>
                        </div>

						<div class="row input_row" id="form_manual_appointment_appointment_type" style="display:none">
							<div class="col-md-4 form-group">
								<label for="manual_appointment_location"><strong style="color:black;padding-left:1em;">Appointment Type</strong></label>
							</div>
							<div class="col-md-7 form-group">

								<input type="text" class="form-control" name="manual_appointment_appointment_type" id="manual_appointment_appointment_type">

							</div>
							<div class="col-md-1"></div>
						</div>



                        <div class="row input_row">
                            <div class="col-md-4 form-group">
                                <label for="action_notes"><strong style="color:black;padding-left:1em;">Notes</strong></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <textarea class="form-control" name="action_notes" id="action_notes" rows="5"></textarea>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" onclick="action()" class="btn btn-primary confirm_action active">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
