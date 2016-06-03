<div class="modal fade" id="newfolderModal" role="dialog">
    <div class="modal-dialog alert">
        {!! Form::open(array('url' => 'createFolder', 'method' => 'POST', 'id'=>'create_folder')) !!}
        <div class="modal-content">
                {!! csrf_field() !!}
                <span class="modal_title arial_bold">New Folder</span>
                <span class="modal_content_row">
                    <span class="left">Name</span>

                    <span class="right"><input type="text" name="foldername" required></span>
                </span>
                <span class="modal_content_row">
                    <span class="left">Description</span>
                    <span class="right"><textarea name="folderdescription"></textarea></span>
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
