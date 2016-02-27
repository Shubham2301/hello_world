$(document).ready(function() {
    loadImportForm();
    $('#datetimepicker_action_date').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    $('#search_bar_open').on('click', function() {
        if (($('#search_bar_open').hasClass('active'))) {
            $('#search_bar_open').removeClass('active');
            $('#search_bar_open').removeClass('glyphicon-chevron-left');
            $('#search_bar_open').addClass('glyphicon-chevron-right');
            $('#search_do').addClass('active');
            $('.search').addClass('active');
            $('#search_data').addClass('active');
        } else {
            $('#search_bar_open').addClass('active');
            $('#search_bar_open').removeClass('glyphicon-chevron-right');
            $('#search_bar_open').addClass('glyphicon-chevron-left');
            $('#search_do').removeClass('active');
            $('.search').removeClass('active');
            $('#search_data').removeClass('active');
            $('.search_result_info').removeClass('active');
            $('.search_result').removeClass('active');
        }
    });

    $(document).on('click', '.C3_day_box', function() {

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $('.C3_day_box').removeClass('active');
            $(this).addClass('active');
        }
        var kpi_name = $(this).attr('data-name');
        var stageID = $(this).parent().attr('data-stageid');
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
            getPatientData();
        }


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
            $('.drilldown>.section-header').html($(this).find('p').html());
            $('.drilldown>.subsection-header>p').html('');
            $('.circle drilldown_kpi_indicator').css('background-color', 'transparent');
            bucketData($(this).attr('data-name'));
        }
    });
    $('#recall_date').datetimepicker({
        format: 'YYYY/MM/DD',
    });

    $('.day_box.active').on('click', function() {
        $(this).removeClass('active');
    });

    $('#search_do').on('click', searchc3);

    $('.c3_overview_link').on('click', function() {
        refreshOverview();
        $('.c3_overview_link').removeClass('active');
        $('.control_section').removeClass('active');
        $('ul.c3_sidebar_list').removeClass('active');
        $('.before_drilldown').show();
        $('.drilldown').removeClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('#current_stage').val('-1');
    });

    $('.info_section').on('click', function() {
        $('.c3_overview_link').addClass('active');
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
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('.subsection-header').removeClass('active');
        $('.console_buckets').removeClass('active');

        var stage_id = '';
        var stage_name = '';
        stage_id = $(this).attr('data-id');
        stage_name = $(this).attr('data-name');
        clearHTML();
        $('#current_stage').val(stage_id);
        showStageData(stage_id, stage_name);
    });
    $('#drilldown_patients_listing').on('click', '.careconsole_action', function() {
        if ($('#current_stage').val() === '-1') {
            return;
        }
        $('#form_recall_date').hide();
        switch ($(this).attr('data-name')) {
            case 'schedule':
                window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $(this).parent().attr('data-patientid');
                break;
            case 'recall-later':
                $('#form_recall_date').show();
            default:
                $('#action_patient_id').val($(this).parent().attr('data-patientid'));
                $('#action_id').val($(this).attr('data-id'));
                $('#action_console_id').val($(this).parent().attr('data-consoleid'));
                $('#action_stage_id').val($('#current_stage').val());
                $('#action_header').html($(this).attr('data-displayname'));
                if ($(this).attr('data-name') === 'move-to-console') {

                }
                var results = actionResults[$(this).attr('data-id')];
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
                $('#actionModal').modal('show');
        }
    });
    $('#records_action_dropdown').on('click', '.careconsole_action', function() {
        switch ($(this).attr('data-name')) {
            case 'schedule':
                window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $(this).attr('data-patientid');
                break;
            default:
                $('#action_patient_id').val($(this).attr('data-patientid'));
                $('#action_id').val($(this).attr('data-id'));
                $('#action_console_id').val($(this).parent().attr('data-consoleid'));
                $('#action_stage_id').val($(this).attr('data-stageid'));
                $('#action_header').html($(this).attr('data-displayname'));
                var results = actionResults[$(this).attr('data-id')];
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
                $('#actionModal').modal('show');
        }
    });
    $('#search_action_dropdown').on('click', '.careconsole_action', function() {
        switch ($(this).attr('data-name')) {
            case 'schedule':
                window.location = "/providers?referraltype_id=6&action=careconsole&patient_id=" + $(this).attr('data-patientid');
                break;
            default:
                $('#action_patient_id').val($(this).attr('data-patientid'));
                $('#action_id').val($(this).attr('data-id'));
                $('#action_console_id').val($(this).parent().attr('data-consoleid'));
                $('#action_stage_id').val($(this).attr('data-stageid'));
                $('#action_header').html($(this).attr('data-displayname'));
                var results = actionResults[$(this).attr('data-id')];
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
                $('#actionModal').modal('show');
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

});

var actionResults = {};
var patientdata = [];
var llimit = -1;
var ulimit = -1;


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
                patientdata = $.parseJSON(e);
                if (patientdata.length > 1) {
                    var content = '';
                    var index = 0;
                    patientdata.forEach(function(patient) {
                        content += '<div class="search_result_row row" data-index= "' + index + '"><div class="col-xs-12 search_result_row_text"><p class="result_title result_name">' + patient.name + '</p><p class="result_title scheduled_name"><strong>Scheduled-to&nbsp;&nbsp;</strong>' + patient.scheduled_to + '</p></div></div>';
                        index++;
                    });
                    $('.search_result').html(content);
                    $('.search_result').addClass('active');
                } else if (patientdata.length != 0) {
                    setSearchFields(0);
                    $('#back_to_search').removeClass('active');
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

    getPatientData();
}

function showStageData(stage_id, stage_name) {
    $('#sidebar_' + stage_id).addClass('sidebar_items_active');
    $('.drilldown>.section-header').html(stage_name);
    $('#current_stage').val(stage_id);
    $('#current_kpi').val('0');

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
            $('.control_section').html(controls);
            $('.dropdown-menu.action_dropdownmenu').html(actionList);
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

    var formData = {
        'console_id': $('#action_console_id').val(),
        'stage_id': $('#action_stage_id').val(),
        'action_id': $('#action_id').val(),
        'recall_date': $('#recall_date').val(),
        'action_result_id': $('#action_result_id').val(),
        'notes': $('#action_notes').val()
    };

    $.ajax({
        url: '/careconsole/action',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        cache: false,
        async: false,
        success: function success(e) {
            $('#actionModal').modal('hide');
            getPatientData();
            $('#action_notes').html('');
            $('#action_notes').val('');
            $('#action_result_id').val(0);
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
    $('.result_title.stage_name').html(patient.stage_name);
    $('.result_text.scheduled_to').text(patient.scheduled_to);
    $('.result_text.appointment_date').text(patient.appointment_date);
    $('.search_result_info').addClass('active');
    $('.search_result').removeClass('active');
    $('#status_color').css('background-color', patient.stage_color);
    var content = '';
    patient.actions.forEach(function(action) {
        content += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '" data-patientid = "' + patient.id + '" data-consoleid="' + patient.console_id + '" data-stageid="' + patient.stage_id + '" ><a href="#">' + action.display_name + '</a></li>';
    });
    $('#search_action_dropdown').html(content);
    $('#back_to_search').removeClass('active');
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
            $('.patient_records_info').find('.attempt_phone').text(data.attempt_phon);
            $('.patient_records_info').find('.attempt_archive').text(data.archive);
            $('.patient_records_info').find('.attempt_other').text(data.other);
            var content = '';
            data.actions.forEach(function(action) {
                content += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '" data-patientid= "' + data.patient_id + '" data-consoleid="' + consoleID + '" data-stageid = "' + data.stageid + '"><a href="#">' + action.display_name + '</a></li>';
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
