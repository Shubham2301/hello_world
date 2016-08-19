google.charts.load('current', {
    'packages': ['line', 'bar']
});

var graphColumn = [];

var filterOptions = {
    "chartType": "overview",
    "overviewChart": "all",
    "userID": "",
};

var graphData = [];
var comparisonData = [];

$(document).ready(function () {

    var cur_date = new Date();
    var set_start_date = new Date(cur_date.getTime());

    $('.overview_controls>li').on('click', function () {
        $('.overview_controls>li').removeClass('arial_bold');
        $(this).addClass('arial_bold');
        filterOptions.overviewChart = $(this).attr('id');
        google.charts.setOnLoadCallback(drawCallCenterGraph);
    });

    $('.user_listing').on('click', 'li.drilldown_item', function () {
        filterOptions.userID = $(this).attr('id');
        getReport();
    });

    $('.filter_row').on('click', '.remove_filter', function () {
        filterOptions.userID = '';
        getReport();
    });

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

    $("li.chart_tab").click(function () {
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

function getReport(filter) {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        filter_option: filterOptions,
    };

    $.ajax({
        url: '/report/call_center/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (data) {
            var content = '';
            var filterData = '';
            var userData = data.user;
            for (var key in userData) {
                if (userData[key]['total'] != 0) {
                    var drilldownIndicator = 'drilldown_item';
                } else {
                    var drilldownIndicator = 'drilldown_disable';
                }
                content += '<li class="' + drilldownIndicator + '" id="' + userData[key]['id'] + '"><div class="col-xs-4 user_name">' + userData[key]['name'] + '</div><div class="col-xs-2 align-center">' + userData[key]['phone'] + '</div><div class="col-xs-2 align-center">' + userData[key]['email'] + '</div><div class="col-xs-2 align-center">' + userData[key]['sms'] + '</div><div class="col-xs-2 align-center">' + userData[key]['total'] + '</div></li>';

                if (filterOptions.userID == userData[key]['id']) {
                    filterData = '<div class="filter_section">User: ' + userData[key]['name'] + '<span class="glyphicon glyphicon-remove-circle remove_filter"></span></div>';
                }
            }
            $('.user_listing').html(content);
            $('.filter_row').html(filterData);
            graphData = data.graphData.overview;
            comparisonData = data.graphData.comparison;
            graphColumn = data.graphColumn;
            google.charts.setOnLoadCallback(drawCallCenterGraph);
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function drawCallCenterGraph() {


    if (filterOptions.chartType == "overview") {
        var data_graph = new google.visualization.DataTable();
        var chartColor = [];
        for (var key in graphColumn) {
            if (key == 'Date')
                data_graph.addColumn('string', key);
            else {
                data_graph.addColumn('number', key);
                chartColor.push(graphColumn[key]);
            }
        }

        for (var key in graphData) {

            var temp = [];
            temp.push(graphData[key]['date']);
            temp.push(graphData[key]['scheduled'][filterOptions.overviewChart]);
            temp.push(graphData[key]['attempt'][filterOptions.overviewChart]);

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
            width: 780,
            height: 230,
        };

        var chart = new google.charts.Line(document.getElementById('chart_div'));
        google.visualization.events.addListener(chart, 'error', function (googleError) {
            google.visualization.errors.removeError(googleError.id);
        });
        chart.draw(data_graph, options);
    } else {

        var data = google.visualization.arrayToDataTable([
          ['Type', 'Attempt', 'Scheduled'],
          ['Phone', comparisonData['attempt']['phone'], comparisonData['scheduled']['phone']],
          ['Email', comparisonData['attempt']['email'], comparisonData['scheduled']['email']],
          ['SMS', comparisonData['attempt']['sms'], comparisonData['scheduled']['sms']],
        ]);

        var options = {
            width: 780,
            height: 230,
            colors: chartColor,
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, options);
    }

}
