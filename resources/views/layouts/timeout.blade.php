<div class="modal fade" id="timeout" role="dialog">
	<div class="modal-dialog alert" style="margin: 0;">
		<div class="modal-content session_timeout">
			<div class="modal-body  ">
				<div style="text-align:center">
					<span style="font-weight:bold;color:red;">Warning!</span> Due to inactivity you will be logged out in <span id='warning_counter' style="font-size: 15px;"> </span>.Click <input type="button" id="extend_btn" data-dismiss="modal" onclick="clickAction()" value="Extend">to extend the session

				</div>
				<input type="hidden" id='loged_in' value="{{Auth::check()}}">
			</div>
		</div>
	</div>
</div>
