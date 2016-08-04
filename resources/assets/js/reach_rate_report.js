var reportResult = [];
var config = [];

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
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
    $('.patient_list').on('click', function () {
        getPatientList($(this).attr('id'));
    });
});

function getReport(filter) {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        filter_option: filter,
    };

    $.ajax({
        url: '/report/reach_report/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (data) {
            for (var key in data) {
                $('.' + key).html(data[key]);

            }
            reportResult = data['report_data'];
            config = data['config'];
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function getPatientList(metricName) {
    var content = '';
    reportResult.forEach(function (result) {
        var patientListItem = '<li><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></li>';
        switch (metricName) {
            case 'contact_attempted':
                if ("reached" in result || "not_reached" in result)
                    content += patientListItem;
                break;
            case 'reached':
                if ("reached" in result)
                    content += patientListItem;
                break;
            case 'not_reached':
                if ("not_reached" in result && !("reached" in result))
                    content += patientListItem;
                break;
            case 'appointment_scheduled':
                if ("appointment_scheduled" in result)
                    content += patientListItem;
                break;
            case 'not_scheduled':
                if ("reached" in result && !("appointment_scheduled" in result))
                    content += patientListItem;
                break;
            case 'appointment_completed':
                if ("appointment_completed" in result)
                    content += patientListItem;
                break;
            case 'show':
                if ("appointment_completed" in result) {
                    if (result.appointment_completed == config.appointment_completed.show)
                        content += patientListItem;
                }
                break;
            case 'no_show':
                if ("appointment_completed" in result) {
                    if (result.appointment_completed == config.appointment_completed.no_show)
                        content += patientListItem;
                }
                break;
            case 'exam_report':
                if ("reports" in result || ("appointment_completed" in result && result.appointment_completed == config.appointment_completed.show))
                    content += patientListItem;
                break;
            case 'reports':
                if ("reports" in result)
                    content += patientListItem;
                break;
            case 'no_reports':
                if ("appointment_completed" in result) {
                    if ((result.appointment_completed == config.appointment_completed.show) && !("reports" in result))
                        content += patientListItem;
                }
                break;
            case 'pending_patient':
                if (!("reached" in result) || !("not_reached" in result)) {
                    if ((result.patient_type == config.patient_type.new) || ("repeat_count" in result))
                        content += patientListItem;
                }
                break;
            case 'patient_count':
                content += patientListItem;
                break;
            case 'new_patient':
                if ("patient_type" in result) {
                    if (result.patient_type == config.patient_type.new)
                        content += patientListItem;
                }
                break;
            case 'existing_patients':
                if ("patient_type" in result) {
                    if (result.patient_type == config.patient_type.old)
                        content += patientListItem;
                }
                break;
            case 'completed':
                if ("archived" in result) {
                    content += patientListItem;
                }
                break;
            case 'success':
                if ("archived" in result) {
                    if (result.archived == config.archive.success)
                        content += patientListItem;
                }
                break;
            case 'dropout':
                if ("archived" in result) {
                    if (result.archived == config.archive.dropout)
                        content += patientListItem;
                }
                break;
            case 'active_patient':
                if (!("archived" in result)) {
                    content += patientListItem;
                }
            default:
                break;
        }
        $('ul.patient_listing').html(content);

    });
    $('#patientList').modal('show');
}
