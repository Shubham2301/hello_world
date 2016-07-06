<div class="modal fade" id="field_modal_fpc" role="dialog">
    <div class="modal-dialog alert">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="color:black;margin-left:20%;">Please provide the missing patient information</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    <form action="/updatepatientdata" id="form_fpc_field">
                        <input type="hidden" name="patientId" value="" class="patient_id_fpc"> @foreach($fields_FPC as $field)
                        <div class="row content-row-margin">
                            <div class="col-xs-4 form-group text-right" style="padding-top: 5px;">
                                <lable for="exampleInputName1"><strong style="color:black;"> {{$field['display_name']}}</strong></lable>
                            </div>
                            <div class="col-xs-8">
								{!! Form::text($field['field_name'],null, array('class' => 'fpc_form_input '. $field['type'] )) !!}
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>

				<div class="missing_field_form_footer">
                <div style="">
                    <button type="button" class="btn save_fpcdata active">Save</button>
                    <button type="button" class="btn btn-default dismiss_button cancel_fpcdata" data-dismiss="modal" style="background-color:#d2d3d5">Cancel</button>
                </div>
               </div>
        </div>

    </div>
</div>
