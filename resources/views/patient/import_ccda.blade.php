<!-- Modal -->
<div class="modal fade" id="importCcda" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Import CCDA</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">

                    <div class="import_form active">
                        {!! Form::open(array('url' => 'import/ccda', 'method' => 'POST', 'files'=>true,'id'=>'import_ccda_form')) !!} {!! Form::hidden('patient_id', '', array('id' => 'ccda_patient_id')) !!}

                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <label for="exampleInputFile"><strong style="padding-left:3em;">File</strong></label>
                            </div>
                            <div class="col-md-7 ">
                                <span class="file-input">Choose{!!Form::file('patient_ccda')!!}
                                </span>
                                <span class="filename"></span>

                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <p class="success_message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" class="btn save_ccda_button active">Import</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal">cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>
