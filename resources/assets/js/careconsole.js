$(document).ready(function() {
    loadImportForm();
    $('#datetimepicker_action_date').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $('#manual_appointment_date').datetimepicker();

    var recall_icon_path = $('#recall_icon_path').val();
    var archive_icon_path = $('#archive_icon_path').val();
    var priority_icon_path = $('#priority_icon_path').val();

    $('.open_patient_detail_modal').on('click', function() {
        if ($('.patient_contact_request_info').hasClass('hide')) {
            $('.patient_contact_request_info').removeClass('hide');
            $('.contact_request_section').removeClass('col-md-12');
            $('.contact_request_section').addClass('col-md-8');
            $('.modal-dialog').addClass('wide-modal');
            $('.open_patient_detail_modal').removeClass('glyphicon-circle-arrow-left');
            $('.open_patient_detail_modal').addClass('glyphicon-circle-arrow-right');

        } else {
            $('.patient_contact_request_info').addClass('hide');
            $('.contact_request_section').removeClass('col-md-8');
            $('.contact_request_section').addClass('col-md-12');
            $('.modal-dialog').removeClass('wide-modal');
            $('.open_patient_detail_modal').removeClass('glyphicon-circle-arrow-right');
            $('.open_patient_detail_modal').addClass('glyphicon-circle-arrow-left');
        }
    });

    $('#search_bar_open').on('click', function() {
        if ($('#search_bar_open').hasClass('active')) {
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

    $(document).on('click', '#apply_filter', function() {
        currentPage = 1;
        if (toCall == 2) {
            bucketData(bucketName);
        } else {
            getPatientData();
        }
    });
    
    $(document).on('click', '#remove_filter', function() {
        $(this).attr('disabled', true);
        currentPage = 1;
        clearStageFilter();
        if (toCall == 2) {
            bucketData(bucketName);
        } else {
            getPatientData();
        }
    });

    $(document).on('click', '.C3_day_box', function() {
        var kpi_id = $(this).attr('data-id');
        var kpi_name = $(this).attr('data-name');
        var stageID = $(this).parent().attr('data-stageid');
        var stage_system_name = $(this).parent().attr('data-stage-system-name');
        var stageName = $(this).parent().attr('data-stage-display-name');
        var kpi_indicator = $(this).attr('data-indicator');

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            showcontrols = true;
            clearHTML();
            $('#current_stage').val(stageID);
            showStageData(stageID, stageName, stage_system_name);
        } else {
            $('.C3_day_box').removeClass('active');
            $(this).addClass('active');
            showcontrols = false;
            currentPage = 1;
            if (kpi_name) {
                $('#current_stage').val(stageID);
                $('#current_sort_field').val();
                $('#current_sort_order').val();
                if ($(this).hasClass('low')) {
                    $('#current_kpi').val(0);
                    $('.subsection-header').removeClass('active');
                    setPendingDayslimit(kpi_name, stageID);
                    getPatientData();
                } else {
                    showKPIData(stageID, kpi_id, stageName, kpi_name, kpi_indicator, stage_system_name);
                }
            }
        }
        setSidebarButtonActive();
    });
    $(document).on('click', '.console_buckets', function() {
        $('.control_section').html('');
        clearStageFilter();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.c3_overview_link').removeClass('active');
            $('.control_section').removeClass('active');
            $('ul.c3_sidebar_list').removeClass('active');
            $('.before_drilldown').show();
            $('.drilldown').removeClass('active');
            $('.drilldown>.section-header').html('');
            $('.drilldown>.subsection-header>p').html('');
            $('.circle.drilldown_kpi_indicator').css('background-color', 'transparent');
            showcontrols = false;
        } else {
            $('.console_buckets').removeClass('active');
            $(this).addClass('active');
            $('.c3_overview_link').addClass('active');
            $('.control_section').addClass('active');
            $('ul.c3_sidebar_list').addClass('active');
            $('.before_drilldown').hide();
            $('.drilldown').addClass('active');
            $('.drilldown>.subsection-header>p').html('');
            $('.circle.drilldown_kpi_indicator').css('background-color', 'transparent');
            showcontrols = true;
            bucketName = $(this).attr('data-name');
            var bucketTitle = $(this).find('p').html();
            if (bucketName == 'recall') {
                bucketTitle += '<img src="' + recall_icon_path + '" alt="" class="bucket_icon_style"><button type="button" class="btn export_btn" id="export_bucket" data_bucket_name="recall">Export</button>';
            } else if (bucketName == 'archived') {
                bucketTitle += '<img src="' + archive_icon_path + '" alt="" class="bucket_icon_style"><button type="button" class="btn export_btn" id="export_bucket" data_bucket_name="archived">Export</button>';
            } else if (bucketName == 'priority') {
                bucketTitle += '<img src="' + priority_icon_path + '" alt="" class="bucket_icon_style"><button type="button" class="btn export_btn" id="export_bucket" data_bucket_name="priority">Export</button>';
            }
            $('.drilldown>.section-header').html(bucketTitle);
            currentPage = 1;
            bucketData(bucketName);
        }
    });
    $('#recall_date').datetimepicker({
        format: 'MM/DD/YYYY'
    });
    $('.day_box.active').on('click', function() {
        $(this).removeClass('active');
    });
    $('#search_do').on('click', searchc3);
    $('.c3_overview_link').on('click', function() {
        $('.console_buckets').removeClass('active');
        refreshOverview();
        clearStageFilter();
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
        clearStageFilter();
        $('.c3_overview_link').addClass('active');
        $('.console_bucket_row').addClass('hide');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        var stage_id = $($(this).closest('.info_box')).attr('data-id');
        var stage_name = $($(this).closest('.info_box')).attr('data-name');
        var stage_system_name = $($(this).closest('.info_box')).attr('data-system-name');
        var kpi_id = $(this).attr('id');
        var kpi_name = $(this).attr('data-name');
        var kpi_indicator = $(this).attr('data-indicator');
        clearHTML();
        $('#current_stage').val(stage_id);
        showKPIData(stage_id, kpi_id, stage_name, kpi_name, kpi_indicator, stage_system_name);
    });
    $('.stage').on('click', function() {
        clearStageFilter();
        $('.c3_overview_link').addClass('active');
        $('.console_bucket_row').addClass('hide');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('.console_buckets').removeClass('active');
        $('.patient_records_info').removeClass('active');

        var stage_id = '';
        var stage_name = '';
        var stage_system_name = '';
        showcontrols = true;
        stage_id = $(this).attr('data-id');
        stage_name = $(this).attr('data-name');
        stage_system_name = $(this).attr('data-system-name');
        clearHTML();
        $('#current_stage').val(stage_id);
        showStageData(stage_id, stage_name, stage_system_name);
    });
    $('#drilldown_patients_listing').on('click', '.careconsole_action', function() {
        var data = [];
        data['patient_id'] = $(this).parent().attr('data-patientid');
        data['action_id'] = $(this).attr('data-id');
        data['console_id'] = $(this).parent().attr('data-consoleid');
        data['stage_id'] = $('#current_stage').val();
        data['action_header'] = $(this).attr('data-displayname');

        var patientName = $(this).parent().attr('data-patient-name');
        var patientEmail = ($(this).parent().attr('data-patient-email') == '') ? '-' : $(this).parent().attr('data-patient-email');
        var patientPhone = ($(this).parent().attr('data-patient-phone') == '') ? '-' : $(this).parent().attr('data-patient-phone');

        show_patient = true;
        clearActionFields();
        showDate = false;

        $('.patient_contact_request_info').html('');
        updatePatientDemographic = false;

        $('.open_patient_detail_modal').removeClass('show');
        $('#action_name').val($(this).attr('data-name'));
        $('.modal-dialog').removeClass('wide-modal');

        switch ($(this).attr('data-name')) {
            case 'request-patient-phone':
                $('#form_action_request_phone').show();
                $('.form_action_patient_phone').html(patientPhone);
                $('.form_action_patient_name').html(patientName);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'request-patient-email':
                $('#form_action_request_email').show();
                $('.form_action_patient_email_id').html(patientEmail);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'request-patient-sms':
                $('#form_action_request_sms').show();
                $('.form_action_patient_phone').html(patientPhone);
                $('.form_action_patient_name').html(patientName);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'recall-later':
                $('#form_recall_date').show();
                showDate = true;
                $('#form_action_notes').hide();
                showActionModel(data);
                break;
            case 'annual-exam':
                $('#form_recall_date').show();
                showDate = true;
                showActionModel(data);
                break;
            case 'manually-schedule':
            case 'previously-scheduled':
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
                $('#form_manual_appointment_appointment_type').show();
                $('#form_manual_referredby_details').show();
                $('#form_manual_appointment_existing_relationship').show();
                updateManualScheduleData(data['console_id']);
                showActionModel(data);
                break;
            case 'manually-reschedule':
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
                $('#form_manual_appointment_appointment_type').show();
                $('#form_manual_referredby_details').show();
                $('#form_manual_appointment_existing_relationship').show();
                updateManualScheduleData(data['console_id']);
                showActionModel(data);
                break;
            case 'contact-attempted-by-email':
            case 'contact-attempted-by-mail':
            case 'contact-attempted-by-other':
            case 'contact-attempted-by-phone':
                getPatientContactData(data['patient_id']);
                $('.open_patient_detail_modal').addClass('show');
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

        $('.patient_contact_request_info').html('');
        updatePatientDemographic = false;

        var patientName = $(".action_dropdownmenu[data-patientid=" + data['patient_id'] + "]").attr('data-patient-name');
        var patientEmail = ($(".action_dropdownmenu[data-patientid=" + data['patient_id'] + "]").attr('data-patient-email') == '') ? '-' : $(".action_dropdownmenu[data-patientid=" + data['patient_id'] + "]").attr('data-patient-email');
        var patientPhone = ($(".action_dropdownmenu[data-patientid=" + data['patient_id'] + "]").attr('data-patient-phone') == '') ? '-' : $(".action_dropdownmenu[data-patientid=" + data['patient_id'] + "]").attr('data-patient-phone');

        $('.open_patient_detail_modal').removeClass('show');
        $('#action_name').val($(this).attr('data-name'));
        $('.modal-dialog').removeClass('wide-modal');

        switch ($(this).attr('data-name')) {
            case 'request-patient-phone':
                $('#form_action_request_phone').show();
                $('.form_action_patient_phone').html(patientPhone);
                $('.form_action_patient_name').html(patientName);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'request-patient-email':
                $('#form_action_request_email').show();
                $('.form_action_patient_email_id').html(patientEmail);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'request-patient-sms':
                $('#form_action_request_sms').show();
                $('.form_action_patient_phone').html(patientPhone);
                $('.form_action_patient_name').html(patientName);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
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
            case 'previously-scheduled':
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
                $('#form_manual_appointment_appointment_type').show();
                $('#form_manual_referredby_details').show();
                $('#form_manual_appointment_existing_relationship').show();
                updateManualScheduleData(data['console_id']);
                showActionModel(data);
                break;
            case 'manually-reschedule':
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
                $('#form_manual_appointment_appointment_type').show();
                $('#form_manual_referredby_details').show();
                $('#form_manual_appointment_existing_relationship').show();
                updateManualScheduleData(data['console_id']);
                showActionModel(data);
                break;
            case 'contact-attempted-by-email':
            case 'contact-attempted-by-mail':
            case 'contact-attempted-by-other':
            case 'contact-attempted-by-phone':
                getPatientContactData(data['patient_id']);
                $('.open_patient_detail_modal').addClass('show');
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

        var patientName = '';
        var patientEmail = '';
        var patientPhone = '';

        var formData = {
            'patientID': data['patient_id']
        };

        $.ajax({
            url: '/careconsole/patient_info',
            type: 'GET',
            data: $.param(formData),
            contentType: 'text/html',
            async: false,
            success: function success(e) {
                var patientdata = $.parseJSON(e);
                patientName = patientdata.name;
                patientEmail = (patientdata.email == '') ? '-' : patientdata.email;
                patientPhone = (patientdata.phone == '') ? '-' : patientdata.phone;
            },
            error: function error() {
                $('p.alert_message').text('Error:');
                $('#alert').modal('show');
            },
            cache: false,
            processData: false

        });

        $('.open_patient_detail_modal').removeClass('show');
        $('#action_name').val($(this).attr('data-name'));
        $('.modal-dialog').removeClass('wide-modal');

        $('.patient_contact_request_info').html('');
        updatePatientDemographic = false;

        switch ($(this).attr('data-name')) {
            case 'request-patient-phone':
                $('#form_action_request_phone').show();
                $('.form_action_patient_phone').html(patientPhone);
                $('.form_action_patient_name').html(patientName);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'request-patient-email':
                $('#form_action_request_email').show();
                $('.form_action_patient_email_id').html(patientEmail);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
                break;
            case 'request-patient-sms':
                $('#form_action_request_sms').show();
                $('.form_action_patient_phone').html(patientPhone);
                $('.form_action_patient_name').html(patientName);
                getPatientContactData(data['patient_id']);
                $('#form_action_notes').hide();
                $('.open_patient_detail_modal').addClass('show');
                showActionModel(data);
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
            case 'previously-scheduled':
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
                $('#form_manual_appointment_appointment_type').show();
                $('#form_manual_referredby_details').show();
                $('#form_manual_appointment_existing_relationship').show();
                updateManualScheduleData(data['console_id']);
                showActionModel(data);
                break;
            case 'manually-reschedule':
                $('#form_manual_appointment_practice').show();
                $('#form_manual_appointment_provider').show();
                $('#form_manual_appointment_location').show();
                $('#form_manual_appointment_date').show();
                $('#form_manual_appointment_appointment_type').show();
                $('#form_manual_referredby_details').show();
                $('#form_manual_appointment_existing_relationship').show();
                updateManualScheduleData(data['console_id']);
                showActionModel(data);
                break;
            case 'contact-attempted-by-email':
            case 'contact-attempted-by-mail':
            case 'contact-attempted-by-other':
            case 'contact-attempted-by-phone':
                getPatientContactData(data['patient_id']);
                $('.open_patient_detail_modal').addClass('show');
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
        $('.patient_contact_info').find('.action_note').html(contact_notes[index]);
        if (!contact_results[index]) {
            $('#action_result_section').hide();
        } else {
            $('#action_result_section').show();
            $('.patient_contact_info').find('.action_result').html(contact_results[index]);
        }
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
        currentPage = 1;
        if (toCall == 2) {
            bucketData(bucketName);
        } else {
            getPatientData();
        }
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
        if ($(this).val() != '-1') {
            $('#form_manual_appointment_location').show();
            $('#form_manual_custom_appointment_practice').hide();
            $('#form_manual_custom_appointment_location').hide();
            var manually_created = $('#manual_appointment_practice').find(':selected').data('manually_created');
            if (manually_created == 1) {
                $('#form_manual_appointment_provider').hide();
            } else {
                $('#form_manual_appointment_provider').show();
            }
            getProvidersAndLocations($(this).val(), $(this).find(':selected').data('manually_created'));
        } else {
            $('#form_manual_custom_appointment_practice').show();
            $('#form_manual_custom_appointment_location').show();
            $('#form_manual_appointment_provider').hide();
            $('#form_manual_appointment_location').hide();
            $('#manual_custom_appointment_practice').val('');
            $('#manual_custom_appointment_location').val('');
        }
    });

    $('#manual_appointment_location').on('change', function() {
        if ($(this).val() == '-1') {
            $('#form_manual_custom_appointment_location').show();
            $('#manual_custom_appointment_location').val('');
        } else {
            $('#form_manual_custom_appointment_location').hide();
        }
    });

    $('#listing_content').on('scroll', function() {
        if ($(this).scrollTop() + $(this).innerHeight() + 10 >= $(this)[0].scrollHeight) {
            currentPage++;

            if (toCall == 1 && currentPage <= lastPage) getPatientData();
            if (toCall == 2 && currentPage <= lastPage) bucketData(bucketName);
        }
    });

    $('.suggestion_list').on('click', '.practice_suggestion_item', function() {
        var selectedValue = $(this).text();
        $('#manual_referredby_practice').val(selectedValue);
        $(this).closest('.suggestion_list').removeClass('active');
    });

    $('.suggestion_list').on('click', '.provider_suggestion_item', function() {
        var selectedValue = $(this).text();
        $('#manual_referredby_provider').val(selectedValue);
        $(this).closest('.suggestion_list').removeClass('active');
    });

    $(document).on('click', function() {
        $('.suggestion_list').removeClass('active');
    });
    $('#manual_appointment_appointment_type').on('change', function() {
        var val = $(this).find(":selected").val();
        $('#form_manual_custom_appointment_appointment_type').hide();
        if (val === '-1') {
            $('#form_manual_custom_appointment_appointment_type').show();
        }
    });

    $(document).on('click', '.preferred_phone_checkbox', function() {
        $('.preferred_phone_checkbox').tooltip('hide');
        var checked = $(this).is(':checked');
        $('.preferred_phone_checkbox').attr('checked', false);
        if (checked) {
            $(this).prop('checked', true);
        }
    });

});

var actionResults = {};
var patientdata = [];
var llimit = -1;
var ulimit = -1;
var show_patient = true;
var contact_notes = [];
var showDate = false;
var showcontrols = true;
var bucketName = '';
var currentPage = 1;
var toCall = 0;
var lastPage = 1;
var contact_results = [];
var updatePatientDemographic = false;

function searchc3() {
    $('.search_result_info').removeClass('active');
    if (!$('#search_bar_open').hasClass('active')) {
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

function showKPIData(stage_id, kpi_id, stage_name, kpi_name, kpi_indicator, stage_system_name) {
    $('#sidebar_' + stage_id).addClass('sidebar_items_active');
    $('.subsection-header').addClass('active');
    $('.drilldown>.section-header').html('');
    $('.drilldown>.section-header').html(stage_name + '<button type="button" class="btn export_btn" id="export_bucket" data_bucket_name="' + stage_system_name + '" data_kpi_name="' + kpi_id + '">Export</button');
    $('.drilldown>.subsection-header>p').html(kpi_name);
    $('.drilldown_kpi_indicator').css('background-color', kpi_indicator);
    $('#current_stage').val(stage_id);
    $('#current_kpi').val(kpi_id);
    setSidebarButtonActive();
    currentPage = 1;
    $('#drilldown_patients_listing').html();
    getPatientData();
}

function showStageData(stage_id, stage_name, stage_system_name) {
    $('#sidebar_' + stage_id).addClass('sidebar_items_active');
    $('.drilldown>.section-header').html('');
    $('.drilldown>.section-header').html(stage_name + '<button type="button" class="btn export_btn" id="export_bucket" data_bucket_name="' + stage_system_name + '" data_kpi_name="">Export</button');
    $('.subsection-header').removeClass('active');
    $('#current_stage').val(stage_id);
    setSidebarButtonActive();
    $('#current_kpi').val('0');
    bucketName = '';
    if (stage_id < 6) $('.console_buckets').removeClass('active');
    currentPage = 1;
    getPatientData();
}

function clearHTML() {
    $('.drilldown>.section-header').html('');
    $('.drilldown>.subsection-header>p').html('');
    $('.drilldown_content').html('');
}

function getPatientData() {
    toCall = 1;
    var stageID = $('#current_stage').val();
    var kpiName = $('#current_kpi').val() === '0' ? '' : $('#current_kpi').val();
    var sortField = $('#current_sort_field').val();
    var sortOrder = $('#current_sort_order').val();
    var filterType = $('#filter_type').val();
    var filterValue = $('#filter_value').val();

    var formData = {
        'stage': stageID,
        'kpi': kpiName,
        'sort_field': sortField,
        'sort_order': sortOrder,
        'lower_limit': llimit,
        'upper_limit': ulimit,
        'filter_type': filterType,
        'filter_value': filterValue
    };
    $.ajax({
        url: '/careconsole/drilldown?page=' + currentPage,
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
            lastPage = data.lastpage;
            if (actions.length > 0) {
                actions.forEach(function(action) {
                    actionResults[action.id] = action.action_results;
                    actionList += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '"><a href="#">' + action.display_name + '</a></li>';
                });
            }

            if (currentPage > 1) {
                $('#listing_content').append(data.listing_content)
            } else {
                $('#listing_header').html(data.listing_header);
                $('#listing_content').html(data.listing_content);
            }

            if (showcontrols) {
                $('.control_section').html(controls);
            }

            $('#export_bucket').on('click', function() {
                var formData = {
                    bucket: $(this).attr('data_bucket_name'),
                    kpi: $(this).attr('data_kpi_name'),
                };
                var query = $.param(formData);
                window.location = '/careconsole/bucket-patients-excel?' + query;
            });

        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function getRequestMessage(action_name) {

    switch (action_name) {
        case 'request-patient-email':
            return $('#request_email').val();
        case 'request-patient-phone':
            return $('#request_phone').val();
        case 'request-patient-sms':
            return $('#request_sms').val();
        default:
            return '';
    }

    return '';
}

function action() {

    if ($('#recall_date').val() == '' && showDate) {
        $('p.alert_message').text('please select a date');
        $('#alert').modal('show');
        return;
    }
    if ($('#action_result_id').val() == '0') {
        $('p.alert_message').text('please select a result');
        $('#alert').modal('show');
        return;
    }

    var actionName = $('#action_name').val();

    switch (actionName) {
        case 'reschedule':
        case 'schedule':
            window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $('#action_patient_id').val() + "&action_result_id=" + $('#action_result_id').val();
            break;
        case 'request-patient-phone':
            performAction();
            var data = [];
            data['patient_id'] = $('#action_patient_id').val();
            data['action_id'] = 1;
            data['console_id'] = $('#action_console_id').val();
            data['stage_id'] = $('#action_stage_id').val();
            data['action_header'] = 'Contact attempted by phone';

            show_patient = true;
            clearActionFields();
            showDate = false;

            $('#action_name').val('contact-attempted-by-phone');

            showActionModel(data);

            break;
        case 'contact-attempted-by-phone':
        case 'contact-attempted-by-email':
        case 'contact-attempted-by-mail':
        case 'contact-attempted-by-other':
            var selected = $('#action_result_id').find('option:selected');
            performAction();
            if (selected.data('name') == 'recall-later') {
                var data = [];
                data['patient_id'] = $('#action_patient_id').val();
                data['action_id'] = 33;
                data['console_id'] = $('#action_console_id').val();
                data['stage_id'] = $('#action_stage_id').val();
                data['action_header'] = 'Recall Later';

                show_patient = true;
                clearActionFields();
                showDate = true;

                $('#action_name').val('recall-later');
                $('#form_recall_date').show();
                $('#form_action_notes').hide();
                showActionModel(data);
            }
            break;
        case 'manually-schedule':
        case 'previously-scheduled':
            var manual_appointment_date = $('#manual_appointment_date').val();
            var manual_appointment_practice = $('#manual_appointment_practice').val();
            var manual_appointment_location = $('#manual_appointment_location').val();
            var manual_appointment_provider = $('#manual_appointment_provider').val();
            var manual_appointment_appointment_type = $('#manual_appointment_appointment_type').val();
            var custom_appointment_type = $('#manual_custom_appointment_appointment_type').val();
            var manually_created = $('#manual_appointment_practice').find(':selected').data('manually_created');
            if (manual_appointment_date == '' || manual_appointment_practice == '0' || ((manual_appointment_appointment_type == '' || manual_appointment_appointment_type == '-1') && custom_appointment_type == '') || (manual_appointment_practice != '-1' && manually_created != '1' && (manual_appointment_location == '' || manual_appointment_provider == '' || manual_appointment_location == '0' || manual_appointment_provider == '0'))) {
                $('p.alert_message').text('Please enter all the fields to schedule appointment');
                $('#alert').modal('show');
                return;
            }
            performAction();
            break;
        default:
            performAction();
            break;
    }
}

function performAction() {

    var request_message = getRequestMessage($('#action_name').val());
    var patientData = [];
    if (updatePatientDemographic) {
        patientData = {
            'email': $('.update_demographic#email').val(),
            'cellphone': $('.update_demographic#cellphone').val(),
            'homephone': $('.update_demographic#homephone').val(),
            'workphone': $('.update_demographic#workphone').val(),
            'dob': $('.update_demographic#dob').val(),
            'address_line_1': $('.update_demographic#address_line_1').val(),
            'address_line_2': $('.update_demographic#address_line_2').val(),
            'referred_by_provider': $('.update_demographic#referred_by_provider').val(),
            'referred_by_practice': $('.update_demographic#referred_by_practice').val(),
            'pcp': $('.update_demographic#pcp').val(),
            'special_request': $('.update_demographic#special_request').val(),
            'insurance_carrier': $('.update_demographic#insurance_carrier').val(),
            'subscriber_name': $('.update_demographic#subscriber_name').val(),
            'subscriber_birthdate': $('.update_demographic#subscriber_birthdate').val(),
            'subscriber_id': $('.update_demographic#subscriber_id').val(),
            'relation_to_patient': $('.update_demographic#relation_to_patient').val(),
            'group_number': $('.update_demographic#group_number').val(),
            'contact_phone_preference': $('.preferred_phone_checkbox:checked').val() ? $('.preferred_phone_checkbox:checked').val() : '',
        };
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
        'manual_appointment_appointment_type': $('#manual_appointment_appointment_type').val(),
        'existing_relationship': ($('#existing_relationship_checkbox').prop('checked') == true) ? 1 : 0,
        'manual_referredby_practice': $('#manual_referredby_practice').val(),
        'manual_referredby_provider': $('#manual_referredby_provider').val(),
        'custom_manual_appointment_practice': $('#manual_custom_appointment_practice').val(),
        'custom_manual_appointment_location': $('#manual_custom_appointment_location').val(),
        'custom_appointment_type': $('#manual_custom_appointment_appointment_type').val(),
        'request_message': request_message,
        'update_demographics': updatePatientDemographic,
        'patient_data': patientData,
    };

    $.ajax({
        url: '/careconsole/action',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        cache: false,
        async: false,
        success: function success(e) {
            switch ($('#action_name').val()) {
                case 'request-patient-phone':
                    break;
                case 'contact-attempted-by-phone':
                case 'contact-attempted-by-email':
                case 'contact-attempted-by-mail':
                case 'contact-attempted-by-other':
                    var selected = $('#action_result_id').find('option:selected');
                    if (selected.data('name') == 'recall-later') {
                        break;
                    }
                default:
                    var stage = $.parseJSON(e);
                    $('#actionModal').modal('hide');
                    if (show_patient && bucketName == '') {
                        currentPage = 1;
                        showcontrols = true;
                        getPatientData();
                    } else if (show_patient && bucketName != '') {
                        showcontrols = true;
                        currentPage = 1;
                        bucketData(bucketName);
                    }
                    show_patient = true;
                    $('#action_notes').html('');
                    $('#action_notes').val('');
                    $('#action_result_id').val(0);
                    $('#recall_date').val('');
            }
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        processData: false
    });
}

function refreshOverview() {
    toCall = 0;
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
                    $('.info_section_number.' + kpi.name).html(kpi.abbreviated_count);
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
    if (patient.recall_date) {
        $('.result_title.stage_name').html('Recalled');
        $('#status_color').css('background-color', patient.stage_color);
        $('.result_title.searchfield_1').text('Recall date');
        $('.result_text.searchfield_1').text(patient.recall_date);
        $('.result_title.searchfield_2').parent().parent().hide();
        if (patient.archived_date) $('.result_title.searchfield_2').parent().parent().show();
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
    toCall = 2;
    var sortField = $('#current_sort_field').val();
    var sortOrder = $('#current_sort_order').val();
    var filterType = $('#filter_type').val();
    var filterValue = $('#filter_value').val();
    var formData = {
        'bucket': bucketName,
        'sort_field': sortField,
        'sort_order': sortOrder,
        'filter_type': filterType,
        'filter_value': filterValue
    };

    $.ajax({
        url: '/careconsole/bucketpatients?page=' + currentPage,
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
            lastPage = data.lastpage;
            if (actions.length > 0) {
                actions.forEach(function(action) {
                    actionResults[action.id] = action.action_results;
                    actionList += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '"><a href="#">' + action.display_name + '</a></li>';
                });
            }
            if (currentPage > 1) {
                $('#listing_content').append(data.listing_content);
            } else {
                $('#listing_header').html(data.listing_header);

                $('#listing_content').html(data.listing_content);
            }

            if (showcontrols) {
                $('.control_section').html(data.controls);
            }

            $('.dropdown-menu.action_dropdownmenu').html(actionList);
            $('#export_bucket').on('click', function() {
                var formData = {
                    bucket: $(this).attr('data_bucket_name'),
                };
                var query = $.param(formData);
                window.location = '/careconsole/bucket-patients-excel?' + query;
            });
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
            $('.patient_records_info').find('.patient_timezone').text(data.timezone);
            $('.patient_records_info').find('.special_request').text(data.special_request);
            $('.patient_records_info').find('.pcp').text(data.pcp);
            $('.patient_records_info').find('.scheduled_to').text(data.scheduled_to);
            $('.patient_records_info').find('.appointment_date').text(data.appointment_date);
            var content = '';
            contact_notes = [];
            contact_results = [];
            var i = 0;
            var show_active = 'active';
            $('.patient_contact_info').find('.action_note').html('');
            $('.patient_contact_info').find('.action_result').html('');
            if (data.contacts_attempt.length > 0) {
                data.contacts_attempt.forEach(function(contact) {
                    content += '<p class="history_item ' + show_active + '" data-index = "' + i + '"><span class="history_item_name ">' + contact.name + '</span> <span class="history_item_date attempt_phone">' + contact.date + '</span></p>';
                    contact_notes[i] = '-';
                    if (contact.notes) {
                        contact_notes[i] = contact.notes;
                        contact_results[i] = contact.result;
                    }
                    show_active = '';
                    i++;
                });
                $('.contact_attempts').html(content);

                $('.patient_contact_info').find('.action_note').html(contact_notes[0]);
                if (!contact_results[0]) {
                    $('#action_result_section').hide();
                } else {
                    $('#action_result_section').show();
                    $('.patient_contact_info').find('.action_result').html(contact_results[0]);
                }
            } else {
                $('.contact_attempts').text('-');
            }
            content = '';
            data.actions.forEach(function(action) {
                actionResults[action.id] = action.action_results;
                if (data.priority == 1 && action.id == 30) {} else if (!data.priority && action.id == 31) {} else {
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

function setPendingDayslimit(kpi_name, stageID) {

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

    if (updatePatientDemographic != true) {
        $('.patient_contact_request_info').addClass('hide');
        $('.contact_request_section').removeClass('col-md-8');
        $('.contact_request_section').addClass('col-md-12');
        $('.modal-dialog').removeClass('wide-modal');
        $('.open_patient_detail_modal').removeClass('glyphicon-circle-arrow-right');
        $('.open_patient_detail_modal').addClass('glyphicon-circle-arrow-left');
    } else {
        $('.patient_contact_request_info').removeClass('hide');
        $('.contact_request_section').addClass('col-md-8');
        $('.contact_request_section').removeClass('col-md-12');
        $('.modal-dialog').addClass('wide-modal');
        $('.open_patient_detail_modal').addClass('glyphicon-circle-arrow-right');
        $('.open_patient_detail_modal').removeClass('glyphicon-circle-arrow-left');
    }

    var results = actionResults[data['action_id']];
    if (results.length > 0) {
        var content = '<option value="0">Select Action Result</option>';
        results.forEach(function(result) {
            if (result.name == 'outgoing-call') {
                content += '<option value="' + result.action_result_id + '" data-name="' + result.name + '" selected>' + result.display_name + '</option>';
            } else {
                content += '<option value="' + result.action_result_id + '" data-name="' + result.name + '">' + result.display_name + '</option>';
            }
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

function getProvidersAndLocations(practiceID, manualPractice) {
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
            if (manualPractice == '1') {
                content += '<option value="-1">Not Listed</option>';
            }
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

function clearActionFields() {
    $('#form_recall_date').hide();
    $('#form_manual_appointment_date').hide();
    $('#form_manual_appointment_practice').hide();
    $('#form_manual_appointment_provider').hide();
    $('#form_manual_appointment_location').hide();
    $('#form_manual_referredby_details').hide();
    $('#form_manual_appointment_appointment_type').hide();
    $('#form_manual_appointment_existing_relationship').hide();
    $('#form_manual_custom_appointment_appointment_type').hide();
    $('#form_recall_date').val('');
    $('#manual_appointment_date').val('');
    $('#manual_appointment_practice').val('0');
    $('#manual_appointment_provider').val('0');
    $('#manual_appointment_location').val('0');
    $('#manual_appointment_appointment_type').val('');
    $('#existing_relationship_checkbox').prop('checked', false);
    $('#manual_referredby_practice').val('');
    $('#manual_referredby_provider').val('');
    $('#manual_custom_appointment_appointment_type').val('');
    $('#form_action_notes').show();
    $('#form_action_request_email').hide();
    $('#form_action_request_phone').hide();
    $('#form_action_request_sms').hide();
    $('#form_manual_custom_appointment_practice').hide();
    $('#form_manual_custom_appointment_location').hide();
    $('#manual_custom_appointment_practice').val('');
    $('#manual_custom_appointment_location').val('');
}

function referredByProviderSuggestions(searchValue) {
    if (searchValue != '') {
        $.ajax({
            url: '/referredbyproviders',
            type: 'GET',
            data: $.param({
                'provider': searchValue
            }),
            contentType: 'text/html',
            async: false,
            success: function success(e) {
                var data = $.parseJSON(e);
                var content = '';
                data.forEach(function(providerName) {
                    content += '<p class="provider_suggestion_item">' + providerName + '</p>';
                });
                if (content != '') {
                    $('.provider_suggestions').addClass('active');
                    $('.provider_suggestions').html(content);
                } else {
                    $('.provider_suggestions').removeClass('active');
                }
            },
            error: function error() {
                $('p.alert_message').text('Error searching');
                $('#alert').modal('show');
            },
            cache: false,
            processData: false
        });
    } else {
        $('.provider_suggestions').removeClass('active');
    }
}

function referredByPracticeSuggestions(searchValue) {
    if (searchValue != '') {
        $.ajax({
            url: '/referredbypractice',
            type: 'GET',
            data: $.param({
                'practice': searchValue
            }),
            contentType: 'text/html',
            async: false,
            success: function success(e) {
                var data = [];
                data = $.parseJSON(e);
                var content = '';
                data.forEach(function(practiceName) {
                    content += '<p class="practice_suggestion_item">' + practiceName + '</p>';
                });
                if (content != '') {
                    $('.practice_suggestions').addClass('active');
                    $('.practice_suggestions').html(content);
                } else {
                    $('.practice_suggestions').removeClass('active');
                }
            },
            error: function error() {
                $('p.alert_message').text('Error searching');
                $('#alert').modal('show');
            },
            cache: false,
            processData: false
        });
    } else {
        $('.practice_suggestions').removeClass('active');
    }
}

function getPatientContactData(patientID) {
    var formData = {
        'patientID': patientID
    };

    var content = '';
    $.ajax({
        url: '/careconsole/patient_info',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);
            if (data.length === 0) {
                return;
            }

            content += '<div class="form-group"><h4 class="">Patient Details</h4>';

            content += '<p><span class="arial_bold">Name</span><br><span class="arial">' + data.name + '</span><br></p>';
            content += '<div class="demographic_list"><p><span class="arial_bold">PCP</span><br><span class="arial"><input type="text" class="update_demographic" id="pcp" value="' + data.pcp + '"></span><br></p>';
            content += '<p><span class="arial_bold">Timezone</span><br><span class="arial">' + data.timezone + '</span><br></p>';
            content += '<p><span class="arial_bold">Last Seen By</span><br><span class="arial">' + data.last_seen_by + '</span><br></p>';

            content += '<p><span class="arial_bold">Email</span><br><span class="arial"><input type="text" class="update_demographic" id="email" value="' + data.email + '"></span><br></p>';
            content += '<p><span class="arial_bold">Date of Birth</span><br><span class="arial"><input type="text" class="update_demographic" id="dob" value="' + data.dob + '"></span><br></p>';

            content += '<p><span class="arial_bold">Cellphone</span><span class="arial"><input type="text" class="update_demographic" id="cellphone" value="' + data.cellphone + '"><input type="checkbox" class="preferred_phone_checkbox" value="1" data-toggle="tooltip" title="Mark as preferred phone" data-placement="right"';
            if (data.preferred_contact_number.selected != '-1' && data.preferred_contact_number.list[data.preferred_contact_number.selected] == 'Cellphone') {
                content += 'checked'
            }
            content += '></span><br></p>';

            content += '<p><span class="arial_bold">Workphone</span><span class="arial"><input type="text" class="update_demographic" id="workphone" value="' + data.workphone + '"><input type="checkbox" class="preferred_phone_checkbox" value="2" data-toggle="tooltip" title="Mark as preferred phone" data-placement="right"';
            if (data.preferred_contact_number.selected != '-1' && data.preferred_contact_number.list[data.preferred_contact_number.selected] == 'Workphone') {
                content += 'checked'
            }
            content += '></span><br></p>';

            content += '<p><span class="arial_bold">Homephone</span><span class="arial"><input type="text" class="update_demographic" id="homephone" value="' + data.homephone + '"><input type="checkbox" class="preferred_phone_checkbox" value="3" data-toggle="tooltip" title="Mark as preferred phone" data-placement="right"';
            if (data.preferred_contact_number.selected != '-1' && data.preferred_contact_number.list[data.preferred_contact_number.selected] == 'Homephone') {
                content += 'checked'
            }
            content += '></span><br></p>';

            content += '<p><span class="arial_bold">Home Address Line 1</span><br><span class="arial"><input type="text" class="update_demographic" id="address_line_1" value="' + data.address_line_1 + '"></span><br></p>';
            content += '<p><span class="arial_bold">Home Address Line 2</span><br><span class="arial"><input type="text" class="update_demographic" id="address_line_2" value="' + data.address_line_2 + '"></span><br></p>';
            content += '<p><span class="arial_bold">Referred By Provider</span><br><span class="arial"><input type="text" class="update_demographic" id="referred_by_provider" value="' + data.referred_by_provider + '"></span><br></p>';
            content += '<p><span class="arial_bold">Referred By Practice</span><br><span class="arial"><input type="text" class="update_demographic" id="referred_by_practice" value="' + data.referred_by_practice + '"></span><br></p>';
            content += '<p><span class="arial_bold">Special Request</span><br><span class="arial"><input type="text" class="update_demographic" id="special_request" value="' + data.special_request + '"></span><br></p>';

            content += '<p><span class="arial_bold">Insurance Provider</span><br><span class="arial"><input type="text" class="update_demographic" id="insurance_carrier" value="' + data.insurance_carrier + '"></span><br></p>';
            content += '<p><span class="arial_bold">Subscriber Name</span><br><span class="arial"><input type="text" class="update_demographic" id="subscriber_name" value="' + data.subscriber_name + '"></span><br></p>';
            content += '<p><span class="arial_bold">Subscriber DOB</span><br><span class="arial"><input type="text" class="update_demographic" id="subscriber_birthdate" value="' + data.subscriber_birthdate + '"></span><br></p>';
            content += '<p><span class="arial_bold">Group No</span><br><span class="arial"><input type="text" class="update_demographic" id="group_number" value="' + data.group_number + '"></span><br></p>';
            content += '<p><span class="arial_bold">Subscriber ID</span><br><span class="arial"><input type="text" class="update_demographic" id="subscriber_id" value="' + data.subscriber_id + '"></span><br></p>';
            content += '<p><span class="arial_bold">Relation to patient</span><br><span class="arial"><input type="text" class="update_demographic" id="relation_to_patient" value="' + data.relation_to_patient + '"></span><br></p>';

            content += '</div></div>';

        },
        error: function error() {
            $('p.alert_message').text('Error getting patient information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false

    });

    $('.patient_contact_request_info').html(content);

    $('.update_demographic#subscriber_birthdate').datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });
    $('.update_demographic#dob').datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });

    updatePatientDemographic = true;

    $('[data-toggle="tooltip"]').tooltip();
}

function updateManualScheduleData(consoleID) {

    var appointmentTypeList = '<option value="">Appointment Type</option>';
    var practiceList = '<option value="0">Select Practice</option>';
    $.ajax({
        url: '/careconsole/manual_schedule_data/' + consoleID,
        type: 'GET',
        contentType: 'text/html',
        async: false,
        success: function success(data) {
            for (var key in data.appointment_type) {
                appointmentTypeList += '<option value="' + data.appointment_type[key] + '">' + data.appointment_type[key] + '</option>';
            }
            for (var key in data.practiceList) {
                var manually_created = data.practiceList[key].manually_created ? data.practiceList[key].manually_created : '';
                practiceList += '<option value="' + data.practiceList[key].id + '" data-manually_created="' + manually_created + '">' + data.practiceList[key].name + '</option>';
            }
            $('#manual_referredby_practice').val(data.referred_by_practice != '-' ? data.referred_by_practice : '');
            $('#manual_referredby_provider').val(data.referred_by_provider != '-' ? data.referred_by_provider : '');
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

    appointmentTypeList += '<option value="-1">Not listed</option>';
    practiceList += '<option value="-1">Not listed</option>';
    $('#manual_appointment_appointment_type').html(appointmentTypeList);
    $('#manual_appointment_practice').html(practiceList);
}

function clearStageFilter() {
    $('#filter_type').val('-1');
    $('#filter_value').val('');
}