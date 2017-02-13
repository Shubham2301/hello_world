var reportResult = [];
var config = [];
var filter = '';

$(document).ready(function() {
    getReport();

    $('.patient_list').on('click', function() {
        var header = $(this).attr('data-name');
        $('.action_modal_title.reach_report_patient_list').text(header);
        getPatientList($(this).attr('id'));
    });
    $('.referred_by_practice_list').on('change', function() {
        filter = $(this).val();
        getReport(filter);
    });

    $('.generate_report_excel').on('click', function() {
        var formData = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            filter_option: filter,
            export_field: $(this).attr('id')
        };
        var query = $.param(formData);
        window.location = '/report/reach_report/generateReportExcel?' + query;
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
        success: function(data) {
            for (var key in data) {
                $('.' + key).html(data[key]);
            }
            reportResult = data['report_data'];
            config = data['config'];

            var dropdownContent = '';
            var referredBy = data['referred_by_practice'];

            dropdownContent += '<option value="">All</option>';
            for (var referredByPractice in referredBy) {
                if (referredByPractice == filter) {
                    dropdownContent += '<option value="' + referredByPractice + '" selected>' + referredBy[referredByPractice] + '</option>';
                } else {
                    dropdownContent += '<option value="' + referredByPractice + '">' + referredBy[referredByPractice] + '</option>';
                }
            }
            $('.referred_by_practice_list').html(dropdownContent);

        },
        error: function() {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function getPatientList(metricName) {
    var content = '';
    var headerContent = '';
    $('.generate_report_excel').hide();
    reportResult.forEach(function(result) {

        if (filter != '') {
            if (result['referred_by_practice'] ? result['referred_by_practice'] != filter : true) {
                return;
            }
        }

        headerContent = '<span>Name</span><span>Date of Birth</span>';
        var patientListItem = '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + result.dob + '</span></li>';
        switch (metricName) {
            case 'contact_attempted':
                headerContent = '<span>Name</span><span>Request Received</span><span>Contact Attempts</span><span>Days Pending</span>';
                if ("reached" in result || "not_reached" in result) {
                    var requestReceived = result.request_received || '-';
                    var contactAttempts = result.contact_attempts || '-';
                    var daysPending = (result.reached_stage_change >= 0) ? result.reached_stage_change : result.days_in_contact_status;
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + requestReceived + '</span><span>' + contactAttempts + '</span><span>' + daysPending + '</span></li>';
                }
                break;
            case 'reached':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Existing Relationship</span><span>Appointment Type</span>';
                if ("reached" in result) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    if (scheduledToPractice != '-') {
                        var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                        scheduledToPractice += '(<span class ="arial_italic">' + scheduledToPracticeLocation + '</span>)';
                    }s

                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    var existingRelationship = result.existing_relationship || '0';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + existingRelationship + '</span><span>' + appointmentType + '</span></li>';
                }
                break;
            case 'not_reached':
                headerContent = '<span>Name</span><span>Request Received</span><span>Contact Attempts</span><span>Days Pending</span>';
                if ("not_reached" in result && !("reached" in result)) {
                    var requestReceived = result.request_received || '-';
                    var contactAttempts = result.contact_attempts || '-';
                    var daysPending = (result.reached_stage_change >= 0) ? result.reached_stage_change : result.days_in_contact_status;
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + requestReceived + '</span><span>' + contactAttempts + '</span><span>' + daysPending + '</span></li>';
                }
                $('.generate_report_excel').attr('id', metricName);
                $('.generate_report_excel').show();
                break;
            case 'appointment_scheduled_existing_relationship':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Appointment Type</span>';
                if ("appointment_scheduled" in result && result.appointment_scheduled == config.appointment_status.scheduled_appointment_existing_relationship) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + appointmentType + '</span></li>';
                }
                break;
            case 'appointment_scheduled_non_existing_relationship':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Appointment Type</span>';
                if ("appointment_scheduled" in result && result.appointment_scheduled == config.appointment_status.scheduled_appointment_non_existing_relationship) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + appointmentType + '</span></li>';
                }
                break;
            case 'past_appointment_existing_relationship':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Appointment Type</span>';
                if ("appointment_scheduled" in result && result.appointment_scheduled == config.appointment_status.past_appointment_existing_relationship) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + appointmentType + '</span></li>';
                }
                break;
            case 'past_appointment_non_existing_relationship':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Appointment Type</span>';
                if ("appointment_scheduled" in result && result.appointment_scheduled == config.appointment_status.past_appointment_non_existing_relationship) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + appointmentType + '</span></li>';
                }
                break;
            case 'not_scheduled':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Scheduled On</span><span>Appointment Type</span>';
                if ("reached" in result && !("appointment_scheduled" in result)) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var scheduledOn = result.scheduled_on || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + scheduledOn + '</span><span>' + appointmentType + '</span></li>';
                }
                $('.generate_report_excel').attr('id', metricName);
                $('.generate_report_excel').show();
                break;
            case 'appointment_completed':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Appointment Type</span><span>Days Pending</span>';
                if ("appointment_completed" in result) {
                    var scheduledToPractice = result.scheduled_to_practice || '-';
                    var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                    var scheduledToProvider = result.scheduled_to_provider || '-';
                    var scheduledFor = result.scheduled_for || '-';
                    var appointmentType = result.appointment_type || '-';
                    var daysPending = (result.show_stage_change >= 0) ? result.show_stage_change : result.days_in_appointment_completed;
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + appointmentType + '</span><span>' + daysPending + '</span></li>';
                }
                break;
            case 'show':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Appointment Type</span><span>Days Pending</span>';
                if ("appointment_completed" in result) {
                    if (result.appointment_completed == config.appointment_completed.show) {
                        var scheduledToPractice = result.scheduled_to_practice || '-';
                        var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                        var scheduledToProvider = result.scheduled_to_provider || '-';
                        var scheduledFor = result.scheduled_for || '-';
                        var appointmentType = result.appointment_type || '-';
                        var daysPending = (result.show_stage_change >= 0) ? result.show_stage_change : result.days_in_appointment_completed;
                        content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + appointmentType + '</span><span>' + daysPending + '</span></li>';
                    }
                }
                break;
            case 'no_show':
                headerContent = '<span>Name</span><span>Scheduled To Practice</span><span>Scheduled To Practice Location</span><span>Scheduled To Provider</span><span>Scheduled For</span><span>Appointment Type</span><span>Days Pending</span>';
                if ("appointment_completed" in result) {
                    if (result.appointment_completed == config.appointment_completed.no_show) {
                        var scheduledToPractice = result.scheduled_to_practice || '-';
                        var scheduledToPracticeLocation = result.scheduled_to_practice_location || '-';
                        var scheduledToProvider = result.scheduled_to_provider || '-';
                        var scheduledFor = result.scheduled_for || '-';
                        var appointmentType = result.appointment_type || '-';
                        var daysPending = (result.show_stage_change >= 0) ? result.show_stage_change : result.days_in_appointment_completed;
                        content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + scheduledToPractice + '</span><span>' + scheduledToPracticeLocation + '</span><span>' + scheduledToProvider + '</span><span>' + scheduledFor + '</span><span>' + appointmentType + '</span><span>' + daysPending + '</span></li>';
                    }
                }
                $('.generate_report_excel').attr('id', metricName);
                $('.generate_report_excel').show();
                break;
            case 'exam_report':
                headerContent = '<span>Name</span><span>PCP</span><span>Scheduled For</span><span>Days Pending</span>';
                if ("reports" in result || ("appointment_completed" in result && result.appointment_completed == config.appointment_completed.show)) {
                    var daysPending = (result.days_in_stage_before_archive >= 0) ? result.days_in_stage_before_archive : result.days_in_exam_report;
                    var scheduledFor = result.scheduled_for || '-';
                    var pcpName = result.pcp_name || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + pcpName + '</span><span>' + scheduledFor + '</span><span>' + daysPending + '</span></li>';
                }
                break;
            case 'reports':
                headerContent = '<span>Name</span><span>PCP</span><span>Scheduled For</span><span>Days Pending</span>';
                if ("reports" in result) {
                    var daysPending = (result.days_in_stage_before_archive >= 0) ? result.days_in_stage_before_archive : result.days_in_exam_report;
                    var scheduledFor = result.scheduled_for || '-';
                    var pcpName = result.pcp_name || '-';
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + pcpName + '</span><span>' + scheduledFor + '</span><span>' + daysPending + '</span></li>';
                }
                break;
            case 'no_reports':
                headerContent = '<span>Name</span><span>PCP</span><span>Scheduled For</span><span>Days Pending</span>';
                if ("appointment_completed" in result) {
                    if ((result.appointment_completed == config.appointment_completed.show) && !("reports" in result)) {
                        var daysPending = (result.days_in_stage_before_archive >= 0) ? result.days_in_stage_before_archive : result.days_in_exam_report;
                        var scheduledFor = result.scheduled_for || '-';
                        var pcpName = result.pcp_name || '-';
                        content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + pcpName + '</span><span>' + scheduledFor + '</span><span>' + daysPending + '</span></li>';
                    }
                }
                $('.generate_report_excel').attr('id', metricName);
                $('.generate_report_excel').show();
                break;
            case 'pending_patient':
                headerContent = '<span>Name</span><span>Request Received</span>';
                if (!("reached" in result) && !("not_reached" in result)) {
                    if ((result.patient_type == config.patient_type.new) || ("repeat_count" in result))
                        content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + result.request_received + '</span></li>';
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
                headerContent = '<span>Name</span><span>Date Archived</span><span>Reason for archiving</span>';
                if ("archived" in result) {
                    content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + result.archive_date + '</span><span>' + result.archive_reason + '</span></li>';
                }
                break;
            case 'closed':
                headerContent = '<span>Name</span><span>Date Archived</span><span>Reason for archiving</span>';
                if ("archived" in result) {
                    if (result.archived == config.archive.closed)
                        content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + result.archive_date + '</span><span>' + result.archive_reason + '</span></li>';
                }
                break;
            case 'incomplete':
                headerContent = '<span>Name</span><span>Date Archived</span><span>Reason for archiving</span>';
                if ("archived" in result) {
                    if (result.archived == config.archive.incomplete)
                        content += '<li><span><a href="/records?patient_id=' + result.patient_id + '">' + result.patient_name + '</a></span><span>' + result.archive_date + '</span><span>' + result.archive_reason + '</span></li>';
                }
                break;
            case 'active_patient':
                if (!("archived" in result)) {
                    content += patientListItem;
                }
                break;
            case 'repeat_patient':
                if ("repeat_count" in result) {
                    content += patientListItem;
                }
                break;
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