'use strict';

$(document).ready(function () {
    $('.appointment_confirmed').hide();
    $('#confirm_appointment').on('click', function () {
        alert('Appoitment has been scheduled!');
        $('.appointment_confirm').hide();
        $('.appointment_confirmed').show();
    });
});
//# sourceMappingURL=appointment.js.map
