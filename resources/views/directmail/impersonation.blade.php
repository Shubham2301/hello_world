<form action="/directmail/beginimpersonate" id="impersonation-form" method="GET">
<div class="modal fade" id="impersonateModal" role="dialog">
    <div class="modal-dialog form_model">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="action_modal_title" id="action_header" style="color:black;text-align:center">Proxy User</h4>
            </div>
            <div class="modal-body">
                    <div class="active">
                        <div class="row input_row">
                            <div class="col-md-3 form-group">
                                <label for="insurance_carrier"><strong style="color:black;padding-left:1em;">User</strong></label>
                            </div>
                            <div class="col-md-7 form-group">
                                    <select class="form-control" id="impersonateuser" name="impersonateuser">
                                        <option value="0">Select User</option>
                                        @foreach($impersonation as $user)
                                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                        @endforeach
                                    </select>


                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" onclick="beginImpersonation()" class="btn btn-primary confirm_action active" >Proxy</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
