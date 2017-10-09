<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#practice_appointment_section">Practice Appointment Export</a>
        </h4>
    </div>
    <div id="practice_appointment_section" class="panel-collapse collapse accounting_collapse_panel">
        <div class="panel-body accounting_reports">
            <div class="reports_container arial" id="record_report">
                <span class="report_option_row">
                    <span>Network:</span>
                    <select class="network_selector practice_appointment">
                        <option value="-1">Select a network</option>
                        @foreach($networkData as $networkID => $network)
                            <option value="{{ $networkID }}">{{ $network }}</option>
                        @endforeach
                    </select>
                </span>
                <span class="report_option_row">
                    <span>Practice:</span>
                    <select class="practice_selector practice_appointment">
                        <option value="-1">Select a practice</option>
                    </select>
                </span>
                <span class="report_option_row">
                    <button type="submit" class="btn export_button" id="practice_appointment">Export</button>
                </span>
            </div>
        </div>
    </div>
</div>