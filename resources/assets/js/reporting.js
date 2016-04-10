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
    $('#end_date').on('change', function(){
        getReport();
    });
    $('#start_date').on('change', function(){
        getReport();
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

function getReport() {
    var formData = {
        'start_date': $('#start_date').val(),
        'end_date': $('#end_date').val()
    };
    $.ajax({
        url: '/reports/updatereports',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
            $('#total_referred').html(info.total_referred);
            $('#to_be_called').html(info.to_be_called);
            
            var content = '';
            for (var key in info.scheduled.practices) {
              if (info.scheduled.practices.hasOwnProperty(key)) {
                content += '<span class="info_row"><span>'+key+'</span><span style="float: right;">'+info.scheduled.practices[key]+'</span></span><br>';
              }
            }
            $('#scheduled').html(content);

            var content = '';
            for (var key in info.referred_by.practices) {
              if (info.referred_by.practices.hasOwnProperty(key)) {
                content += '<span class="info_row"><span>'+key+'</span><span style="float: right;">'+info.referred_by.practices[key]+'</span></span><br>';
              }
            }
            $('#referred_by').html(content);
            
            for (var key in info.appointment_status) {
              if (info.appointment_status.hasOwnProperty(key)) {
                $('#'+key).html(info.appointment_status[key]);
              }
            }

            
        },
        error: function () {
            $('p.alert_message').text('Error generating report');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
