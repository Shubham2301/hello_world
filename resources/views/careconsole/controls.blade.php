<div class="C3_day_row control_header arial_bold"><p>Controls</p></div>
<div class="C3_day_row control_label arial">
    <span>Filter</span>
</div>
<div class="C3_day_row filter_row">
    <input type="text" class="arial_italic" id="filter_value" placeholder="Filter Value" value="{{ $filter_value}}">
    <select id="filter_type">
        <option value="-1">Select Filter</option>
        @foreach($listing['headers'] as $header)
            @if($header['filter_field'] == '1')
                <option value="{{ $header['name'] }}" {{ $header['name'] == $filter_type ? 'selected' : '' }}>{{ $header['display_name'] }}</option>
            @endif
        @endforeach
    </select>
</div>
<div class="C3_day_row">
    <button id="apply_filter">Filter</button>
    <button id="remove_filter" @if($filter_value == '') disabled @endif>Remove</button>
</div>
@foreach($controls as $control)
<div class="C3_day_row control_label arial">
	<p>{{ $control['group_display_name'] }}</p>
</div>
<div class="C3_day_row" data-stageid ="{{$control['stage_id'] }}" data-stage-system-name="{{ $control['stage_system_name'] }}" data-stage-display-name="{{ $control['stage_display_name'] }}">
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