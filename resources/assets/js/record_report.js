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
//    var old_start_date = $('#start_date').val();
//    var old_end_date = $('#end_date').val();

    $('.network_selector').on('change', function() {
        getNetworkWebFormList($('.network_selector').val());
    });

    $('#generate_record_xml').on('click', function() {
        var formData = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            network_id: $('#network_id').val(),
            web_form_id: $('#web_form_id').val(),
        };
        var query = $.param(formData);
        window.location = '/report/records/generateReportExcel?' + query;
    });

});

function getNetworkWebFormList(networkID) {
    $.ajax({
        url: '/report/records/getWebFormList/' + networkID,
        type: 'GET',
        contentType: 'text/html',
        async: false,
        success: function success(webFormList) {
            var content = '';
            if (webFormList.length === 0) {
                $('p.alert_message').text('No webform available for the selected network');
                $('#alert').modal('show');
            } else {
                for (var id in webFormList) {
                    content += '<option value="' + id + '">' + webFormList[id] + '</option>';
                }
            }
            $('.web_form_selector').html(content);
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}