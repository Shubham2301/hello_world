<div class="row search_header">
    @foreach($listing['headers'] as $header)
    <div data-name="{{ $header['name'] }}" class="col-xs-{{ $header['width'] }} drilldown_header_item">
        {{ $header['display_name'] }}
    </div>
    @endforeach
</div>
<div class="drilldown_content">
    @foreach($listing['patients'] as $patient)
    <div class="row drilldown_item" data-patientid="{{ $patient['patient_id'] }}" data-consoleid="{{ $patient['console_id'] }}">
        @foreach($listing['headers'] as $header)
        @if($header['name'] === 'actions')
        <div class="col-xs-{{ $header['width'] }}" data-name="{{ $header['name'] }}">
            <div class="dropdown">
                <span class="glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu{{ $patient['patient_id'] }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="float: right;background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span>
                <ul class="dropdown-menu action_dropdownmenu" aria-labelledby="dropdownMenu{{ $patient['patient_id'] }}" data-patientid="{{ $patient['patient_id'] }}" data-consoleid="{{ $patient['console_id'] }}" style="border-radius: 3px;margin-left: -400%;text-align: right;max-height: 15em;top: 2em;overflow-y: scroll;overflow-x: visible;right: 0;"
                </ul>
            </div>
        </div>
        @else
        <div class="col-xs-{{ $header['width'] }}" data-name="{{ $header['name'] }}">
            <p>{{ $patient[$header['name']] }}</p>
        </div>
        @endif
        @endforeach
    </div>
    @endforeach
</div>