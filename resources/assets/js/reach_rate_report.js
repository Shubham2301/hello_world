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
        var header = $(this).text();
        $('.action_modal_title.reach_report_patient_list').text($.trim(header));
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
    var headerContent = '';
    reportResult.forEach(function (result) {
        var patientListItem = '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span></li>';
        switch (metricName) {
            case 'contact_attempted':
                headerContent = '<span>Name</span><span>Request Received</span><span>Contact Attempts</span><span>Days Pending</span>';
                if ("reached" in result || "not_reached" in result) {
                    var requestReceived = result.request_received || '-';
                    var contactAttempts = result.contact_attempts || '-';
                    var daysPending = result.reached_stage_change || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + requestReceived + '</span><span>' + contactAttempts + '</span><span>' + daysPending + '</span></li>';
                }
                break;
            case 'reached':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Appointment Type</span>';
                if ("reached" in result) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + appointmentType + '</span></li>';
                }
                break;
            case 'not_reached':
                if ("not_reached" in result && !("reached" in result)) {
                    content += patientListItem;
                }
                break;
            case 'appointment_scheduled':
                if ("appointment_scheduled" in result) {
                    content += patientListItem;
                }
                break;
            case 'not_scheduled':
                if ("reached" in result && !("appointment_scheduled" in result)) {
                    content += patientListItem;
                }
                break;
            case 'appointment_completed':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Appointment Type</span><span>Days Pending</span>';
                if ("appointment_completed" in result) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var daysPending = result.show_stage_change || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + appointmentType + '</span><span>' + daysPending + '</span></li>';
                }
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
                headerContent = '<span>Name</span><span>PCP</span><span>Scheduled For</span><span>Days Pending</span>';
                if ("reports" in result || ("appointment_completed" in result && result.appointment_completed == config.appointment_completed.show)) {
                    var daysPending = result.days_in_stage_before_archive || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var pcpName = result.pcp_name || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + pcpName + '</span><span>' + scheduledFor + '</span><span>' + daysPending + '</span></li>';
                }
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
    });
    $('ul.patient_listing').html(content);
    $('.report_patient_list_header').html(headerContent);
    if (headerContent != '') {
        $('.modal-dialog').addClass('wide_modal_dialog');
    } else {
        $('.modal-dialog').removeClass('wide_modal_dialog');
    }
    if (content != '') {
        $('#patientList').modal('show');
    }
}
