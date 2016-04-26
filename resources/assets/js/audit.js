$(document).ready(function () {

    refreshReports();

    $('#select_network').on('change', function () {
        refreshReports();
    });
});

function refreshReports() {
    var formData = {
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