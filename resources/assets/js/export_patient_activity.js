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
    });
    $('#end_date').datetimepicker().on('dp.hide', function(ev) {
        var end_date = $('#end_date').val();
        $('#start_date').data("DateTimePicker").maxDate(new Date(end_date));
    });

    $('#export_patient_activity_excel').on('click', function() {
        var formData = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            network_id: $('#network_id').val(),
        };
        var query = $.param(formData);
        window.location = '/report/export-patient-activity/get-patient-excel?' + query;
    });

});