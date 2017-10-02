<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#provider_billing_section">Provider Billing Export</a>
        </h4>
    </div>
    <div id="provider_billing_section" class="panel-collapse collapse accounting_collapse_panel">
        <div class="panel-body accounting_reports">
            <div class="reports_container arial" id="record_report">
                <span class="report_option_row">
                    <span>Network:</span>
                    <select class="network_selector provider_billing">
                        <option value="all">All</option>
                        @foreach($networkData as $networkID => $network)
                            <option value="{{ $networkID }}">{{ $network }}</option>
                        @endforeach
                    </select>
                </span>
                <span class="report_option_row">
                    <button type="submit" class="btn export_button" id="provider_billing">Export</button>
                </span>
            </div>
        </div>
    </div>
</div>