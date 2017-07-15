$(document).ready(function() {

    $('#export_hedis_supplementary').on('click', exportFile);

});

function exportFile() {

    $('.export_status').html('');
    var network_id = $('#network_id').val()
    var formData = {
        'network_id': network_id,
    };

    $.ajax({
        url: '/report/hedis_export/generate/',
        type: 'GET',
        contentType: 'text/html',
        async: true,
        data: $.param(formData),
        success: function success(e) {
            var status = $.parseJSON(e);
            var status_html = '<h4>Export Status</h4>';
            for (var key in status) {
                status_html += '<p>';
                status_html += '<span>' + key + ': </span>';
                status_html += '<span><i>' + status[key] + '</i></span>';
                status_html += '</p>';
            }
            $('.export_status').html(status_html);
            
            if(status['Mode of transfer'] === 'Not transferred. Data exported as file.') {
                window.location = '/report/hedis_export/export/' + network_id;
            }
        },
        error: function error() {
            $('p.alert_message').text('Error Generating file');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}