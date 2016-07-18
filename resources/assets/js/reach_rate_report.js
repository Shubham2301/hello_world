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

function getReport() {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
    };

    $.ajax({
        url: '/report/reach_rate_report/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (data) {
            for (var key in data) {
                $('.'+key).html(data[key]);

            }
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}
