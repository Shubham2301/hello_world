<div class="C3_day_row control_header arial_bold"><p>Controls</p></div>
@foreach($controls as $control)
<div class="C3_day_row control_label arial">
	<p>{{ $control['group_display_name'] }}</p>
</div>
<div class="C3_day_row" data-stageid ="{{$control['stage_id'] }}">
	@if($control['type'] == 1)
	@foreach($control['options'] as $option)
	<div class="C3_day_box low" style="color:{{ $option['color_indicator'] }}" data-name="{{ $option['display_name'] }}">
		<h4 class="arial_bold">{{ $option['description'] }}</h4>
		<p>{{ $option['display_name'] }}</p>
	</div>
	@endforeach
	@endif
	@if($control['type'] == 2)
	@foreach($control['options'] as $option)
	<div class="C3_day_box show" data-id = "{{$option['kpi_name']}}" data-indicator = "{{$option['color_indicator']}}" data-name = "{{$option['display_name']}}" >
		<div class="show_bar" style="background-color:{{ $option['color_indicator']}}"></div>
		<p class="arial_bold">{{ $option['count'] }}</p>
		<p>{{ $option['display_name'] }}</p>
	</div>
	@endforeach
	@endif
</div>
@endforeach
