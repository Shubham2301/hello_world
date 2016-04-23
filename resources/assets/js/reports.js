google.load("visualization", "1", {
    packages: ["corechart"]
});
google.load("visualization", "1.1", {
    packages: ["corechart"]
});

var filterOptions;
function resetFilter() {
    filterOptions = {
        "type": "none",
        "status_of_patients": "none",
        "disease_type": "none",
        "severity_scale": "none",
        "incomming_referrals":
            {
                "appointment_type": "none",
                "referred_by":
                    {
                        "type": "none",
                        "name": "none"
                }

        }
    ,
        "patient_demographics":
            {
                "age": "none",
                "gender": "none",
                "insurance_type": "none"
        }
    ,
        "referred_to":
            {
                "type": "none",
                "name": "none"
        }

    };
    if ($('.historical_header').hasClass('active')) {
        filterOptions.type = "historical";
    }
    else {
        filterOptions.type = "real-time";
    }

}

function addFilter(name, value, meta) {
    switch (name) {
        case 'status_of_patients':
            if (filterOptions.status_of_patients != 'none') {
                $('.filter[data-id="' + name + '"]').remove();
            }
            filterOptions.status_of_patients = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'disease_type':
            if (filterOptions.disease_type != 'none')
                $('.filter[data-id="disease_type"]').remove();
            filterOptions.disease_type = value;
            filterOptions.severity_scale = 'none';
            $('#drilldown_filters').append('<div class="filter" data-id="disease_type"><div class="filter_name"><span class="item_value report_content_label">' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'severity_scale':
            if (filterOptions.disease_type != 'none')
                $('.filter[data-id="disease_type"]').remove();
            filterOptions.disease_type = value;
            filterOptions.severity_scale = meta;
            $('#drilldown_filters').append('<div class="filter" data-id="disease_type"><div class="filter_name"><span class="item_value report_content_label">' + value + ' : ' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'appointment_type':
            if (filterOptions.incomming_referrals.appointment_type != 'none') {
                $('.filter[data-id="' + name + '"]').remove();
            }
            filterOptions.incomming_referrals.appointment_type = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'referred_by_practice':
            if (filterOptions.incomming_referrals.referred_by.value != "none") {
                $('.filter[data-id="referred_by_practice_user"]').remove();
                $('.filter[data-id="referred_by_practice"]').remove();
            }
            filterOptions.incomming_referrals.referred_by.type = "practice";
            filterOptions.incomming_referrals.referred_by.name = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">Referred By ' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            $('#referred_by_meta').html(meta);
            break;
        case 'referred_by_practice_user':
            if (filterOptions.incomming_referrals.referred_by.value != "none") {
                $('.filter[data-id="referred_by_practice_user"]').remove();
                $('.filter[data-id="referred_by_practice"]').remove();
            }
            filterOptions.incomming_referrals.referred_by.type = "practice_user";
            filterOptions.incomming_referrals.referred_by.name = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">Referred By ' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            $('#referred_by_meta').html(meta);
            break;
        case 'age':
            if (filterOptions.patient_demographics.age != 'none') {
                $('.filter[data-id="' + name + '"]').remove();
            }
            filterOptions.patient_demographics.age = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'gender':
            if (filterOptions.patient_demographics.gender != 'none') {
                $('.filter[data-id="' + name + '"]').remove();
            }
            filterOptions.patient_demographics.gender = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'insurance_type':
            if (filterOptions.patient_demographics.insurance_type != 'none') {
                $('.filter[data-id="' + name + '"]').remove();
            }
            filterOptions.patient_demographics.insurance_type = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            break;
        case 'referred_to_practice':
            if (filterOptions.referred_to.type != 'none') {
                $('.filter[data-id="referred_to_practice_user"]').remove();
                $('.filter[data-id="referred_to_practice"]').remove();
            }
            filterOptions.referred_to.type = "practice";
            filterOptions.referred_to.name = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">Referred To ' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            $('#referred_to_meta').html(meta);
            break;
        case 'referred_to_practice_user':
            if (filterOptions.referred_to.type != "none") {
                $('.filter[data-id="referred_to_practice_user"]').remove();
                $('.filter[data-id="referred_to_practice"]').remove();
            }
            filterOptions.referred_to.type = "practice_user";
            filterOptions.referred_to.name = value;
            $('#drilldown_filters').append('<div class="filter" data-id="' + name + '"><div class="filter_name"><span class="item_value report_content_label">Referred To ' + meta + '</span></div><span class="filter_remove ">x</span></div>');
            $('#referred_to_meta').html(meta);
            break;
    }
}

function removeFilter(name) {

    switch (name) {
        case 'status_of_patients':
            filterOptions.status_of_patients = 'none';
            break;
        case 'disease_type':
            filterOptions.disease_type = 'none';
            filterOptions.severity_scale = 'none';
            break;
        case 'severity_scale':
            filterOptions.disease_type = 'none';
            filterOptions.severity_scale = 'none';
            break;
        case 'appointment_type':
            filterOptions.incomming_referrals.appointment_type = 'none';
            break;
        case 'referred_by_practice':
            filterOptions.incomming_referrals.referred_by.type = 'none';
            filterOptions.incomming_referrals.referred_by.name = 'none';
            $('#referred_by_meta').html('');
            break;
        case 'referred_by_practice_user':
            filterOptions.incomming_referrals.referred_by.type = 'none';
            filterOptions.incomming_referrals.referred_by.name = 'none';
            $('#referred_by_meta').html('');
            break;
        case 'age':
            filterOptions.patient_demographics.age = 'none';
            break;
        case 'gender':
            filterOptions.patient_demographics.gender = 'none';
            break;
        case 'insurance_type':
            filterOptions.patient_demographics.insurance_type = 'none';
            break;
        case 'referred_to_practice':
            filterOptions.referred_to.type = 'none';
            filterOptions.referred_to.name = 'none';
            $('#referred_to_meta').html('');
            break;
        case 'referred_to_practice_user':
            filterOptions.referred_to.type = 'none';
            filterOptions.referred_to.name = 'none';
            $('#referred_to_meta').html('');
            break;
    }
}




$(document).ready(function () {

    $('.historical_section').hide();
    $('.sidebar_historical').on('click', function () {
        $("#population_report_options").collapse('hide');
        $('.expandable_sidebar').removeClass('active');
        $('.expandable_sidebar_historical').addClass('active');
        $('.historical_header').addClass('active');
        $('.historical_sub_header').addClass('active');
        $('.realtime_header').removeClass('active');
        $('.realtime_sub_header').removeClass('active');
        $('.realtime_section').hide();
        $('.historical_section').show();
        $('.filter_remove').parent().remove();
        resetFilter();
        clearHtml();
        getReport();
    });
    $('.sidebar_realtime').on('click', function () {
        $('.expandable_sidebar').addClass('active');
        $('.expandable_sidebar_historical').removeClass('active');
        $('.realtime_header').addClass('active');
        $('.realtime_sub_header').addClass('active');
        $('.historical_header').removeClass('active');
        $('.historical_sub_header').removeClass('active');
        $('.historical_section').hide();
        $('.realtime_section').show();
        $('.filter_remove').parent().remove();
        resetFilter();
        clearHtml();
        getReport();
        $("#population_report_options").collapse('show');
    });
    $("li").click(function () {
        $(this.parentNode).children("li").removeClass("active");
        $(this).addClass("active");

        if ($('li.active').hasClass('referred_by')) {
            $('.chart').addClass('referred_by');
            $('.chart').removeClass('referred_to');
        } else if($('li.active').hasClass('referred_to')) {
            $('.chart').addClass('referred_to');
            $('.chart').removeClass('referred_by');
        }
        getReport();
    });


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
    $('#end_date').on('change', function(){
        getReport();
    });
    $('#start_date').on('change', function(){
        getReport();
    });
    resetFilter();
    getReport();
    $("#population_report_options").collapse('show');
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

    $(document).on('click', '.drilldown_item', function () {

        if ($(this).hasClass('disable_drilldown'))
            return;

        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        var meta = $(this).attr('data-meta');
        addFilter(type, id, meta);
        clearHtml();
        getReport();
    });

    $(document).on('click', '.filter_remove', function () {

        var type = $(this).parent().attr('data-id');
        $(this).parent().remove();
        removeFilter(type);
        clearHtml();
        getReport();
    });
});

function getReport() {

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        filters: filterOptions
    };

    $.ajax({
        url: '/careconsole_reports/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (e) {
            var data = $.parseJSON(e);
            if (data.length === 0) {
                return;
            }
            if (data.status_of_patients.length !== 0) {
                renderStatusOfPatients(data.status_of_patients);
            }
            if (data.disease_type.length !== 0) {
                renderDiseaseType(data.disease_type);
            }
            if (data.insurance_demographics.length !== 0) {
                renderInsuranceDemographics(data.insurance_demographics);
            }
            if (data.appointment_type.length !== 0) {
                renderAppointmentTypeDemographics(data.appointment_type);
            }
            if (data.gender_demographics.length !== 0) {
                renderGenderDemographics(data.gender_demographics);
            }

            renderReferredTo(data.referred_to);

            renderReferredBy(data.referred_by);

            if (data.age_demographics.length !== 0) {
                renderAgeDemographics(data.age_demographics);
            }
            renderAppointmentStatusDemographics(data.appointment_status);
        },
        error: function () {
            alert('Error Refreshing');
        },
        cache: false,
        processData: false
    });
}

function renderStatusOfPatients(data) {

    var rowContent = '';
    var disable = '';
    var colContent = '';
    for (var i = 0; i < data.length; i++) {

        if (data[i].count == 0) {
            disable = 'disable_drilldown';
        } else {
            disable = '';
        }

        if (i % 2 == 0) {
            rowContent += '<div class="row remove_margin">';
        }

        rowContent += '<div class="drilldown_item ' + disable + '" data-type="status_of_patients" data-id="' + data[i].id + '" data-meta="' + data[i].name + '"><div class="col-xs-7 col-sm-3"><p class="report_content_label">' + data[i].name + '</p></div><div class="col-xs-2 col-sm-1"><p class="report_content_value">' + data[i].count + '</p></div><div class="col-xs-3 col-sm-2"><p class="report_content_value">' + data[i].percent + '%</p></div></div>';

        if (i % 2 == 1) {
            rowContent += '</div>';
        }

        colContent += '<div class="row"><div class="col-xs-12 drilldown_item sidebar_drilldown ' + disable + '" data-type="status_of_patients" data-id="' + data[i].id + '" data-meta="' + data[i].name + '"><p class="sidebar_item">' + data[i].name + '</p></div></div>';

    }
    $('#status_of_patients').html(rowContent);
    $('#population_report_options').html(colContent);

}

function renderGenderDemographics(data) {
    var disable = '';
    if (data.male == 0) {
        disable = 'disable_drilldown';
    } else {
        disable = '';
    }
    $('#male_percent').html('<p class="report_content_value">' + data.male + '%</p');
    $('#female_percent').html('<p class="report_content_value">' + data.female + '%</p');
}

function renderAgeDemographics(data) {
    var ageData = formatAgeData(data);
    drawAgeChart(ageData);
}

function renderReferredTo(data) {

    if ($('.historical_header').hasClass('active')) {
        if ($('.chart').hasClass('referred_to')) {
            {
                drawReferredToChart(data);
            }
        }
    } else if(data.total !== 0) {
        var type = data.type;
        data = data.data;
        var content = '';
        var disable = '';


        for (var i = 0; i < data.length; i++) {
            if (data[i].count == 0) {
                disable = 'disable_drilldown';
            } else {
                disable = '';
            }
            content += '<div class="col-xs-12 remove-padding drilldown_item ' + disable + '" data-type="referred_to_' + type + '" data-id="' + data[i].id + '" data-meta="' + data[i].name + '"><div class="col-xs-8"><p class="report_content_label">' + data[i].name + '</p></div><div class="col-xs-4"><p class="report_content_value">' + data[i].count + '</p></div></div>';
        }

        $('#referred_to').html(content);
    }
}

function renderReferredBy(data) {

    if ($('.historical_header').hasClass('active')) {
        if ($('.chart').hasClass('referred_by')) {
            drawReferredByChart(data);
        }
    } else if(data.total !== 0) {
        var type = data.type;
        data = data.data;
        var content = '';
        var disable = '';


        for (var i = 0; i < data.length; i++) {
            if (data[i].count == 0) {
                disable = 'disable_drilldown';
            } else {
                disable = '';
            }
            content += '<div class="col-xs-12 remove-padding drilldown_item ' + disable + '" data-type="referred_by_' + type + '" data-id="' + data[i].name + '" data-meta="' + data[i].name + '"><div class="col-xs-8"><p class="report_content_label">' + data[i].name + '</p></div><div class="col-xs-4"><p class="report_content_value">' + data[i].count + '</p></div></div>';
        }

        $('#referred_by').html(content);
    }
}

function renderInsuranceDemographics(data) {

    var content = '';
    var disable = '';
    for (var i = 0; i < data.length; i++) {
        if (data[i].count === 0) {
            disable = 'disable_drilldown';
        } else {
            disable = '';
        }
        content += '<div class="col-xs-12 remove-padding drilldown_item ' + disable + '" data-type="insurance_type" data-id="' + data[i].name + '" data-meta="' + data[i].name + '"><div class="col-xs-8"><p class="report_content_label">' + data[i].name + '</p></div><div class="col-xs-4"><p class="report_content_value">' + data[i].count + '</p></div></div>';
    }
    $('#insurance_type').html(content);
}

function renderAppointmentTypeDemographics(data) {

    var content = '';
    var disable = '';
    for (var i = 0; i < data.length; i++) {
        if (data[i].count === 0) {
            disable = 'disable_drilldown';
        } else {
            disable = '';
        }
        content += '<div class="col-xs-12 remove-padding drilldown_item ' + disable + '" data-type="appointment_type" data-id="' + data[i].name + '" data-meta="' + data[i].name + '"><div class="col-xs-8"><p class="report_content_label">' + data[i].name + '</p></div><div class="col-xs-4"><p class="report_content_value">' + data[i].count + '</p></div></div>';
    }
    $('#appointment_type').html(content);
}

function renderAppointmentStatusDemographics(info) {

    var content = '';

    for (var key in info) {
        if (info.hasOwnProperty(key)) {
            content += '<div class="col-xs-12 remove-padding" data-type="appointment_status"><div class="col-xs-8"><p class="report_content_label">' + info[key][1] + '</p></div><div class="col-xs-4"><p class="report_content_value">' + info[key][0] + '</p></div></div>';
        }
    }

    $('#appointment_status').html(content);
}

function renderDiseaseType(data) {

    var content = '';
    var disable = '';
    var id;
    for (var i = 0; i < data.length; i++) {

        name = data[i].name;
        id = name.replace(/\s/g, '');

        content += '<div class="col-xs-12 remove-padding"><div class="col-xs-6 drilldown_item" data-type="disease_type" data-id="' + data[i].name + '" data-meta="' + data[i].name + '"><p class="report_content_label">' + data[i].name + '</p></div><div class="col-xs-4"><select class="select_dropdown select_severity" data-id="' + data[i].name + '">';
        for (var j = 0; j < data[i].severity.length; j++) {
            if (data[i].count == 0) {
                continue;
            }
            content += '<option value="' + data[i].severity[j].count + '">' + data[i].severity[j].type + '</option>';
        }


        content += '</select></div><div class="col-xs-2 drilldown_item ' + disable + '" id="disease_type_' + id + '" data-type="severity_scale" data-id="' + data[i].name + '" data-meta="' + data[i].severity[0].type + '"><p class="report_content_value">' + data[i].severity[0].count + '</p></div></div>';
    }

    $('#disease_type').html(content);

}

function drawAgeChart(dataArray) {

    var data = google.visualization.arrayToDataTable(dataArray);

    var options = {
        pieSliceTextStyle: {
            color: 'white',
        },
        legend: 'none',
        chartArea: {
            width: '90%'
        },
        pieHole: 0.3,
        pieSliceText: 'label',
        slices: {
            0: {
                color: '#7acfa9'
            },
            1: {
                color: '#006837'
            },
            2: {
                color: '#009245'
            },
            3: {
                color: '#346639'
            },
            4: {
                color: '#08a172'
            }
        }
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
}
function formatAgeData(data) {
    var ageData = [[]];
    ageData[0] = ['Range', 'No. of Patients'];
    var ageLength = Object.keys(data).length;
    for (var i = 1; i <= ageLength; i++) {
        ageData[i] = [data['category' + i].name, data['category' + i].count];
    }

    return ageData;
}
function clearHtml() {
    $('#status_of_patients').html("");
    $('#population_report_options').html("");
    $('#male_percent').html("");
    $('#female_percent').html("");
    $('#referred_to').html("");
    $('#referred_by').html("");
    $('#insurance_type').html("");
    $('#appointment_type').html("");
    $('#disease_type').html("");
    $('#piechart').html("");
    $('#most_referral_to').html("");
    $('#most_referral_by').html("");
    $('#most_appointment_type').html("");
    $('#linechart_material').html("");

}
function drawReferredByChart(data) {
    var data_hospital = new google.visualization.DataTable();
    data_hospital.addColumn('string', 'Hospital Name');
    data_hospital.addColumn('number', 'Referals');

    var type = 'referred_by_' + data.type;
    $('.chart').attr('data-type', type);
    data = data.data;
    if (!data) {

    } else {
        for (var i = 0; i < data.length; i++) {
            data_hospital.addRow([data[i].name, data[i].count]);
        }
    }

    var options = {
        legend: {
            position: 'none'
        },
        chartArea: {
            width: '90%',
            height: '75%'
        },
        hAxis: {
            textStyle: {
                color: '#4d4d4d'
            },
        },
        vAxis: {
            textStyle: {
                color: '#4d4d4d'
            },
        },
        colors: ['#00a99d'],
        fontName: 'Montserrat',
        pointSize: 10,
        pointShape: 'circle',
        axes: {
            x: {
                0: {
                    side: 'bottom',
                    label: ""
                }
            }
        },
    };
    var chart_hospital = new google.visualization.ColumnChart(document.getElementById('linechart_material'));
    chart_hospital.draw(data_hospital, options);

    google.visualization.events.addListener(chart_hospital, 'select', showdoctor);

    function showdoctor(e) {
        var selection = chart_hospital.getSelection();
        var item = selection[0];
        var type = $('.chart').attr('data-type');
        var meta = data_hospital.getValue(item.row, 0);
        var id = data_hospital.getValue(item.row, 0);
        addFilter(type, id, meta);
        getReport();
    }
}

function drawReferredToChart(data) {
    var data_hospital = new google.visualization.DataTable();
    data_hospital.addColumn('string', 'Hospital Name');
    data_hospital.addColumn('number', 'Referals');
    var type = 'referred_to_' + data.type;
    $('.chart').attr('data-type', type);
    data = data.data;
    if (!data) {

    } else {
        for (var i = 0; i < data.length; i++) {
            data_hospital.addRow([data[i].name, data[i].count]);
        }
    }
    var options = {
        legend: {
            position: 'none'
        },
        chartArea: {
            width: '90%',
            height: '75%'
        },
        hAxis: {
            textStyle: {
                color: '#4d4d4d'
            },
        },
        vAxis: {
            textStyle: {
                color: '#4d4d4d'
            },
        },
        colors: ['#00a99d'],
        fontName: 'Montserrat',
        pointSize: 10,
        axes: {
            x: {
                0: {
                    side: 'bottom',
                    label: ""
                }
            }
        },
    };
    var chart_hospital = new google.visualization.ColumnChart(document.getElementById('linechart_material'));
    chart_hospital.draw(data_hospital, options);
    google.visualization.events.addListener(chart_hospital, 'select', showdoctor);

    function showdoctor(e) {
        var selection = chart_hospital.getSelection();
        var item = selection[0];
        var id = data[item.row].id;
        var type = $('.chart').attr('data-type');
        var meta = data_hospital.getValue(item.row, 0);
        addFilter(type, id, meta);
        getReport();
    }
}
