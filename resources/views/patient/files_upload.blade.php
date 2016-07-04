<div class="modal fade" id="upload_files" role="dialog">
	<div class="modal-dialog form_model" style="background-color: transparent;">

        <!-- Modal content-->
        <div class="modal-content " style="width:35em;">
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
						<div class="col-xs-2"></div>
                        <div class="col-xs-6 form-group text-right" style="padding-top: 5px;">
							<input type="text" name="patient_file_name_1" class="patient_file_name" placeholder="File name" >
                        </div>
                        <div class="col-xs-4">
							<span class="file_upload_form_input active select_patient_file ">Select<input name="patient_file_1" type="file"></span>
                            <span class="file_upload_form_filename filename"></span>
                        </div>
                    </div>
					<div class="patient_file_section"></div>
                    </form>
				<button id='new_file_upload_btn' class="btn" style="">+</button>
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
