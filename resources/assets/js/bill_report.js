google.charts.load('current', {
    'packages': ['corechart']
});

var filterOptions = {};

var graphData = {};

var graphType = {};

var graphColumn = {};

var graphOption = {
    vAxis: {
        textPosition: 'none',
        gridlines: {
            color: '#f5f5f5',
        },
        baselineColor: '#f5f5f5',
    },
    hAxis: {
        textPosition: 'none',
        gridlines: {
            color: '#f5f5f5',
        },
        baselineColor: '#f5f5f5',
    },
    chartArea: {
        width: '100%',
        height: '100%'
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

});

function getReport(filter) {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        filter_option: filterOptions,
    };

    $.ajax({
        url: '/report/billing/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (data) {
            graphData = data.timelineGraph;
            graphType = data.graphType;
            graphColumn = graphType.graphColumn;

            google.charts.setOnLoadCallback(drawGoalChart);
            google.charts.setOnLoadCallback(drawChart);
            google.charts.setOnLoadCallback(drawCompareChart);
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function drawGoalChart() {

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

        for (var key in graphData) {
            var temp = [];
            temp.push(graphData[key]['date']);
            switch (type) {
                case 'avgContact':
                    temp.push(graphData[key]['contactAttempted']);
                    temp.push(2);
                    break;
                case 'avgReached':
                    temp.push(graphData[key]['reached']);
                    temp.push(2);
                    break;
                case 'avgScheduled':
                    temp.push(graphData[key]['appointmentScheduled']);
                    temp.push(2);
                    break;
            }

            data.addRow(temp);

        }

        var chart = new google.visualization.ComboChart(document.getElementById(type));
        chart.draw(data, options);
    });
}

function drawChart() {
    var data = google.visualization.arrayToDataTable([
          ['Year', 'Scheduled', 'Not Scheduled'],
          ['2013', 1000, 170],
          ['2014', 1000, 170]
        ]);

    var options = {
        isStacked: 'true',
        series: {
            0: {
                color: '#22b573',
            },
            1: {
                color: '#ff1d25',
            }
        }
    };

    $.extend(options, graphOption);

    var chart = new google.visualization.AreaChart(document.getElementById('overall_patient'));
    chart.draw(data, options);
}

function drawCompareChart() {

    var options = {
        colors: ['#22b573', '#ff1d25'],
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
                    temp.push(graphData[key]['appointmentScheduled']);
                    temp.push(graphData[key]['dropped']);
                    break;
                case 'keptAppointment_vs_missed':
                    temp.push(graphData[key]['keptAppointment']);
                    temp.push(graphData[key]['missedAppointment']);
                    break;
                case 'receivedReport_vs_pending':
                    temp.push(graphData[key]['reportsReceived']);
                    temp.push(graphData[key]['reportsDue']);
                    break;
            }

            data.addRow(temp);

        }

        var chart = new google.visualization.AreaChart(document.getElementById(type));
        chart.draw(data, options);
    });
}
