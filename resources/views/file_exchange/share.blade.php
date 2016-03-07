<div class="modal fade" id="shareModal" role="dialog">
    <div class="modal-dialog form_model center">
        {!! Form::open(array('url' => 'shareFilesFolders', 'method' => 'POST', 'id'=>'share_files_folders')) !!}
        <div class="modal-content">
                {!! csrf_field() !!}
                <span class="modal_title arial_bold">Share</span>
                <span class="modal_content_row">
                    <span class="left">Practice</span>
                    <span class="right">
                        <select name="share_practices" class="form-control" id="share_practices">
                            <option value="0">Select Practice</option>
                            @foreach($practices as $practice)
                            <option value="{{ $practice['id'] }}">{{ $practice['name'] }}</option>
                            @endforeach
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
                <input type="hidden" name="share_folders" id="share_folders" value="">
                <input type="hidden" name="share_files" id="share_files" value="">
        </div>
        {!! Form::close()!!}
    </div>
</div>

