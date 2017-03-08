google.load("visualization", "1", {
    packages: ["corechart"]
});
google.charts.load('visualization', 'current', {
    'packages': ['corechart']
});

var filterOptions = {
    'filterType': '',
    'type': 'no_filter',
    'filterHeader': '',
};

var filter = '';

var graphData = {};

var graphType = {};

var graphColumn = {};

var overAllGraph = {};

var drillDownData = {};

var graphGoals = {};

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
    width: 250,
    height: 200,
    legend: {
        position: 'none'
    }
};

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
            filter = '';
            getReport();
        }
    });
    $('#end_date').datetimepicker().on('dp.hide', function(ev) {
        var end_date = $('#end_date').val();
        $('#start_date').data("DateTimePicker").maxDate(new Date(end_date));
        if (end_date != old_end_date) {
            old_end_date = $('#end_date').val();
            filter = '';
            getReport();
        }
    });

    getReport();

    $(document).on('change', '#network', function() {
        resetFilter();
        getReport();
    });

    $('.filter_row').on('click', '.remove_filter', function() {
        resetFilter();
        getReport();
    });

    $('.filter_row').on('click', '.export_button', function() {
        var formData = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            network: $('#network').val(),
            filter_option: filterOptions,
        };
        var query = $.param(formData);
        window.location = '/report/performance/generateReportExcel?' + query;
    });

    $('.graph_column.clickable').on('click', function() {
        filterOptions.filterType = $(this).find('.graph_section').attr('id');
        filterOptions.filterHeader = $(this).attr('data-title');
        var filterData = '<div class="filter_section">Graph: ' + filterOptions.filterHeader + '<span class="glyphicon glyphicon-remove-circle remove_filter"></span></div><div class="filter_section export_button">Export</div>';
        filterOptions.type = 'graph_filter';
        $('.performance_graph_row').hide();
        $('.drilldown_section').show();
        $('.filter_graph').attr('id', filterOptions.filterType);
        $('.filter_row').html(filterData);
        graphOption.width = 800;
        graphOption.height = 300;
        getReport();
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
        success: function(data) {
            graphData = data.timelineGraph;
            graphType = data.graphType;
            overAllGraph = data.overAllGraph;
            graphGoals = data.graph_goal;

            graphColumn = graphType.graphColumn;
            drawAggregationTable(data.reportAggregationData);

            if (overAllGraph.total_patient != 0) {
                if (filterOptions.filterType == '') {
                    $('.performance_graph_row').show();
                } else {
                    drillDownData = data.drilldown;
                    drawDrillDownTable();
                }
                $('.no_data_received').hide();
                drawGoalChart(data.userCount);
                drawCompareChart(data.userCount);
                drawOverallChart();
            } else {
                $('.performance_graph_row').hide();
                $('.no_data_received').show();
            }
        },
        error: function() {
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

    types.forEach(function(type) {

        if (filterOptions.filterType != '' && filterOptions.filterType != type) {
            return true;
        }

        var data = new google.visualization.DataTable();

        graphColumn[type].forEach(function(columnName) {
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
                    temp.push(Math.round(graphData[key]['contactAttempted'] / userCount));
                    temp.push(graphGoals[type]);
                    break;
                case 'avgReached':
                    temp.push(Math.round(graphData[key]['reached'] / userCount));
                    temp.push(graphGoals[type]);
                    break;
                case 'avgScheduled':
                    temp.push(Math.round(graphData[key]['appointmentScheduled'] / userCount));
                    temp.push(graphGoals[type]);
                    break;
            }

            data.addRow(temp);

        }
        if (filterOptions.filterType != '' && filterOptions.filterType == type) {
            options.chartArea.width = '85%';
            options.chartArea.height = '70%';
            options.hAxis.showTextEvery = 'automatic';
        } else {
            options.hAxis.showTextEvery = i - 1;
        }

        var chart = new google.visualization.ComboChart($('#' + type)[0]);
        chart.draw(data, options);
    });
}

function drawOverallChart() {

    for (var key in overAllGraph) {
        $('.' + key).text(overAllGraph[key]);
    }
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

    types.forEach(function(type) {

        if (filterOptions.filterType != '' && filterOptions.filterType != type) {
            return true;
        }

        var data = new google.visualization.DataTable();

        graphColumn[type].forEach(function(columnName) {
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
                    temp.push(Math.round(graphData[key]['appointmentScheduled'] / userCount));
                    temp.push(Math.round(graphData[key]['dropped'] / userCount));
                    break;
                case 'keptAppointment_vs_missed':
                    temp.push(Math.round(graphData[key]['keptAppointment'] / userCount));
                    temp.push(Math.round(graphData[key]['missedAppointment'] / userCount));
                    break;
                case 'receivedReport_vs_pending':
                    temp.push(Math.round(graphData[key]['reportsReceived'] / userCount));
                    temp.push(Math.round(graphData[key]['reportsDue'] / userCount));
                    break;
            }

            data.addRow(temp);

        }

        if (filterOptions.filterType != '' && filterOptions.filterType == type) {
            options.chartArea.width = '85%';
            options.chartArea.height = '70%';
            options.hAxis.showTextEvery = 'automatic';
        }

        var chart = new google.visualization.AreaChart($('#' + type)[0]);
        chart.draw(data, options);
    });
}

function resetFilter() {
    filterOptions = {
        'filterType': '',
        'type': 'no_filter',
        'filterHeader': '',
    };
    graphOption.width = 250;
    graphOption.height = 200;
    $('.filter_row').html('');
    $('.performance_graph_row').show();
    $('.drilldown_section').hide();
    drillDownData = {};
    $('.filter_graph').attr('id', '');
}

function drawDrillDownTable() {
    var content = '';
    content += '<div class="row head_row arial_bold">'
    for (var columnName in drillDownData.columns) {
        content += '<span>' + drillDownData.columns[columnName] + '</span>';
    }
    content += '</div>';
    content += '<div class="row drilldown_data_content arial">';
    for (var row in drillDownData.data) {
        content += '<div class="drilldown_data_row">';
        for (var rowData in drillDownData.data[row]) {
            content += '<span>' + drillDownData.data[row][rowData] + '</span>';
        }
        content += '</div>';
    }
    content += '<//div>';
    $('.drilldown_data').html(content);
}

function drawAggregationTable(data) {
    var content = '';
    content += '<span class="aggregation_row title_section">Report Data</span>';
    for (var index in data) {
        content += '<span class="aggregation_row">';
        content += '<span class="aggregation_column">' + index + '</span>';
        content += '<span class="aggregation_column">' + data[index] + '</span>';
        content += '</span>';
    }
    $('.performance_report_aggregation').html(content);
}