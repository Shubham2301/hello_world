google.charts.load('current', {
    'packages': ['corechart']
});

var filterOptions = {};

var graphData = {};

var graphType = {};

var graphColumn = {};

var overAllGraph = {};

var graphOption = {
    vAxis: {
        title: 'Patient Count',
        gridlines: {
            color: '#f5f5f5',
        },
        baselineColor: '#f5f5f5',
    },
    hAxis: {
        title: 'Date',
        gridlines: {
            color: '#f5f5f5',
        },
        baselineColor: '#f5f5f5',
    },
    backgroundColor: '#f5f5f5',
    width: 200,
    height: 200,
    legend: {
        position: 'none'
    }
};

$(document).ready(function () {

    var cur_date = new Date();
    var set_start_date = new Date(cur_date.getTime());

    $('#start_date').datetimepicker({
        defaultDate: set_start_date.setDate(cur_date.getDate() - 30),
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
    });

    $('#end_date').datetimepicker({
        defaultDate: cur_date,
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
    });

    getReport();

    var old_start_date = $('#start_date').val();
    var old_end_date = $('#end_date').val();

    $('#start_date').datetimepicker().on('dp.hide', function (ev) {
        var start_date = $('#start_date').val();
        if (start_date != old_start_date) {
            old_start_date = $('#start_date').val();
            getReport();
        }
    });

    $('#end_date').datetimepicker().on('dp.hide', function (ev) {
        var end_date = $('#end_date').val();
        $('#start_date').data("DateTimePicker").maxDate(new Date(end_date));
        if (end_date != old_end_date) {
            old_end_date = $('#end_date').val();
            getReport();
        }
    });

    $(document).on('change', '#network', function () {
        getReport()
    });

});

function getReport(filter) {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        network: $('#network').val(),
        filter_option: filterOptions,
    };

    $.ajax({
        url: '/report/performance/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (data) {
            graphData = data.timelineGraph;
            graphType = data.graphType;
            overAllGraph = data.overAllGraph;
            graphColumn = graphType.graphColumn;

            if (overAllGraph.total_patient != 0) {
                $('.bill_graph_row').show();
                $('.no_data_received').hide();
                drawGoalChart(data.userCount);
                drawCompareChart(data.userCount);
                drawOverallChart();
            } else {
                $('.bill_graph_row').hide();
                $('.no_data_received').show();
            }
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function drawGoalChart(userCount) {

    var options = {
        seriesType: 'area',
        series: {
            0: {
                color: '#0071bc',
            },
            1: {
                type: 'line',
                color: ['#4d4d4d'],
            }
        },
        chartArea: {
            width: '70%',
            height: '70%'
        },
        fallingColors: 'cce3f2',
    };

    $.extend(options, graphOption);



    var types = graphType.goalGraph;

    types.forEach(function (type) {

        var data = new google.visualization.DataTable();

        graphColumn[type].forEach(function (columnName) {
            if (columnName == 'Date') {
                data.addColumn('string', columnName);
            } else {
                data.addColumn('number', columnName);
            }
        });
        var i = 0;
        for (var key in graphData) {
            var temp = [];
            i++;
            temp.push(graphData[key]['date']);
            switch (type) {
                case 'avgContact':
                    temp.push(Math.round(graphData[key]['contactAttempted']/userCount));
                    temp.push(2);
                    break;
                case 'avgReached':
                    temp.push(Math.round(graphData[key]['reached']/userCount));
                    temp.push(2);
                    break;
                case 'avgScheduled':
                    temp.push(Math.round(graphData[key]['appointmentScheduled']/userCount));
                    temp.push(2);
                    break;
            }

            data.addRow(temp);

        }
        options.hAxis.showTextEvery = i-1;
        var chart = new google.visualization.ComboChart(document.getElementById(type));
        chart.draw(data, options);
    });
}

function drawOverallChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Count');
    data.addColumn('number', 'Scheduled');
    data.addColumn('number', 'Not Scheduled');

    data.addRow(['Patients', overAllGraph.completed_patient, overAllGraph.pending_patient]);

    var options = {
        isStacked: 'percent',
        series: {
            0: {
                color: '#22b573',
            },
            1: {
                color: '#ff1d25',
            }
        },
        chartArea: {
            width: '100%',
            height: '100%'
        },
        bar: {
            groupWidth: '100%'
        }
    };

    $.extend(options, graphOption);

    var chart = new google.visualization.ColumnChart(document.getElementById('overall_patient'));
    chart.draw(data, options);
}

function drawCompareChart(userCount) {

    var options = {
        colors: ['#22b573', '#ff1d25'],
        chartArea: {
            width: '70%',
            height: '70%'
        },
    };

    $.extend(options, graphOption);

    var types = graphType.compareGraph;

    types.forEach(function (type) {

        var data = new google.visualization.DataTable();

        graphColumn[type].forEach(function (columnName) {
            if (columnName == 'Date') {
                data.addColumn('string', columnName);
            } else {
                data.addColumn('number', columnName);
            }
        });

        for (var key in graphData) {
            var temp = [];
            temp.push(graphData[key]['date']);
            switch (type) {
                case 'scheduled_vs_dropped':
                    temp.push(Math.round(graphData[key]['appointmentScheduled']/userCount));
                    temp.push(Math.round(graphData[key]['dropped']/userCount));
                    break;
                case 'keptAppointment_vs_missed':
                    temp.push(Math.round(graphData[key]['keptAppointment']/userCount));
                    temp.push(Math.round(graphData[key]['missedAppointment']/userCount));
                    break;
                case 'receivedReport_vs_pending':
                    temp.push(Math.round(graphData[key]['reportsReceived']/userCount));
                    temp.push(Math.round(graphData[key]['reportsDue']/userCount));
                    break;
            }

            data.addRow(temp);

        }

        var chart = new google.visualization.AreaChart(document.getElementById(type));
        chart.draw(data, options);
    });
}
