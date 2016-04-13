$(document).ready(function() {
    loadImportForm();
    $('#datetimepicker_action_date').datetimepicker({
        format: 'YYYY-MM-DD',
    });
    $('#manual_appointment_date').datetimepicker();

    var recall_icon_path = $('#recall_icon_path').val();
    var archive_icon_path = $('#archive_icon_path').val();
    var priority_icon_path = $('#priority_icon_path').val();

    $('#search_bar_open').on('click', function() {
        if (($('#search_bar_open').hasClass('active'))) {
            $('#search_bar_open').removeClass('active');
            $('#search_do').addClass('active');
            $('.search').addClass('active');
            $('#search_data').addClass('active');
        } else {
            $('#search_bar_open').addClass('active');
            $('#search_do').removeClass('active');
            $('.search').removeClass('active');
            $('#search_data').removeClass('active');
            $('.search_result_info').removeClass('active');
            $('.search_result').removeClass('active');
        }
    });
    $(document).on('click', '.C3_day_box', function() {
        var kpi_name = $(this).attr('data-name');
        var stageID = $(this).parent().attr('data-stageid');
        var stageName = $('.drilldown>.section-header').html();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            showcontrolls = true;
            clearHTML();
            $('#current_stage').val(stageID);
            showStageData(stageID, stageName);
        } else {
            $('.C3_day_box').removeClass('active');
            $(this).addClass('active');
            if (kpi_name) {
                $('#current_stage').val(stageID);
                $('#current_sort_field').val();
                $('#current_sort_order').val();
                if ($(this).hasClass('low')) {
                    $('#current_kpi').val('0');
                    setPandingDayslimit(kpi_name, stageID);
                } else {
                    $('#current_kpi').val(kpi_name);
                }
                showcontrolls = false;
                getPatientData();
            }
        }
        setSidebarButtonActive();
    });
    $(document).on('click', '.console_buckets', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.c3_overview_link').removeClass('active');
            $('.control_section').removeClass('active');
            $('ul.c3_sidebar_list').removeClass('active');
            $('.before_drilldown').show();
            $('.drilldown').removeClass('active');
            $('.drilldown>.section-header').html('');
            $('.drilldown>.subsection-header>p').html('');
            $('.circle drilldown_kpi_indicator').css('background-color', 'transparent');
        } else {
            $('.console_buckets').removeClass('active');
            $(this).addClass('active');
            $('.c3_overview_link').addClass('active');
            $('.control_section').addClass('active');
            $('ul.c3_sidebar_list').addClass('active');
            $('.before_drilldown').hide();
            $('.drilldown').addClass('active');
            $('.drilldown>.subsection-header>p').html('');
            $('.circle drilldown_kpi_indicator').css('background-color', 'transparent');
            bucketName = $(this).attr('data-name');
            var bucketTitle = $(this).find('p').html();
            if (bucketName == 'recall') {
                bucketTitle += '<img src="' + recall_icon_path + '" alt="" class="bucket_icon_style">';
            } else if (bucketName == 'archived') {
                bucketTitle += '<img src="' + archive_icon_path + '" alt="" class="bucket_icon_style">';
            } else if (bucketName == 'priority') {
                bucketTitle += '<img src="' + priority_icon_path + '" alt="" class="bucket_icon_style">';
            }
            $('.drilldown>.section-header').html(bucketTitle);
            bucketData(bucketName);
        }
    });
    $('#recall_date').datetimepicker({
        format: 'MM/DD/YYYY',
    });
    $('.day_box.active').on('click', function() {
        $(this).removeClass('active');
    });
    $('#search_do').on('click', searchc3);
    $('.c3_overview_link').on('click', function() {
        $('.console_buckets').removeClass('active');
        refreshOverview();
        $('.c3_overview_link').removeClass('active');
        $('.console_bucket_row').removeClass('hide');
        $('.control_section').removeClass('active');
        $('ul.c3_sidebar_list').removeClass('active');
        $('.before_drilldown').show();
        $('.drilldown').removeClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('#current_stage').val('-1');
        setSidebarButtonActive();
    });
    $('.info_section').on('click', function() {
        $('.c3_overview_link').addClass('active');
        $('.console_bucket_row').addClass('hide');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        var stage_id = $($(this).closest('.info_box')).attr('data-id');
        var stage_name = $($(this).closest('.info_box')).attr('data-name');
        var kpi_id = $(this).attr('id');
        var kpi_name = $(this).attr('data-name');
        var kpi_indicator = $(this).attr('data-indicator');
        clearHTML();
        $('#current_stage').val(stage_id);
        showKPIData(stage_id, kpi_id, stage_name, kpi_name, kpi_indicator);
    });
    $('.stage').on('click', function() {
        $('.c3_overview_link').addClass('active');
        $('.console_bucket_row').addClass('hide');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('.subsection-header').removeClass('active');
        $('.console_buckets').removeClass('active');
        $('.patient_records_info').removeClass('active');

        var stage_id = '';
        var stage_name = '';
        showcontrolls = true;
        stage_id = $(this).attr('data-id');
        stage_name = $(this).attr('data-name');
        clearHTML();
        $('#current_stage').val(stage_id);
        showStageData(stage_id, stage_name);
    });
    $('#drilldown_patients_listing').on('click', '.careconsole_action', function() {
        var data = [];
        data['patient_id'] = $(this).parent().attr('data-patientid');
        data['action_id'] = $(this).attr('data-id');
        data['console_id'] = $(this).parent().attr('data-consoleid');
        data['stage_id'] = $('#current_stage').val();
        data['action_header'] = $(this).attr('data-displayname');

        if ($('#current_stage').val() === '-1') {
            return;
        }
        show_patient = true;
		clearActionFields();
        showDate = false;

        switch ($(this).attr('data-name')) {
            case 'reschedule':
            case 'schedule':
                window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $(this).parent().attr('data-patientid');
                break;
            case 'recall-later':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'annual-exam':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'manually-schedule':
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
				$('#form_manual_appointment_appointment_type').show();
                showActionModel(data);
                break;
            case 'manually-reschedule':
                $('#form_manual_appointment_date').show();
				$('#form_manual_appointment_practice').show();
				$('#form_manual_appointment_provider').show();
				$('#form_manual_appointment_location').show();
				$('#form_manual_appointment_appointment_type').show();
                showActionModel(data);
                break;
            default:
                showActionModel(data);
        }
    });
    $('#records_action_dropdown').on('click', '.careconsole_action', function() {
        var data = [];
        data['patient_id'] = $(this).attr('data-patientid');
        data['action_id'] = $(this).attr('data-id');
        data['console_id'] = $(this).attr('data-consoleid');
        data['stage_id'] = $(this).attr('data-stageid');
        data['action_header'] = $(this).attr('data-displayname');

        show_patient = true;
		clearActionFields();
        showDate = false;

        switch ($(this).attr('data-name')) {
            case 'reschedule':
            case 'schedule':
                window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $(this).attr('data-patientid');
                break;
            case 'recall-later':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'annual-exam':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'manually-schedule':
                $('#form_manual_appointment_date').show();
				$('#form_manual_appointment_practice').show();
				$('#form_manual_appointment_provider').show();
				$('#form_manual_appointment_location').show();
				$('#form_manual_appointment_appointment_type').show();
                showActionModel(data);
                break;
            case 'manually-reschedule':
                $('#form_manual_appointment_date').show();
				$('#form_manual_appointment_practice').show();
				$('#form_manual_appointment_provider').show();
				$('#form_manual_appointment_location').show();
				$('#form_manual_appointment_appointment_type').show();
                showActionModel(data);
                break;
            default:
                showActionModel(data);
        }
    });
    $('#search_action_dropdown').on('click', '.careconsole_action', function() {
        var data = [];
        data['patient_id'] = $(this).attr('data-patientid');
        data['action_id'] = $(this).attr('data-id');
        data['console_id'] = $(this).attr('data-consoleid');
        data['stage_id'] = $(this).attr('data-stageid');
        data['action_header'] = $(this).attr('data-displayname');
		clearActionFields();
        showDate = false;
        show_patient = false;
        switch ($(this).attr('data-name')) {
            case 'reschedule':
            case 'schedule':
                window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $(this).attr('data-patientid');
                break;
            case 'recall-later':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'annual-exam':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'manually-schedule':
                $('#form_manual_appointment_date').show();
				$('#form_manual_appointment_practice').show();
				$('#form_manual_appointment_provider').show();
				$('#form_manual_appointment_location').show();
				$('#form_manual_appointment_appointment_type').show();
                showActionModel(data);
                break;
            case 'manually-reschedule':
				$('#form_manual_appointment_practice').show();
				$('#form_manual_appointment_provider').show();
				$('#form_manual_appointment_location').show();
                $('#form_manual_appointment_date').show();
				$('#form_manual_appointment_appointment_type').show();
                showActionModel(data);
                break;
            default:
                showActionModel(data);
        }
    });
    $('.search_result').on('click', '.search_result_row', function() {
        var index = $(this).attr('data-index');
        setSearchFields(index);
    });
    $('#back_to_search').on('click', function() {
        $('.search_result').addClass('active');
        $('.search_result_info').removeClass('active');

    });
    $('.contact_attempts').on('click', '.history_item', function() {
        $('.history_item').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        var index = $(this).attr('data-index');
        $('.patient_contact_info').find('.contact_notes').html(contact_notes[index]);
    });
    $(document).on('click', '.drilldown_header_item', function() {
        var field = $(this).find('.sort_order');
        if (field.length === 0) {
            return;
        }
        if (field.css('display') !== 'none') {
            if (field.attr('data-order') === 'SORT_DESC') {
                field.attr('data-order', 'SORT_ASC');
                field.removeClass('glyphicon-chevron-down');
                field.addClass('glyphicon-chevron-up');
            } else if (field.attr('data-order') === 'SORT_ASC') {
                field.attr('data-order', 'SORT_DESC');
                field.removeClass('glyphicon-chevron-up');
                field.addClass('glyphicon-chevron-down');
            }
        }
        $('.sort_order').css('display', 'none');
        field.css('display', 'inline-block');
        $('#current_sort_field').val(field.attr('data-name'));
        $('#current_sort_order').val(field.attr('data-order'));
        getPatientData();
    });
    $(document).on('click', '.drilldown_item>div', function() {
        if ($(this).attr('data-name') === 'actions') {
            return;
        }
        var consoleID = $(this).attr('data-consoleid');
        setPatientRecords(consoleID);
        $('.patient_records_info').addClass('active');
    });
    $(document).on('click', '.close_patient_records_info', function() {
        $('.patient_records_info').removeClass('active');
    });
    $(document).keypress(function(e) {
        if (e.which == 13 && $('#search_data').val() != '') {
            searchc3();
        }
    });

    $('#manual_appointment_practice').on('change', function() {
        getProvidersAndLocations($(this).val());

    });
});

var actionResults = {};
var patientdata = [];
var llimit = -1;
var ulimit = -1;
var show_patient = true;
var contact_notes = [];
var showDate = false;
var showcontrolls = true;
var bucketName = '';

function searchc3() {
    $('.search_result_info').removeClass('active');
    if (!($('#search_bar_open').hasClass('active'))) {
        var formData = {
            'name': $('#search_data').val()
        };
        $.ajax({
            url: '/careconsole/searchpatient',
            type: 'GET',
            data: $.param(formData),
            contentType: 'text/html',
            async: false,
            success: function success(e) {
                $('#back_to_search').addClass('active');
                patientdata = $.parseJSON(e);
                if (patientdata.length > 1) {
                    var content = '';
                    var index = 0;
                    patientdata.forEach(function(patient) {
                        content += '<div class="search_result_row row" data-index= "' + index + '"><div class="col-xs-1 search_color_col"><div class="circle" id="" style="background-color:' + patient.stage_color + '"></div></div><div class="col-xs-11 search_result_row_text"><p class="result_title arial_bold result_name">' + patient.name + '</p><p class="result_title arial_bold scheduled_name">' + patient.stage_name + '</p></div></div>';

                        index++;
                    });
                    $('.search_result').html(content);
                    $('.search_result').addClass('active');
                } else if (patientdata.length != 0) {
                    $('#back_to_search').removeClass('active');
                    setSearchFields(0);

                }
            },
            error: function error() {
                $('p.alert_message').text('Error searching');
                $('#alert').modal('show');
            },
            cache: false,
            processData: false
        });

    }
}

function showKPIData(stage_id, kpi_id, stage_name, kpi_name, kpi_indicator) {
    $('#sidebar_' + stage_id).addClass('sidebar_items_active');
    $('.subsection-header').addClass('active');
    $('.drilldown>.section-header').html(stage_name);
    $('.drilldown>.subsection-header>p').html(kpi_name);
    $('.drilldown_kpi_indicator').css('background-color', kpi_indicator);
    $('#current_stage').val(stage_id);
    $('#current_kpi').val(kpi_id);
    setSidebarButtonActive();

    getPatientData();
}

function showStageData(stage_id, stage_name) {
    $('#sidebar_' + stage_id).addClass('sidebar_items_active');
    $('.drilldown>.section-header').html(stage_name);
    $('#current_stage').val(stage_id);
    setSidebarButtonActive();
    $('#current_kpi').val('0');
    bucketName = '';
    if (stage_id < 6)
        $('.console_buckets').removeClass('active');
    getPatientData();
}

function clearHTML() {
    $('.drilldown>.section-header').html('');
    $('.drilldown>.subsection-header>p').html('');
    $('.drilldown_content').html('');
}

function getPatientData() {
    var stageID = $('#current_stage').val();
    var kpiName = ($('#current_kpi').val() === '0' ? '' : $('#current_kpi').val());
    var sortField = $('#current_sort_field').val();
    var sortOrder = $('#current_sort_order').val();
    var formData = {
        'stage': stageID,
        'kpi': kpiName,
        'sort_field': sortField,
        'sort_order': sortOrder,
        'lower_limit': llimit,
        'upper_limit': ulimit,
    };
    $('.drilldown_content').html('');
    $.ajax({
        url: '/careconsole/drilldown',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);
            llimit = -1;
            ulimit = -1;
            if (data.length === 0) {
                return;
            }
            var listing = '';
            var actionList = '';
            var actions = data.actions;
            var controls = data.controls;
            listing = data.listing;
            if (actions.length > 0) {
                actions.forEach(function(action) {
                    actionResults[action.id] = action.action_results;
                    actionList += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '"><a href="#">' + action.display_name + '</a></li>';
                });
            }
            $('#drilldown_patients_listing').html(listing);
            if (showcontrolls)
                $('.control_section').html(controls);
            //$('.dropdown-menu.action_dropdownmenu').html(actionList);
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function action() {
    if (($('#recall_date').val()) == '' && (showDate)) {
        $('p.alert_message').text('please select a date');
        $('#alert').modal('show');
        return;
    }
    if ($('#action_result_id').val() == '0') {
        $('p.alert_message').text('please select a result');
        $('#alert').modal('show');
        return;
    }
    var formData = {
        'console_id': $('#action_console_id').val(),
        'stage_id': $('#action_stage_id').val(),
        'action_id': $('#action_id').val(),
        'recall_date': $('#recall_date').val(),
        'manual_appointment_date': $('#manual_appointment_date').val(),
        'action_result_id': $('#action_result_id').val(),
        'notes': $('#action_notes').val(),
		'manual_appointment_practice': $('#manual_appointment_practice').val(),
		'manual_appointment_location': $('#manual_appointment_location').val(),
		'manual_appointment_provider': $('#manual_appointment_provider').val(),
		'manual_appointment_appointment_type': $('#manual_appointment_appointment_type').val()
    };

    $.ajax({
        url: '/careconsole/action',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        cache: false,
        async: false,
        success: function success(e) {
            var stage = $.parseJSON(e);
            $('#actionModal').modal('hide');
            if (show_patient && bucketName == '') {
                showStageData(stage.id, stage.name);
            } else if (show_patient && bucketName != '')
                bucketData(bucketName);
            show_patient = true;
            $('#action_notes').html('');
            $('#action_notes').val('');
            $('#action_result_id').val(0);
            $('#recall_date').val('');
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        processData: false
    });
}

function refreshOverview() {
    $.ajax({
        url: '/careconsole/overview',
        type: 'GET',
        contentType: 'text/html',
        cache: false,
        async: false,
        success: function success(e) {
            if (e.length === 0) {
                return;
            }
            var stages = e.stages;

            stages.forEach(function(stage) {
                var kpis = stage.kpis;
                kpis.forEach(function(kpi) {
                    $('.info_section_number.' + kpi.name).html(kpi.count);
                });
            });
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        processData: false
    });
}

function setSearchFields(index) {
    var patient = patientdata[index];
    //	$('.result_title.stage_name').html(patient.stage_name);
    //	$('#status_color').css('background-color', patient.stage_color);
    if (patient.recall_date) {
        $('.result_title.stage_name').html('Recalled');
        $('#status_color').css('background-color', patient.stage_color);
        $('.result_title.searchfield_1').text('Recall date');
        $('.result_text.searchfield_1').text(patient.recall_date);
        $('.result_title.searchfield_2').parent().parent().hide();
        if (patient.archived_date)
            $('.result_title.searchfield_2').parent().parent().show();
        $('.result_title.searchfield_2').text('Archived date');
        $('.result_text.searchfield_2').text(patient.archived_date);
        $('.result_title.searchfield_3').parent().parent().hide();

    } else if (patient.archived_date) {
        $('.result_title.stage_name').html('Archived');
        $('#status_color').css('background-color', patient.stage_color);
        $('.result_title.searchfield_1').text('Archived date');
        $('.result_text.searchfield_1').text(patient.archived_date);
        $('.result_title.searchfield_2').parent().parent().hide();
        $('.result_title.searchfield_3').parent().parent().hide();
    } else if (patient['stage_id'] == 1) {
        $('.result_title.stage_name').html(patient.stage_name);
        $('#status_color').css('background-color', patient.stage_color);
        $('.result_title.searchfield_2').parent().parent().show();
        $('.result_title.searchfield_1').text('Days Pending');
        $('.result_text.searchfield_1').text(patient.days_pending);
        $('.result_title.searchfield_2').text('Last Scheduled To');
        $('.result_text.searchfield_2').text(patient.last_scheduled_to);
        $('.result_title.searchfield_3').parent().parent().show();
        $('.result_title.searchfield_3').text('Contact Attempts');
        $('.result_text.searchfield_3').text(patient.contact_attempts);
    } else {
        $('.result_title.stage_name').html(patient.stage_name);
        $('#status_color').css('background-color', patient.stage_color);
        $('.result_title.searchfield_1').text('Scheduled To');
        $('.result_text.searchfield_1').text(patient.scheduled_to);
        $('.result_title.searchfield_2').parent().parent().show();
        $('.result_title.searchfield_2').text('Appointment Date');
        $('.result_text.searchfield_2').text(patient.appointment_date);
        $('.result_title.searchfield_3').parent().parent().hide();
    }


    $('.search_result_info').addClass('active');
    $('.search_result').removeClass('active');

    var content = '';
    patient.actions.forEach(function(action) {
        actionResults[action.id] = action.action_results;
        content += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '" data-patientid = "' + patient.id + '" data-consoleid="' + patient.console_id + '" data-stageid="' + patient.stage_id + '" ><a href="#">' + action.display_name + '</a></li>';
    });
    $('#search_action_dropdown').html(content);

}

function bucketData(bucketName) {

    var formData = {
        'bucket': bucketName
    };

    $.ajax({
        url: '/careconsole/bucketpatients',
        type: 'GET',
        contentType: 'text/html',
        data: $.param(formData),
        cache: false,
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);
            if (data.length === 0) {
                return;
            }
            var listing = '';
            var actionList = '';
            var actions = data.actions;
            listing = data.listing;
            if (actions.length > 0) {
                actions.forEach(function(action) {
                    actionResults[action.id] = action.action_results;
                    actionList += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '"><a href="#">' + action.display_name + '</a></li>';
                });
            }
            $('#drilldown_patients_listing').html(listing);
            $('.dropdown-menu.action_dropdownmenu').html(actionList);
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        processData: false
    });
}

function setPatientRecords(consoleID) {
    var formData = {
        'consoleID': consoleID
    };

    $.ajax({
        url: '/careconsole/patient/records',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);
            if (data.length === 0) {
                return;
            }
            $('.patient_records_info').find('.patient_name').text(data.name);
            $('.patient_records_info').find('.patient_phone').text(data.phone);
            $('.patient_records_info').find('.scheduled_to').text(data.scheduled_to);
            $('.patient_records_info').find('.appointment_date').text(data.appointment_date);
            var content = '';
            contact_notes = [];
            var i = 0;
            var show_active = 'active';
            $('.patient_contact_info').find('.contact_notes').html('');
            if (data.contacts_attempt.length > 0) {
                data.contacts_attempt.forEach(function(contact) {
                    content += '<p class="history_item ' + show_active + '" data-index = "' + i + '"><span class="history_item_name ">' + contact.name + '</span> <span class="history_item_date attempt_phone">' + contact.date + '</span></p>';
                    contact_notes[i] = '-';
                    if (contact.notes)
                        contact_notes[i] = contact.notes;
                    show_active = '';
                    i++;
                });
                $('.contact_attempts').html(content);
            } else {
                $('.contact_attempts').text('-');
            }
            content = '';
            data.actions.forEach(function(action) {
                actionResults[action.id] = action.action_results;
                if (data.priority == 1 && action.id == 30) {

                } else if (!data.priority && action.id == 31) {

                } else {
                    content += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '" data-patientid= "' + data.patient_id + '" data-consoleid="' + consoleID + '" data-stageid = "' + data.stageid + '"><a href="#">' + action.display_name + '</a></li>';
                }
            });
            $('#records_action_dropdown').html(content);

        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false

    });
}

function setPandingDayslimit(kpi_name, stageID) {

    switch (kpi_name) {
        case 'Low':
            if (stageID == '1') {
                llimit = 0;
                ulimit = 4;
            } else {
                llimit = 0;
                ulimit = 3;
            }

            break;
        case 'Normal':
            if (stageID == '1') {
                llimit = 4;
                ulimit = 8;
            } else {
                llimit = 3;
                ulimit = 5;
            }
            break;
        case 'Urgent':
            if (stageID == '1') {
                llimit = 8;
                ulimit = 10000;
            } else {
                llimit = 0;
                ulimit = 10000;
            }
            break;
    }
}

function showActionModel(data) {
    $('#action_patient_id').val(data['patient_id']);
    $('#action_id').val(data['action_id']);
    $('#action_console_id').val(data['console_id']);
    $('#action_stage_id').val(data['stage_id']);
    $('#action_header').html(data['action_header']);
    var results = actionResults[data['action_id']];
    if (results.length > 0) {
        var content = '<option value="0">Select Action Result</option>';
        results.forEach(function(result) {
            content += '<option value="' + result.action_result_id + '">' + result.display_name + '</option>';
        });
        $('#action_result_id').html(content);
        $('#action_results').show();
    } else if (results.length === 0) {
        $('#action_result_id').html('<option value="-1">No Action Results</option>');
        $('#action_results').hide();
    }
    $('#action_notes').val('');
    $('#actionModal').modal('show');
}

function setSidebarButtonActive() {
    var stageID = $('#current_stage').val();
    $(".stage.box").parent('.sidebar_menu_item').removeClass('active');
    $(".stage.box[data-id=" + stageID + "]").parent('.sidebar_menu_item').addClass('active');
}

function getProvidersAndLocations(practiceID) {
    var formData = {
        'practiceID': practiceID
    };
    $.ajax({
        url: '/careconsole/action/practiceproviders',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);

            var locations = data['locations'];
            var content = '<option value="0">Select Location</option>';
            $.each(locations, function(index, val) {
                content += '<option value="' + val.id + '">' + val.locationname + '</option>';
            });
            $('#manual_appointment_location').html(content);

            var providers = data['provider'];
            if (!providers) {
                var content = '<option value="0">Select Provider</option>';
                $('#manual_appointment_provider').html(content);
                return;
            }
            var content = '<option value="0">Select User</option>';

            $.each(providers, function(index, val) {
                content += '<option value="' + val.id + '">' + val.name + '</option>';
            });
            $('#manual_appointment_provider').html(content);
        }
    });
}

function clearActionFields(){
	$('#form_recall_date').hide();
	$('#form_manual_appointment_date').hide();
	$('#form_manual_appointment_practice').hide();
	$('#form_manual_appointment_provider').hide();
	$('#form_manual_appointment_location').hide();
	$('#form_manual_appointment_appointment_type').hide();
	$('#form_recall_date').val('');
	$('#manual_appointment_date').val('');
	$('#form_manual_appointment_practice').val('');
	$('#form_manual_appointment_provider').val('');
	$('#form_manual_appointment_location').val('');
	$('#manual_appointment_appointment_type').val('');

}
