<div class="modal fade" id="newfileModal" role="dialog">
    <div class="modal-dialog form_model center">
        {!! Form::open(array('url' => 'uploadDocument', 'method' => 'POST', 'files'=>true, 'id'=>'upload_document')) !!}
        <div class="modal-content">
                {!! csrf_field() !!}
                <span class="modal_title arial_bold">New File</span>
                <span class="modal_content_row">
                    <span class="left">Name</span>

                    <span class="right"><input type="text" name="filename"></span>
                </span>
                <span class="modal_content_row">
                    <span class="left">Description</span>
                    <span class="right"><textarea name="filedescription"></textarea></span>
                </span>

                <span class="modal_content_row">
                    <span class="left">File</span>
                    <span class="right">
<!--
                        <span class="file-input" style="width: 100%;text-align: left;">
                            <button type="button" class="btn add-btn" style="display: block;float: left;"><input type="file" name="add_document" id="add_document" style="opacity: 0;position: absolute;" required>Select</button>
                            <br><br>
                            <span id="new_filename"></span>
                        </span>
-->
                   <div class="fileUpload btn btn-primary add-btn file-input" style="border-radius:0;">
                       <span>Select</span>
                        <input type="file" class="upload" name="add_document" id="add_document" required/>
                   </div>
                            <span id="new_filename"></span>
                    </span>
                </span>

                <span class="modal_footer">
                    <button type="submit" class="btn add-btn">Add</button>&nbsp;&nbsp;
                    <button type="button" class="btn dismiss-button" data-dismiss="modal">Cancel</button>
                </span>
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
        </div>
        {!! Form::close()!!}
    </div>
</div>

