<div class="modal fade" id="shareModal" role="dialog">
    <div class="modal-dialog form_model center">
        {!! Form::open(array('url' => 'shareDocument', 'method' => 'POST', 'id'=>'share_document')) !!}
        <div class="modal-content">
                {!! csrf_field() !!}
                <span class="modal_title arial_bold">Share</span>
                <span class="modal_content_row">
                    <span class="left">Practice</span>
                    <span class="right">
                        <select name="share_practices" class="form-control" id="share_practices">
                            <option value="0">Select Practice</option>
                        </select>
                    </span>
                </span>
                <span class="modal_content_row">
                    <span class="left">User</span>
                    <span class="right">
                        <select name="share_users" class="form-control" id="share_users">
                            <option value="0">Select User</option>
                        </select>
                    </span>
                </span>

                <span class="modal_content_row">
                    <span class="left">Writable</span>
                    <span class="right">
                        <span class="file-input">
                            <span class="btn primary-btn"><input type="checkbox" name="share_writable" style=""></span>
                        </span>
                    </span>
                </span>

                <span class="modal_footer">
                    <button type="submit" class="btn add-btn">Share</button>&nbsp;&nbsp;
                    <button type="button" class="btn dismiss-button" data-dismiss="modal">Cancel</button>
                </span>
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
        </div>
        {!! Form::close()!!}
    </div>
</div>

