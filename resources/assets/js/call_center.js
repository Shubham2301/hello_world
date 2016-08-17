google.charts.load('current', {
    packages: ['corechart', 'line']
});
var graphData = [];
var graphColumn = [];

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
        filter_option: filter,
    };

    $.ajax({
        url: '/report/call_center/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (data) {
            var content = '';
            var userData = data.user;
            for (var key in userData) {
                content += '<div class="col-xs-4">' + userData[key]['name'] + '</div><div class="col-xs-2 align-center">' + userData[key]['phone'] + '</div><div class="col-xs-2 align-center">' + userData[key]['email'] + '</div><div class="col-xs-2 align-center">' + userData[key]['mail'] + '</div><div class="col-xs-2 align-center">' + userData[key]['other'] + '</div>';
            }
            $('.user_listing').html(content);
            graphData = data.graphData;
            graphColumn = data.graphColumn;
            google.charts.setOnLoadCallback(drawLineColors);
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function drawLineColors() {
    var data_graph = new google.visualization.DataTable();
    var chartColor = [];
    for (var key in graphColumn) {
        if (key == 'X')
            data_graph.addColumn('string', key);
        else {
            data_graph.addColumn('number', key);
            chartColor.push(graphColumn[key]);
        }
    }
    var method = ['phone', 'email', 'mail', 'other'];
    for (var key in graphData) {
        var temp = [];
        temp.push(key);
        for (var methods in method) {
            temp.push(graphData[key][method[methods]] ? graphData[key][method[methods]] : 0);
        }
        data_graph.addRow(temp);
    }

    var options = {
        hAxis: {
            title: 'Date'
        },
        vAxis: {
            title: 'Contact Attempts'
        },
        colors: chartColor,
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(data_graph, options);
}
