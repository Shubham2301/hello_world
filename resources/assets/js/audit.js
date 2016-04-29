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
    $('#end_date').on('change', function () {
        refreshReports();
    });
    $('#start_date').on('change', function () {
        refreshReports();
    });
    var old_start_date = $('#start_date').val();
    var old_end_date = $('#end_date').val();
    $('#start_date').datetimepicker().on('dp.hide', function (ev) {
        var start_date = $('#start_date').val();
        if (start_date != old_start_date) {
            old_start_date = $('#start_date').val();
            refreshReports();
        }
    });
    $('#end_date').datetimepicker().on('dp.hide', function (ev) {
        var end_date = $('#end_date').val();
        $('#start_date').data("DateTimePicker").maxDate(new Date(end_date));
        if (end_date != old_end_date) {
            old_end_date = $('#end_date').val();
            refreshReports();
        }
    });


    refreshReports();

    $('#select_network').on('change', function () {
        refreshReports();
    });
});

function refreshReports() {
    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        'network_id': $('#select_network').val(),
    };

    $.ajax({
        url: '/getauditreports',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            
            var auditLogs = $.parseJSON(e);

            var content = '';
            
            if (auditLogs.length > 1) {
                auditLogs.forEach(function (audit) {
                    content += '<div class="row"><div class="col-xs-3 info_col"><p>' + audit.date + '</p></div><div class="col-xs-2 info_col"><p>' + audit.user_name + '</p></div><div class="col-xs-2 info_col"><p>' + audit.network_name + '</p></div><div class="col-xs-5 info_col"><p>' + audit.action + '</p></div></div>';
                });
            }
            
            $('#audit_reports').html(content);
            
        },
        error: function () {
            $('p.alert_message').text('Error generating report');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });


}
