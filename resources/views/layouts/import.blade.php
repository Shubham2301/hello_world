<!-- Modal -->
<div class="modal fade" id="importModal" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Import Patients</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active" id="patients_section">
                    <div class="import_form active">
                        {!! Form::open(array('url' => 'import/xlsx', 'method' => 'POST', 'files'=>true,'id'=>'import_form')) !!}
                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <lable for="exampleInputName1"><strong style="padding-left:3em;">Network</strong></lable>
                            </div>
                            <div class="col-md-7 form-group">
                                <span class="network_name">{{ $network['name'] }}</span>
                                <input type="hidden" value="{{ $network['id'] }}">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <label for="exampleInputFile"><strong style="padding-left:3em;" >File</strong></label>
                            </div>
                            <div class="col-md-7 ">
                                <span class="file-input">Select{!!Form::file('patient_xlsx')!!}
                                </span>
                                <span class="filename"></span>

                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    {!! Form::close()!!}
                    <p class="success_message"></p>
                </div>
            </div>

            <div class="modal-footer">
                <div style="text-align:center">
                    <button type="button" class="btn import_button active" >Import</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>
