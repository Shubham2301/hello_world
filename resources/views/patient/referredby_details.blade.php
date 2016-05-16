<!-- Modal -->
<div class="modal fade" id="referredby_details" role="dialog">
    <div class="modal-dialog form_model">

        <!-- Modal content-->
        <div class="modal-content " style="width:500px">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;margin-left:39%;">Referred By</h4>
            </div>
            <div class="modal-body">
                <div class="content-section active">
                    <div class="row content-row-margin">
						<div class="col-md-3 form-group" style="padding-top: 5px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;"> Practice</strong></lable>
						</div>
                        <div class="col-sm-9 col-xs-12">
                            {!! Form::text('referred_by_practice',null, array('class' => 'referredby_input referredby_practice', 'name' => 'referred_by_practice', 'id' => 'referred_by_practice', 'onkeyup'=>'referredByPracticeSuggestions(this.value)', 'autocomplete'=>'off')) !!}
                            <ul class="suggestion_list practice_suggestions">
                            </ul>
                        </div>
                    </div>
                    <div class="row content-row-margin">
						<div class="col-md-3 form-group" style="padding-top: 5px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;"> Provider</strong></lable>
						</div>
                        <div class="col-sm-9 col-xs-12">
							{!! Form::text('referred_by_provider',null , array('class' => 'referredby_input referredby_provider', 'name' => 'referred_by_provider', 'id' => 'referred_by_provider', 'onkeyup'=>'referredByProviderSuggestions(this.value)', 'autocomplete'=>'off')) !!}
                            <ul class="suggestion_list provider_suggestions">
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div style="margin-right:33%">
                    <button type="button" class="btn save_referredby active">Save</button>
					<button type="button" class="btn btn-default dismiss_button" data-dismiss="modal" style="background-color:#d2d3d5">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>
