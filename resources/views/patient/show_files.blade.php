<div class="patient_file_item row">
    <div class="col-xs-1">
        <input type="checkbox" value="CCDA" class="selected_files file_checkbox" checked />
    </div>
    <div class="col-xs-9">
        <a href="/download/ccda/{{ $patientID }}" target="_blank" class="file_name" >CCDA</a>
    </div>
</div>

@foreach($files as $file )
    <div class="patient_file_item row" data-id="{{ $file->id }}">
        <div class="col-xs-1">
            <input type="checkbox" value="{{ $file->id }}" class="selected_files file_checkbox" />
        </div>
        <div class="col-xs-10">
            <a href="/downloadpatientfile/{{ $file->id }}" target="_blank" class="file_name" > {{ $file->display_name }}</a>
        </div>
    </div>
@endforeach

@foreach($records as $record )
    <div class="patient_file_item row" data-id="{{ $record->id }}">
        <div class="col-xs-1">
            <input type="checkbox" value="{{ $record->contact_history_id }}" class="selected_records file_checkbox"/>
        </div>
        <div class="col-xs-10">
            <a href="/show_records/{{ $record->contact_history_id }}" target="_blank" class="file_name" >
                {{ $record->template->display_name . ' - '.$record->created_at }}
            </a>
        </div>
    </div>
@endforeach
