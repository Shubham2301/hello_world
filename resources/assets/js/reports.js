google.load("visualization", "1", {
    packages: ["corechart"]
});
google.load("visualization", "1.1", {
    packages: ["corechart"]
});


$(document).ready(function () {
    $('.historical_section').hide();
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
});

function getReport() {

    var dateFilter = {
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val()
    };

    var formData = {
        dates: dateFilter
    };

    $.ajax({
        url: '/careconsole_reports/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'application/json',
        async: false,
        success: function (e) {
            var data = $.parseJSON(e);
//            if (data.length === 0) {
//                return;
//            }
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
            if (data.referred_to.total !== 0) {
                renderReferredTo(data.referred_to);
            }
            if (data.referred_by.total !== 0) {
                renderReferredBy(data.referred_by);
            }
            if (data.age_demographics.length !== 0) {
                renderAgeDemographics(data.age_demographics);
            }
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
    var colContent = '<div class="row"><div class="col-xs-4"></div><div class="col-xs-7"><p class="sidebar_item active">Dashboard View</p></div></div>';
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

        colContent += '<div class="row"><div class="col-xs-4"></div><div class="col-xs-7 drilldown_item sidebar_drilldown ' + disable + '" data-type="status_of_patients" data-id="' + data[i].id + '" data-meta="' + data[i].name + '"><p class="sidebar_item">' + data[i].name + '</p></div></div>';

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
    } else {
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
    } else {
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
