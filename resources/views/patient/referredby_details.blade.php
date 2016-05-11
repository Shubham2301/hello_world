<!-- Modal -->
<div class="modal fade" id="referredby_details" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Reffered By</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    <div class="row content-row-margin">
						<div class="col-md-5 form-group" style="padding-top: 8px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;">Referred By Practice</strong></lable>
						</div>
                        <div class="col-sm-7 col-xs-12">
                            {!! Form::text('referred_by_practice',null, array('class' => 'referredby_input referredby_practice', 'name' => 'referred_by_practice', 'placeholder' => 'ReferredBy Practice', 'id' => 'referred_by_practice', 'onkeyup'=>'referredByPracticeSuggestions(this.value)')) !!}
                            <ul class="suggestion_list practice_suggestions">
                                <p class="suggestion_item">Practice 1</p>
                                <p class="suggestion_item">Practice 2</p>
                                <p class="suggestion_item">Practice 3</p>
                                <p class="suggestion_item">Practice 4</p>
                            </ul>
                        </div>
                    </div>
                    <div class="row content-row-margin">
						<div class="col-md-5 form-group" style="padding-top: 8px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;">Referred By Provider</strong></lable>
						</div>
                        <div class="col-sm-7 col-xs-12">
                            {!! Form::text('referred_by_provider',null , array('class' => 'referredby_input referredby_provider', 'name' => 'referred_by_provider', 'placeholder' => 'ReferredBy Provider', 'id' => 'referred_by_provider', 'onkeyup'=>'referredByProviderSuggestions(this.value)')) !!}
                            <ul class="suggestion_list provider_suggestions">
                                <p class="suggestion_item">Provider 1</p>
                                <p class="suggestion_item">Provider 2</p>
                                <p class="suggestion_item">Provider 3</p>
                                <p class="suggestion_item">Provider 4</p>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div style="margin-right:40%">
                    <button type="button" class="btn save_referredby active">Save</button>
                    <button type="button" class="btn btn-default dismiss_button" data-dismiss="modal">cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>
