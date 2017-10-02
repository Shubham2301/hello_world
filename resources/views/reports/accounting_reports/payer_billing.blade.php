<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#payer_billing_section">Payer Billing Export</a>
        </h4>
    </div>
    <div id="payer_billing_section" class="panel-collapse collapse accounting_collapse_panel">
        <div class="panel-body accounting_reports">
            <div class="reports_container arial" id="record_report">
                <span class="report_option_row timeline">
                    <span>Timeline:</span>
                    <span>
                        <input type="text" class="date_selector start_date" report="payer_billing">
                        <input type="text" class="date_selector end_date" report="payer_billing">
                    </span>
                </span>
                <span class="report_option_row">
                    <span>Network:</span>
                    <select class="network_selector payer_billing">
                        @foreach($networkData as $networkID => $network)
                            <option value="{{ $networkID }}">{{ $network }}</option>
                        @endforeach
                    </select>
                </span>
                <span class="report_option_row">
                    <button type="submit" class="btn export_button" id="payer_billing">Export</button>
                </span>
            </div>
        </div>
    </div>
</div>