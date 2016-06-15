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
                            <input type="hidden" value="" name="action_name" id="action_name">
                            <input type="hidden" value="" name="console_id" id="action_console_id">
                            <input type="hidden" value="" name="stage_id" id="action_stage_id">
                            <div class="col-md-4 form-group">
                                <lable for="action_result_id"><span class="arial_bold" style="color:black;padding-left:1em;">Result</span></lable>
                            </div>
                            <div class="col-md-7 form-group">
                                <select class="form-control" name="action_result_id" id="action_result_id"></select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row input_row" id="form_recall_date">
                            <div class="col-md-4 form-group">
                                <label for="recall_date"><span class="arial_bold" style="color:black;padding-left:1em;">Recall date</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" name="recall_date" id="recall_date" rows="5">
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row input_row" id="form_manual_referredby_details" style="display: none;">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_date"><span class="arial_bold" style="color:black;padding-left:1em;">Referred By</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="manual_referredby_practice" id="manual_referredby_practice" placeholder="Practice" onkeyup="referredByPracticeSuggestions(this.value)">
                                        <ul class="suggestion_list practice_suggestions">
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="manual_referredby_provider" id="manual_referredby_provider" placeholder="Provider" onkeyup="referredByProviderSuggestions(this.value)">
                                        <ul class="suggestion_list provider_suggestions">
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row input_row" id="form_manual_appointment_date" style="display: none;">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_date"><span class="arial_bold" style="color:black;padding-left:1em;">Appointment Date</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" name="manual_appointment_date" id="manual_appointment_date">
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row input_row" id="form_manual_appointment_practice" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_date"><span class="arial_bold" style="color:black;padding-left:1em;">Practice</span></label>
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
                                <label for="manual_appointment_provider"><span class="arial_bold" style="color:black;padding-left:1em;">Provider</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <select class="form-control" name="manual_appointment_provider" id="manual_appointment_provider"></select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row input_row" id="form_manual_appointment_location" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_location"><span class="arial_bold" style="color:black;padding-left:1em;">Location</span></label>
                            </div>
                            <div class="col-md-7 form-group">

                                <select class="form-control" name="manual_appointment_location" id="manual_appointment_location"></select>

                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row input_row" id="form_manual_appointment_appointment_type" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_location"><span class="arial_bold" style="color:black;padding-left:1em;">Appointment Type</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <select class="form-control" name="manual_appointment_appointment_type" id="manual_appointment_appointment_type">
                                    <option value="">Appointment Type</option>
                                    @foreach($overview['appointment_types'] as $types)
                                    <option value="{{ $types }}">{{ $types }}</option>
                                    @endforeach
                                    <option value="-1">Not listed</option>
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row input_row" id="form_manual_custom_appointment_appointment_type" style="display:none">
                            <div class="col-md-4 form-group">
                                <label for="manual_appointment_location"><span class="arial_bold" style="color:black;padding-left:1em;">&nbsp;</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" name="manual_custom_appointment_appointment_type" id="manual_custom_appointment_appointment_type" placeholder="Custom Appointment Type">
                            </div>
                            <div class="col-md-1"></div>
                        </div>


                        <div class="row input_row" id="form_action_notes">
                            <div class="col-md-4 form-group">
                                <label for="action_notes"><span class="arial_bold" style="color:black;padding-left:1em;">Notes</span></label>
                            </div>
                            <div class="col-md-7 form-group">
                                <textarea class="form-control" name="action_notes" id="action_notes" rows="5"></textarea>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div id="form_action_request_email" style="display:none">
                            <div class="row input_row">
                                <div class="col-md-1"></div>
                                <div class="col-md-2 form-group">
                                    <p><span class="arial_bold" style="color:black;padding-left:1em;">To</span></p>
                                    <p><span class="arial_bold" style="color:black;padding-left:1em;">Subject</span></p>
                                </div>
                                <div class="col-md-7 form-group">
                                    <p style="color:black;" class="form_action_patient_email_id"></p>
                                    <p style="color:black;">Request For Appointment</p>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <div class="row input_row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10 form-group">
                                    <textarea class="form-control" name="request_email" id="request_email" rows="8">{{ $overview['request_for_appointment']['email'] }}</textarea>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                        <div id="form_action_request_phone" style="display:none">
                            <div class="row input_row">
                                <div class="col-md-1"></div>
                                <div class="col-md-5 form-group">
                                    <p class="form_action_patient_name" style="color:black;"></p>
                                </div>
                                <div class="col-md-5 form-group">
                                    <p style="color:black;text-align:right" class="form_action_patient_phone"></p>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <div class="row input_row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10 form-group" style="padding:0">
                                    <p class="" style="color: rgba(0,0,0,0.8);border: solid 1px #eaeaea;padding: 1em;" name="request_phone" id="request_phone">{!! $overview['request_for_appointment']['phone'] !!}</p>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                        <div id="form_action_request_sms" style="display:none">
                            <div class="row input_row">
                                <div class="col-md-1"></div>
                                <div class="col-md-5 form-group">
                                    <p class="form_action_patient_name" style="color:black;"></p>
                                </div>
                                <div class="col-md-5 form-group">
                                    <p style="color:black;text-align:right" class="form_action_patient_phone"></p>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <div class="row input_row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10 form-group">
                                    <textarea class="form-control" name="request_sms" id="request_sms" rows="8">{{ $overview['request_for_appointment']['sms'] }}</textarea>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
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
