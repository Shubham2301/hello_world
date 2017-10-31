google.charts.load('current', {
    'packages': ['line', 'bar']
});
google.charts.load("current", {
    packages: ['corechart']
});

var filterOptions = {
    "chartType": "overview",
    "overviewChart": "all",
    "userID": "",
};

var reportData = [];

$(document).ready(function() {

    var cur_date = new Date();
    var set_start_date = new Date(cur_date.getTime());
    $('#start_date').datetimepicker({
        defaultDate: set_start_date.setDate(cur_date.getDate() - 31),
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
    });
    set_start_date = new Date(cur_date.getTime());
    $('#end_date').datetimepicker({
        defaultDate: cur_date,
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
        minDate: set_start_date.setDate(cur_date.getDate() - 30),
    });
    var old_start_date = $('#start_date').val();
    var old_end_date = $('#end_date').val();

    $('#start_date').datetimepicker().on('dp.hide', function(ev) {
        var start_date = $('#start_date').val();
        $('#end_date').data("DateTimePicker").minDate(new Date(start_date));
        if (start_date != old_start_date) {
            old_start_date = $('#start_date').val();
            getReport();
        }
    });
    $('#end_date').datetimepicker().on('dp.hide', function(ev) {
        var end_date = $('#end_date').val();
        $('#start_date').data("DateTimePicker").maxDate(new Date(end_date));
        if (end_date != old_end_date) {
            old_end_date = $('#end_date').val();
            getReport();
        }
    });

    $('.user_listing').on('click', 'li.drilldown_item', function() {
        var user_id = $(this).attr('id');
        exportUserData(user_id);
    });

    $('.report_header_row').on('change', '#network_id', getReport);

    getReport();

    $("li.chart_tab").click(function() {
        $(this.parentNode).children("li").removeClass("active");
        $(this).addClass("active");

        if ($('li.chart_tab.active').hasClass('overview')) {
            filterOptions.chartType = "overview";
            $('.overview_controls>li').removeClass('hide');
        } else if ($('li.chart_tab.active').hasClass('conversion')) {
            filterOptions.chartType = "conversion";
            $('.overview_controls>li').addClass('hide');
        }
        google.charts.setOnLoadCallback(drawCallCenterGraph);
    });

});

function getReport() {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        network_id: $('#network_id').val(),
    };

    $.ajax({
        url: '/report/call_center/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function(data) {
            var content = '';
            reportData = JSON.parse(data);
            var call_center_data = reportData['call_center_data'];
            call_center_data.forEach(function(user) {

                var total = user['contact_attempts'] + user['appointment_scheduled_outgoing'] + user['appointment_scheduled_incoming'];

                if (total !== 0) {
                    var drilldownIndicator = 'drilldown_item';
                } else {
                    var drilldownIndicator = '';
                }
                content += '<li class="' + drilldownIndicator + '" id="' + user['user_id'] + '"><div class="col-xs-6 user_name">' + user['user_name'] + '</div><div class="col-xs-2 align-center">' + user['contact_attempts'] + '</div><div class="col-xs-2 align-center">' + user['appointment_scheduled_outgoing'] + '</div><div class="col-xs-2 align-center">' + user['appointment_scheduled_incoming'] + '</div></li>';
            });
            $('.user_listing').html(content);

            google.charts.setOnLoadCallback(drawCallCenterGraph);
        },
        error: function() {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function exportUserData(user_id) {
    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        network_id: $('#network_id').val(),
        user_id: user_id,
    };

    var query = $.param(formData);
    window.location = '/report/call_center/export_user_data?' + query;
}

function drawCallCenterGraph() {

    if (filterOptions.chartType == "overview") {
        var data_graph = new google.visualization.DataTable();
        var chartColor = [];
        data_graph.addColumn('string', 'Date');
        data_graph.addColumn('number', 'Attempts');
        data_graph.addColumn('number', 'Scheduled (Outgoing)');
        data_graph.addColumn('number', 'Scheduled (Incoming)');
        chartColor.push('#22b573');
        chartColor.push('#e28413');
        chartColor.push('#de3c4b');


        var graphData = reportData['overview_graph_data'];

        for (var key in graphData) {

            var temp = [];
            temp.push(graphData[key]['date']);
            temp.push(graphData[key]['contact_attempts']);
            temp.push(graphData[key]['appointment_scheduled_outgoing']);
            temp.push(graphData[key]['appointment_scheduled_incoming']);

            data_graph.addRow(temp);

        }

        var options = {
            colors: chartColor,
            height: 230,
        };

        var chart = new google.charts.Line(document.getElementById('chart_div'));
        google.visualization.events.addListener(chart, 'error', function(googleError) {
            google.visualization.errors.removeError(googleError.id);
        });
        chart.draw(data_graph, options);
    } else {


        var data = new google.visualization.DataTable();
        var chartColor = [];
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Attempts');
        data.addColumn('number', 'Outgoing Scheduled');
        data.addColumn('number', 'Incoming Scheduled');
        chartColor.push('#22b573');
        chartColor.push('#e28413');
        chartColor.push('#de3c4b');


        var graphData = reportData['call_center_data'];

        for (var key in graphData) {

            var temp = [];
            var total = graphData[key]['contact_attempts'] + graphData[key]['appointment_scheduled_outgoing'] + graphData[key]['appointment_scheduled_incoming'];

            if (total === 0) {
                continue;
            }
            temp.push(graphData[key]['user_name']);
            temp.push(graphData[key]['contact_attempts']);
            temp.push(graphData[key]['appointment_scheduled_outgoing']);
            temp.push(graphData[key]['appointment_scheduled_incoming']);

            data.addRow(temp);

        }


        var options = {
            height: 230,
            colors: chartColor,
            isStacked: true,
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }

}