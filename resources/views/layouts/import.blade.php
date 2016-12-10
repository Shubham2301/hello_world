<!-- Modal -->
<div class="modal fade" id="importModal" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="loader-container" style="top: 1.5em;left: 36%;"></div>
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
								@if($network['multiple_network'])
								<select name="network_id" class="form-control" required>
                               	<option value="0">Select a Network</option>
								   @foreach ($network['networks'] as $key => $network)
								   <option value="{{$key}}">{{$network}}</option>
								   @endforeach
                               </select>
                               @else
								<span class="network_name">{{ $network['name'] }}</span>
								<input type="hidden" value="{{ $network['id'] }}" name="network_id">
                               @endif

                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <label for="exampleInputFile"><strong style="padding-left:3em;" >File</strong></label>
                            </div>
                            <div class="col-md-7 ">
								<span class="xlsx_file-input active ">Select{!!Form::file('patient_xlsx')!!}
                                </span>
								<span class="xlsx_file_filename filename"></span>
                                <input type='hidden' id='clear_image_path' value="{{asset('images/close-natural.png')}}">

                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        @if(session('network-id') == 9)
                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <label for="exampleInputFile"><strong style="padding-left:3em;" >Mail</strong></label>
                            </div>
                            <div class="col-md-7">
                                <select name="template" id="template" class="form-control">
                                    <option value="-1">Select Mail Template</option>
                                    @foreach(Helper::mandrillTemplates('bulk-import') as $template_slug => $template_name)
                                    <option value="{{ $template_slug }}">{{ $template_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        @endif
                        <a href="/import/format/xlsx"><div style="color:#000;text-decoration: underline; padding-left:3em;">Download Excel Format</div></a>
                        <div style="color:#FF7777; padding-left:3em;">* Max upload limit 5,000 patients.</div>
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
