<div class="modal fade" id="upload_files" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Upload Files</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    {!! Form::open(array('url'=>'#','method'=>'POST', 'files'=>true, 'id'=>'upload_files_form')) !!}
                    @foreach($files as $file)
                    <div class="row content-row-margin">
                        <div class="col-xs-4 form-group text-right" style="padding-top: 5px;">
							<lable for="exampleInputName1"><strong style="color:black;"> {{$file->display_name}}</strong></lable>
                        </div>
                        <div class="col-xs-8">
                            <span class="file_upload_form_input active">Select{!!Form::file($file->name)!!}</span>
                            <span class="file_upload_form_filename filename"></span>
                        </div>
                    </div>
                    @endforeach

                    </form>
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
