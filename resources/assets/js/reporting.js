$(document).ready(function () {
    $('#start_date').datetimepicker({
        defaultDate: new Date(),
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });
    $('#end_date').datetimepicker({
        defaultDate: new Date(),
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });
});
