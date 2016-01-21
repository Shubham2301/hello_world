'use strict';

$(document).ready(function () {
    $('.appointment_confirmed').hide();
    $('#confirm_appointment').on('click', function () {
        scheduleAppointment();
    });
});

function scheduleAppointment() {

    var patient_id = 1;
    var provider_id = 1;
    var location_id = 1;
    var appointment_type = 1;
    var appointment_time = 1;

    var formData = {
        'patient_id': patient_id,
        'provider_id': provider_id,
        'location_id': location_id,
        'appointment_type': appointment_type,
        'appointment_time': appointment_time
    };

    $.ajax({
        url: '/appointments/schedule',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            $('.appointment_confirm').hide();
            $('.appointment_confirmed').show();
        },
        error: function error() {},
        cache: false,
        processData: false
    });
}
//# sourceMappingURL=appointment.js.map
