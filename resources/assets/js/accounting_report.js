$(document).ready(function() {

    var cur_date = new Date();
    var set_start_date = new Date(cur_date.getTime());

    $('.start_date').datetimepicker({
        defaultDate: set_start_date.setDate(cur_date.getDate() - 31),
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
    });
    set_start_date = new Date(cur_date.getTime());
    $('.end_date').datetimepicker({
        defaultDate: cur_date,
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
        minDate: set_start_date.setDate(cur_date.getDate() - 30),
    });

    $('.start_date').datetimepicker().on('dp.hide', function(ev) {
        var report = $(this).attr('report');
        var start_date = $(".start_date[report='" + report + "']").val();
        $(".end_date[report='" + report + "']").data("DateTimePicker").minDate(new Date(start_date));
    });
    $('.end_date').datetimepicker().on('dp.hide', function(ev) {
        var report = $(this).attr('report');
        var end_date = $(".end_date[report='" + report + "']").val();
        $(".start_date[report='" + report + "']").data("DateTimePicker").maxDate(new Date(end_date));
    });


    $('.network_selector.practice_appointment').on('change', function() {

        var networkList = {};
        networkList[0] = $('.network_selector.practice_appointment').val();

        var formData = {
            'networks': networkList,
            'exclude_manually_created': false
        }

        var practices = refreshPractices(formData);

    });

    $('.export_button').on('click', function() {
        var report_type = $(this).attr('id');
        switch (report_type) {
            case 'provider_billing':
                getProviderBillingReport();
                break;
            case 'payer_billing':
                getPayerBillingReport();
                break;
            case 'practice_appointment':
                getPracticeAppointmentReport();
                break;
            default:
        }
    });
});


function getProviderBillingReport() {

    var formData = {
        network_id: $('.network_selector.provider_billing').val(),
    };

    var query = $.param(formData);
    window.location = '/report/accounting_report/provider_billing?' + query;

}

function getPayerBillingReport() {

    var formData = {
        network_id: $('.network_selector.payer_billing').val(),
        start_date: $(".start_date[report='payer_billing']").val(),
        end_date: $(".end_date[report='payer_billing']").val()
    };

    var query = $.param(formData);
    window.location = '/report/accounting_report/payer_billing?' + query;
}

function getPracticeAppointmentReport() {
    var formData = {
        practice_id: $('.practice_selector.practice_appointment').val(),
        network_id: $('.network_selector.practice_appointment').val()
    };

    if (formData['practice_id'] == -1) {
        $('p.alert_message').text('Please select a practice');
        $('#alert').modal('show');

        return;
    }


    var query = $.param(formData);
    window.location = '/report/accounting_report/practice_appointments?' + query;
}

function refreshPractices(formData) {

    $.ajax({
        url: '/administration/practices/by-network',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var practices = $.parseJSON(e);
            var content = '<option value="-1" selected>Select a practice</option>';
            content += '<option value="all">All</option>';
            for (var index in practices) {
                content += '<option value="' + practices[index].id + '">' + practices[index].name + '</option>';
            }
            $('.practice_selector.practice_appointment').html(content);

        },
        error: function error() {
            $('p.alert_message').text('Error getting network practices.');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}