google.charts.load('current', {
    'packages': ['corechart']
});

var filterOptions = {};

$(document).ready(function () {

    google.charts.setOnLoadCallback(drawVisualization);
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawCompareChart);

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

        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function drawVisualization() {
    var data = google.visualization.arrayToDataTable([
         ['Month', 'Average', 'Goal'],
         ['2004/05', 195, 150],
         ['2005/06', 135, 150],
         ['2006/07', 157, 150],
         ['2007/08', 139, 150],
         ['2008/09', 136, 150]
      ]);

    var options = {
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
        chartArea: {
            width: '100%',
            height: '100%'
        },
        backgroundColor: '#f5f5f5',
        width: 200,
        height: 200,
        legend: {
            position: 'none'
        },
    };

    var chart = new google.visualization.ComboChart(document.getElementById('overall_patient2'));
    chart.draw(data, options);
    var chart = new google.visualization.ComboChart(document.getElementById('overall_patient3'));
    chart.draw(data, options);
    var chart = new google.visualization.ComboChart(document.getElementById('overall_patient4'));
    chart.draw(data, options);
}

function drawChart() {
    var data = google.visualization.arrayToDataTable([
          ['Year', 'Scheduled', 'Not Scheduled'],
          ['2013', 1000, 170],
          ['2014', 1000, 170]
        ]);

    var options = {
        isStacked: 'true',
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
        backgroundColor: '#f5f5f5',
        width: 200,
        height: 200,
        legend: {
            position: 'none'
        },
    };

    var chart = new google.visualization.AreaChart(document.getElementById('overall_patient'));
    chart.draw(data, options);
}

function drawCompareChart() {
    var data = google.visualization.arrayToDataTable([
          ['Year', 'Scheduled', 'Not Scheduled'],
          ['2013', 700, 400],
          ['2014', 70, 60],
          ['2015', 60, 20],
          ['2016', 30, 40]
        ]);

    var options = {
        vAxis: {
            textPosition: 'none',
            gridlines: {
                color: '#f5f5f5',
            },
            baselineColor: '#f5f5f5',
        },
        colors: ['#22b573', '#ff1d25'],
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
        },
    };

    var chart = new google.visualization.AreaChart(document.getElementById('overall_patient5'));
    chart.draw(data, options);
    var chart = new google.visualization.AreaChart(document.getElementById('overall_patient6'));
    chart.draw(data, options);
    var chart = new google.visualization.AreaChart(document.getElementById('overall_patient7'));
    chart.draw(data, options);
}
