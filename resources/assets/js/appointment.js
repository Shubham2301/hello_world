$(document).ready(function() {
    loadFPCValidateModel();
    $('#subscriber_dob').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $('[data-toggle="tooltip"]').tooltip();
    $('.appointment_confirmed').hide();
    $('#confirm_appointment').on('click', function() {
        if (unfillfpcFields > 0) {
            $('#show_fpc_model').trigger('click');
            return;
        } else {
            scheduleAppointment();
        }
    });

    $('#schedule_new_patient').on('click', function() {
        $('#form_patient_id').prop('disabled', true);
        $('#form_schedule_another_appointment').submit();
    });
    $('#cancel_appointment').on('click', function() {
        $('#form_patient_id').prop('disabled', true);
        $('#form_schedule_another_appointment').submit();
    });
    $('#back').on('click', function() {
        $('#form_schedule_another_appointment').attr('action', "/providers");
        $('#form_schedule_another_appointment').submit();
    });

	$('#model_fpc_view').on('click', '.save_fpcdata', function() {
		$('.patient_id_fpc').val($('#form_patient_id').val());
		saveFPCRequiredFields();
	});
});
var unfillfpcFields = null;

function disableConfirmApptBttn() {
    $('.appointment_confirm>p>button').addClass('disable');
}

function enableConfirmApptBttn() {
    $('.appointment_confirm>p>button').removeClass('disable');
}

function checkApptBttn() {
    return $('.appointment_confirm>p>button').hasClass('disable');
}

function scheduleAppointment() {

    if (checkApptBttn()) {
        return;
    }

    //disableConfirmApptBttn();
	$('.appointment_confirm').hide();
	$('#schedule_apt_loader').css("display", "block");
    var patient_id = $('#form_patient_id').val();
    var provider_id = $('#form_provider_id').val();
    var practice_id = $('#form_practice_id').val();
    var location_id = $('#form_location_id').val();
    var appointment_type_key = $('#form_appointment_type_id').val();
    var appointment_time = $('#form_appointment_date').val() + ' ' + $('#form_appointment_time').val();
    var appointment_type_name = $('#form_appointment_type_name').val();
    var provider_acc_key = $('#form_provider_acc_key').val();
    var location_code = $('#form_location_code').val();
    var location_code = $('#form_location_code').val();

    var formData = {
        'patient_id': patient_id,
        'provider_id': provider_id,
        'practice_id': practice_id,
        'location_id': location_id,
        'location_code': location_code,
        'provider_acc_key': provider_acc_key,
        'appointment_type': appointment_type_name,
        'appointment_type_key': appointment_type_key,
        'appointment_time': appointment_time,
		'send_ccda_file':$('#send_ccda_checkbox').prop('checked')
    };


    $.ajax({
        url: '/appointments/schedule',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: true,
        success: function(e) {

			$('#schedule_apt_loader').css("display", "none");

            if ($('#form_action').val() === 'careconsole') {
                $('#back_to_console').show();
                $('#schedule_new_patient').hide();
            } else {
                $('#back_to_console').hide();
                $('#schedule_new_patient').show();
            }

            $('.appointment_confirmed').show();
            $('#back').addClass('hide');
        },
        error: function() {},
        cache: false,
        processData: false
    });

}

function loadFPCValidateModel() {
    var formData = {
        'patient_id': $('#form_patient_id').val(),
    };
    $.ajax({
        url: '/getfpcvalidateview',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var data = $.parseJSON(e);
            unfillfpcFields = data.validate_fpc_count;
            $('#model_fpc_view').html('');
            $('#model_fpc_view').html(data.validate_fpc_view);
            $('.field_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('.modal-backdrop').remove();

        },
        error: function() {
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function saveFPCRequiredFields() {
    var myform = document.getElementById("form_fpc_field");
    var fd = new FormData(myform);
    $.ajax({
        url: "/updatepatientdata",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function success(e) {
            var info = $.parseJSON(e);
            loadFPCValidateModel();
            var formData = {
                'id': $('#form_patient_id').val()
            };
            getPatientInfo(formData);
            $('.cancel_fpcdata').trigger('click');
        }
    });
}
