<div class="modal fade" id="upload_files" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Upload Files</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active patient_file_content">
                    {!! Form::open(array('url'=>'#','method'=>'POST', 'files'=>true, 'id'=>'upload_files_form')) !!}
                     <input type="hidden" id="upload_referral_id" name="upload_referral_id"  value="">
                     <input type="hidden" id="upload_patient_id" name="upload_patient_id"  value="">
					<input type="hidden" id="count_patient_file" name="count_patient_file"  value="">


                    <div class="row content-row-margin">
						<div class="col-xs-1"></div>
                        <div class="col-xs-8 form-group text-right" style="padding-top: 5px;">
							<input type="text" name="patient_file_name_1" class="patient_file_name" style="color: #4d4d4d;border-radius: 6px;border: 1px solid #4d4d4d;padding: 0.25em 0.5em;font-style: italic;width:100%">
                        </div>
                        <div class="col-xs-2">
							<span class="file_upload_form_input active" style="  color: #fff;background: #4d4d4d;padding: 0.25em 0.5em;margin: 0.25em 0;font-style: italic;">Select<input name="patient_file_1" type="file"></span>
                            <span class="file_upload_form_filename filename"></span>
                        </div>
                    </div>
					<div class="patient_file_section"></div>
                    </form>
				<button id='new_file_upload_btn' class="btn" style="margin-left: 16em; background-color: transparent; color: black;border-color: #0071FE;">+</button>
                    <p class="success_message"></p>
                </div>
            </div>

            <div class="custom_model_footer">
                <div style="">
                    <button type="button" class="btn custom_save_btn upload_files_btn">Upload</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal" style="background-color:#d2d3d5">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>
