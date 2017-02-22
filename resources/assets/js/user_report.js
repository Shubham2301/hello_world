$(document).ready(function() {

    getReport();

    $('.report_table').on('click', '.table_row.drilldown', function() {
        $('#export_all').hide();
        $('.report_table').addClass('user_report_table');
        getNetworkData($(this).attr('data-network_id'), $(this).attr('data-network_name'));
    });

    $('.report_table').on('click', '#close_drilldown', function() {
        $('.report_table').removeClass('user_report_table');
        $('#export_all').show();
        getReport();
    });

    $('.report_table').on('click', '#export_table', function() {
        var formData = {
            network_id: $(this).attr('data-network_id'),
        };
        var query = $.param(formData);
        window.location = '/report/user_report/generateReportExcel?' + query;
    });

    $('#export_all').on('click', function() {
        window.location = '/report/user_report/generateReportExcel';
    });

});

function getReport() {

    $.ajax({
        url: '/report/user_report/show',
        type: 'GET',
        contentType: 'application/json',
        async: false,
        success: function(data) {
            drawAggregationTable(data.total_active_user, data.total_inactive_user);
            drawUserTable(data.networkData);
        },
        error: function() {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function drawAggregationTable(active_users, inactive_users) {
    var content = '';
    content += '<span class="aggregation_row title_section">Report Data</span>';
    content += '<span class="aggregation_row"><span class="aggregation_column">Active Users</span><span class="aggregation_column">' + active_users + '</span></span>';
    content += '<span class="aggregation_row"><span class="aggregation_column">Inactive Users</span><span class="aggregation_column">' + inactive_users + '</span></span>';
    content += '<span class="aggregation_row"><span class="aggregation_column">Total Users</span><span class="aggregation_column">' + (active_users + inactive_users) + '</span></span>';
    $('.user_report_aggregation').html(content);
}

function drawUserTable(data) {
    var content = '';
    content += '<div class="table_row row title"><div class="table_column col-xs-2">Network Name</div><div class="table_column col-xs-2 text-center">Active Users</div><div class="table_column col-xs-2 text-center">Inactive Users</div><div class="table_column col-xs-2 text-center">Total</div></div>';
    for (var row in data) {
        if (row == 0) {
            content += '<div class="table_row row ocuhub_row arial_bold" data-network_id="' + row + '">';
        } else if ((data[row]['activeNetworkUser'] + data[row]['inactiveNetworkUser']) != 0) {
            content += '<div class="table_row row drilldown" data-network_id="' + row + '" data-network_name="' + data[row]['name'] + '">';
        } else {
            content += '<div class="table_row row" data-network_id="' + row + '">';
        }
        content += '<div class="table_column col-xs-2">' + data[row]['name'] + '</div>';
        content += '<div class="table_column col-xs-2 text-center">' + data[row]['activeNetworkUser'] + '</div>';
        content += '<div class="table_column col-xs-2 text-center">' + data[row]['inactiveNetworkUser'] + '</div>';
        content += '<div class="table_column col-xs-2 text-center">' + (data[row]['activeNetworkUser'] + data[row]['inactiveNetworkUser']) + '</div>';
        content += '</div>';
    }
    $('.report_table').html(content);
}

function getNetworkData(network_id, network_name) {

    var formData = {
        network_id: network_id
    };

    $.ajax({
        url: '/report/user_report/network_data',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function(data) {

            if (data.length) {
                var content = '';
                content += '<div class="row"><div class="col-xs-12 drilldown_controls"><span class="filter_section">Network: ' + network_name + ' <span id="close_drilldown" class="glyphicon glyphicon-remove-circle remove_filter"></span></span><span class="filter_section" id="export_table" data-network_id="' + network_id + '">Export</span></div></div>';
                content += '<div class="table_row row title"><div class="table_column col-xs-2 ">User Name</div><div class="table_column col-xs-3 ">User Email</div><div class="table_column col-xs-2 ">User Type</div><div class="table_column col-xs-2 ">User Level</div><div class="table_column col-xs-2 ">Organization</div><div class="table_column col-xs-1 ">Status</div></div>';
                for (var row in data) {
                    content += '<div class="table_row row">';
                    content += '<div class="table_column col-xs-2">' + data[row]['Name'] + '</div>';
                    content += '<div class="table_column col-xs-3 user_report_table_col">' + data[row]['Email'] + '</div>';
                    content += '<div class="table_column col-xs-2">' + data[row]['Type'] + '</div>';
                    content += '<div class="table_column col-xs-2">' + data[row]['Level'] + '</div>';
                    content += '<div class="table_column col-xs-2">' + data[row]['Organization'] + '</div>';
                    content += '<div class="table_column col-xs-1">' + data[row]['Status'] + '</div>';
                    content += '</div>';
                }
                $('.report_table').html(content);
            }
        },
        error: function() {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });

}