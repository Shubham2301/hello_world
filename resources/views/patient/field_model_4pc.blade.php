
<div class="modal fade" id="field_Modal_4PC" role="dialog">
	<div class="modal-dialog alert">

		<!-- Modal content-->
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="color:black;margin-left:20%;">Please provide the missing information</h4>
			</div>
			<div class="modal-body">
				<div class="content-section active">
					<form action="/updatepatientdata" id="form_4pc_field">
					<input type="hidden" name="patientId" value= ""  class="patient_id_4pc">
					@foreach($fields_4PC as $field)
					<div class="row content-row-margin">
						<div class="col-xs-4 form-group" style="padding-top: 5px;">
							<lable for="exampleInputName1"><strong style="padding-left:3em;color:black;"> {{$field['display_name']}}</strong></lable>
						</div>
						<div class="col-xs-8">
							{!! Form::text($field['field_name'],null, array('class' => 'referredby_input '. $field['type'] )) !!}
						</div>
					</div>
					@endforeach
				</div>
			</div>

			<div class="modal-footer">
				<div style="margin-right:33%">
					<button type="button" class="btn save_4pcdata active">Save</button>
					<button type="button" class="btn btn-default dismiss_button" data-dismiss="modal" style="background-color:#d2d3d5">Cancel</button>
				</div>
			</div>
		</div>

	</div>
</div>
