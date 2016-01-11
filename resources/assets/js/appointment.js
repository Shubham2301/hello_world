$(document).ready(function () {
    $('.appointment_confirmed').hide();
    $('#confirm_appointment').on('click', function () {
        $('.appointment_confirm').hide();
        $('.appointment_confirmed').show();
    });
});
