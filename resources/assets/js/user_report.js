$(document).ready(function () {

    getReport();

});

function getReport() {

    $.ajax({
        url: '/report/user_report/show',
        type: 'GET',
        contentType: 'application/json',
        async: false,
        success: function (data) {
            drawAggregationTable(data.total_active_user, data.total_inactive_user);
            drawUserTable(data.networkData);
        },
        error: function () {
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
        content += '<div class="table_row row">';
        content += '<div class="table_column col-xs-2">' + data[row]['name'] + '</div>';
        content += '<div class="table_column col-xs-2 text-center">' + data[row]['activeNetworkUser'] + '</div>';
        content += '<div class="table_column col-xs-2 text-center">' + data[row]['inactiveNetworkUser'] + '</div>';
        content += '<div class="table_column col-xs-2 text-center">' + ( data[row]['activeNetworkUser'] + data[row]['inactiveNetworkUser'] ) + '</div>';
        content += '</div>';
    }
    $('.report_table').html(content);
}
