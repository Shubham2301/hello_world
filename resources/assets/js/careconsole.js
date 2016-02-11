$(document).ready(function () {
    loadImportForm();
    $('#datetimepicker_action_date').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    $('#search_bar_open').on('click', function () {
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
            $('.search_result').removeClass('active');
        }
    });

    $('.C3_day_box').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $('.C3_day_box').removeClass('active');
            $(this).addClass('active');
        }
    });

    $('.day_box.active').on('click', function () {
        $(this).removeClass('active');
    });

    $('#search_do').on('click', searchc3);

    $('.c3_overview_link').on('click', function () {
        $('.c3_overview_link').removeClass('active');
        $('.control_section').removeClass('active');
        $('ul.c3_sidebar_list').removeClass('active');
        $('.before_drilldown').show();
        $('.drilldown').removeClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('#current_stage').val('-1');
    });

    $('.info_section').on('click', function () {
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
    $('.stage').on('click', function () {
        $('.c3_overview_link').addClass('active');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('.subsection-header').removeClass('active');
        var stage_id = '';
        var stage_name = '';
        stage_id = $(this).attr('data-id');
        stage_name = $(this).attr('data-name');
        clearHTML();
        $('#current_stage').val(stage_id);
        showStageData(stage_id, stage_name);
    });
    $('.drilldown_content').on('click', '.careconsole_action', function () {
        if ($('#current_stage').val() === '-1')
            return;

        switch ($(this).attr('data-name')) {
        case 'schedule':
            window.location = "/providers?referraltype_id=6&action=schedule_appointment&patient_id=" + $(this).parent().attr('data-patientid');
            break;
        default:
            $('#action_patient_id').val($(this).parent().attr('data-patientid'));
            $('#action_id').val($(this).attr('data-id'));
            $('#action_console_id').val($(this).parent().attr('data-consoleid'));
            $('#action_stage_id').val($('#current_stage').val());
            $('#action_header').html($(this).attr('data-displayname'));
            $('#actionModal').modal('show');
            var results = actionResults[$(this).attr('data-id')];
            if(results.length > 0)
            {
                var content = '<option value="0">Select Action Result</option>';
                results.forEach(function (result) {
                    console.log(result);
                    content += '<option value="' + result.action_result_id + '">' + result.display_name + '</option>';
                });
                $('#action_result_id').html(content);
                $('#action_results').show();
            }
            else if(results.length === 0 ){
                $('#action_results').hide();
            }
        }
    });

});

var actionResults = {};

function searchc3() {
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
                e = $.parseJSON(e);
                if (e.length != 0) { //replace with loop for multiple patients in search result.
                    $('.result_title.stage_name').html(e[0].stage_name);
                    $('.search_result').addClass('active');
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

    var patients = getPatientData(stage_id, kpi_id);
}

function showStageData(stage_id, stage_name) {
    $('#sidebar_' + stage_id).addClass('sidebar_items_active');
    $('.drilldown>.section-header').html(stage_name);

    var patients = getPatientData(stage_id);
}

function clearHTML() {
    $('.drilldown>.section-header').html('');
    $('.drilldown>.subsection-header>p').html('');
    $('.drilldown_content').html('');
}

function getPatientData(stageID, kpiName = '') {

    var formData = {
        'stage': stageID,
        'kpi': kpiName
    }
    $('.drilldown_content').html('');
    $.ajax({
        url: '/careconsole/drilldown',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);
            var content = '';
            var actionList = '';
            var patients = data.patients;
            var actions = data.actions;
            if (actions.length > 0) {
                actions.forEach(function (action) {
                    actionResults[action.id] = action.action_results;
                    actionList += '<li class="careconsole_action" data-id="' + action.id + '" data-displayname="' + action.display_name + '" data-name="' + action.name + '"><a href="#">' + action.display_name + '</a></li>';
                });
            }
            if (patients.length > 0) {
                patients.forEach(function (patient) {
                    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>' + patient.name + '</p></div><div class="col-xs-2"><p>' + patient.phone + '</p></div><div class="col-xs-2">' + patient.request_recieved + '</div><div class="col-xs-2">' + patient.appointment_date + '</div><div class="col-xs-2">' + patient.scheduled_to + '</div><div class="col-xs-2"><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu' + patient.patient_id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="float: right;background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span><ul class="dropdown-menu" aria-labelledby="dropdownMenu' + patient.patient_id + '" data-patientid="' + patient.patient_id + '"" data-consoleid="' + patient.console_id + '" style="width: 200%;border-radius: 3px;margin-left: -100%;text-align: right;max-height: 15em;top:2em;overflow-y:scroll">' + actionList + '</ul></div></div></div>';
                });
            }
            $('.drilldown_content').html(content);
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
        'action_result_id': $('#action_result_id').val(),
        'notes': $('#action_notes').val(),
        'date': $('#action_date').val(),
    };

    $.ajax({
        url: '/careconsole/action',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            getPatientData(stageID);
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
