<div class="row" style="border-bottom:1px solid #fff">

    <div class="col-xs-6 col-sm-6">
		<span class="search_input_box">
			<input type="text" class="arial_italic" id="search_patient_input" placeholder="search" data-id ="">
			<img src="{{URL::asset('images/sidebar/search-icon.png')}}" class="seacrh_icon" id="search_patient_button">
			<img src="{{URL::asset('images/close-active.png')}}" class="seacrh_icon" id="remove_patient_button">
		</span>

    </div>
    <div class="col-xs-6 col-sm-6">
        <div class="dropdown select_form_dropdown">
			<p class="form_name" type="" data-toggle="dropdown" style="display:none">Type of web form
				<span class="caret" ></span></p>
            <ul class="dropdown-menu" >
                @foreach($forms as $form)
				<li  class= "showwebform" value ="{{$form->name}}" data-id ="{{$form->id}}" >{{$form->display_name}}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
