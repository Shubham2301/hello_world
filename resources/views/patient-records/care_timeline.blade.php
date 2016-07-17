<div class="timeline_section">
    <?php $i=0; ?> @foreach($progress as $status)
    <div class="row">
        <div class="col-xs-1"> </div>
        <div class="col-xs-2">
            <p class="date_left">
                {{ $status['date'][0].', '. $status['date'][2] }}
                <br> {{ $status['date'][1] }}
            </p>
        </div>
        <div class="col-xs-1">
            <div class="timeline">
                <ul>
                    <li class="active">
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="data_right">
                <span data-toggle="collapse" data-target="#{{'left'.$i}}" class="patient_status arial_bold">{{$status['name']}}
                    </span>
                <div class="row collapse" id="{{ 'left'.$i }}">
                    <div class="col-xs-12 ">
                        <div class="timeline_notes">
                            @if(sizeOf($status['notes']) == 1) {{ $status['notes'][0] }} @elseif($status['notes'] > 1)
                            <div class="row note_item">
                                <div class="col-xs-6">
                                    <span>Scheduled to</span>
                                </div>
                                <div class="col-xs-6">
                                    <span>{{ (isset($status['notes'][0]))?$status['notes'][0]:'-' }}</span>
                                </div>
                            </div>
                            <div class="row note_item">
                                <div class="col-xs-6">
                                    <span>Appointment Date</span>
                                </div>
                                <div class="col-xs-6">
                                    <span>{{ (isset($status['notes'][1]))?$status['notes'][1]:'-' }}</span>
                                </div>
                            </div>
                            <div class="row note_item">
                                <div class="col-xs-6">
                                    <span>Appointment Type</span>
                                </div>
                                <div class="col-xs-6">
                                    <span>{{ (isset($status['notes'][2]))?$status['notes'][2]:'-' }}</span>
                                </div>
                            </div>
                            <br> {{ (isset($status['notes'][4]))?$status['notes'][4]:'-' }} @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i++; ?> @endforeach
</div>
<div class="show_more_section">
    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-3">
			@if(sizeOf($progress) == $getResults)
			<p class="show_more_text" data-id="{{$patientID}}" data-result="{{$getResults}}" >Show More</p>
            @endif
        </div>

    </div>
</div>
