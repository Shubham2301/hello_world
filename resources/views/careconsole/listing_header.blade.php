@foreach($listing['headers'] as $header)
<div data-name="{{ $header['name'] }}" class="col-xs-{{ $header['width'] }} drilldown_header_item">
	<span class="header_item_name">{{ $header['display_name'] }} </span>
	<span data-name="{{ $header['name'] }}"
		  @if($header['name'] === 'actions')
		  >
		@elseif(isset($header['sort_order']))
		data-order="{{ $header['sort_order'] }}" style="display:inline-block"
		@if($header['sort_order'] === "SORT_ASC")
		class="sort_order glyphicon glyphicon-chevron-up">
		@endif
		@if($header['sort_order'] === "SORT_DESC")
		class="sort_order glyphicon glyphicon-chevron-down">
		@endif
		@else
		data-order="SORT_ASC" class="sort_order glyphicon glyphicon-chevron-up">
		@endif
	</span>
</div>
@endforeach
