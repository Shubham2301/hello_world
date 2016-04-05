$(document).ready(function () {

    $('#subscriber_dob').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $('[data-toggle="tooltip"]').tooltip();
    $('.appointment_confirmed').hide();
    $('#confirm_appointment').on('click', function () {
        scheduleAppointment();
    });

    $('#schedule_new_patient').on('click', function () {
        $('#form_patient_id').prop('disabled', true);
        $('#form_schedule_another_appointment').submit();
    });
    $('#cancel_appointment').on('click', function () {
        $('#form_patient_id').prop('disabled', true);
        $('#form_schedule_another_appointment').submit();
    });
    $('#back').on('click', function(){
        $('#form_schedule_another_appointment').attr('action', "/providers");
        $('#form_schedule_another_appointment').submit();
    });
});

function scheduleAppointment() {

    var patient_id = $('#form_patient_id').val();
    var provider_id = $('#form_provider_id').val();
    var practice_id = $('#form_practice_id').val();
    var location_id = $('#form_location_id').val();
    var appointment_type_key = $('#form_appointment_type_id').val();
    var appointment_time = $('#form_appointment_date').val() + ' ' + $('#form_appointment_time').val();
     var appointment_type_name = $('#form_appointment_type_name').val();

    var formData = {
        'patient_id': patient_id,
        'provider_id': provider_id,
        'practice_id': practice_id,
        'location_id': location_id,
        'appointment_type': appointment_type_name,
        'appointment_type_key': appointment_type_key,
        'appointment_time': appointment_time
    };

    $.ajax({
        url: '/appointments/schedule',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            if($('#form_action').val() === 'careconsole')
            {
                $('#back_to_console').show();
                $('#schedule_new_patient').hide();
            }
            else
            {
                $('#back_to_console').hide();
                $('#schedule_new_patient').show();
            }
            $('.appointment_confirm').hide();
            $('.appointment_confirmed').show();
            $('#back').addClass('hide');

        },
        error: function () {},
        cache: false,
        processData: false
    });

}
