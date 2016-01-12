$(document).ready(function () {

    var id = $('#form_patient_id').attr('value');
    var formData = {
        'id': id
    };
    getPatientInfo(formData);
    $('.view_selected_patient').on('click', showPatientInfo);
    $('.change_selected_patient').on('click', changePatientInfo);
    $('#change_patient_button').on('click', function () {
        $('#form_select_provider').attr('action', "http://ocuhub.dev/patients");
        $('#form_provider_id').prop('disabled', true);
        $('#form_practice_id').prop('disabled', true);
        $('#form_select_provider').submit();
    });
    $('#search_practice_button').on('click', function () {
        $("#add_practice_search_option").trigger("click");
        $('#search_practice_input').val('');
        var searchdata = getSearchType();
        if ($('.view_selected_patient').hasClass('remove')) {
        showPatientInfo();}
        getProviders(searchdata);
    });

    $('.lastseenby_show').on('click', function () {
        $('.lastseen_content').toggleClass('active');
        if ($('.lastseen_content').hasClass('active')) {
            $('.lastseenby_icon').removeClass('glyphicon-chevron-right');
            $('.lastseenby_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.lastseenby_icon').removeClass('glyphicon-chevron-down');
            $('.lastseenby_icon').addClass('glyphicon-chevron-right');
        }
    });

    $('.referredby_show').on('click', function () {
        $('.referredby_content').toggleClass('active');
        if ($('.referredby_content').hasClass('active')) {
            $('.referredby_icon').removeClass('glyphicon-chevron-right');
            $('.referredby_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.referredby_icon').removeClass('glyphicon-chevron-down');
            $('.referredby_icon').addClass('glyphicon-chevron-right');
        }
    });
    $('.insurance_provider_show').on('click', function () {
        $('.insurance_provider_content').toggleClass('active');
        if ($('.insurance_provider_content').hasClass('active')) {
            $('.insurance_provider_icon').removeClass('glyphicon-chevron-right');
            $('.insurance_provider_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.insurance_provider_icon').removeClass('glyphicon-chevron-down');
            $('.insurance_provider_icon').addClass('glyphicon-chevron-right');
        }
    });

    $('.practice_list').on('click', '.practice_list_item', function () {
        var provider_id = $(this).attr('data-id');
        var practice_id = $(this).attr('practice-id');
        var formData = {
            'provider_id': provider_id,
            'practice_id': practice_id
        };

        getProviderInfo(formData);
    });
    $('#change_practice_button').on('click', function () {

        $('.practice_list').addClass('active');
        $('.practice_info').removeClass('active');
        $('.patient_previous_information').addClass('active');

    });

    $('#add_practice_search_option').on('click', function () {
        var type = $('#search_practice_input_type').val();
        var value = $('#search_practice_input').val();
        if (value != '') {
            if ($('.view_selected_patient').hasClass('remove')) {
            showPatientInfo();}
            var searchoption = getOptionContent(type, value);
            $('.search_filter').append(searchoption);
            $('#search_practice_input').val('');
        }
    });

    $('.search_filter').on('click', '.remove_option', function () {
        $(this).parent().remove();

    });
    $('.schedule_button').on('click', function () {
        console.log($(this).attr('data-id'), $(this).attr('data-practice-id'));

        scheduleAppointment($(this).attr('data-id'), $(this).attr('data-practice-id'));
    });

});

$(document).ready(function () {

    $('.provider_near_patient').on('click', function () {
        $('.provider_near_patient_list').toggleClass("active");
        if ($('.provider_near_patient_list').hasClass("active"))
            showPreviousProvider();
        else {
            $('.provider_near').removeClass('glyphicon-chevron-down');
            $('.provider_near').addClass('glyphicon-chevron-right');
        }
    });

    $('.previous_provider_patient').on('click', function () {
        $('.previous_provider_patient_list').toggleClass("active");
        if ($('.previous_provider_patient_list').hasClass("active"))
            showProviderNear();
        else {
            $('.provider_previous').removeClass('glyphicon-chevron-down');
            $('.provider_previous').addClass('glyphicon-chevron-right');
        }
    });
});

function changePatientInfo() {
    $('.change_selected_patient').text("");
    $('.change_selected_patient').removeClass('view');
    $('.change_selected_patient').addClass('remove');
    $('.button_type_1').addClass('active');
    if ($('.view_selected_patient').hasClass('remove')) {
        $('.view_selected_patient').addClass('view');
    }
    showPatientInfo();
}

function showPatientInfo() {
    if ($('.view_selected_patient').hasClass('view')) {
        $('.patient_info').addClass('active')
        $('.view_selected_patient').text("Hide");
        $('.view_selected_patient').removeClass('view');
        $('.view_selected_patient').addClass('remove');
    } else if ($('.view_selected_patient').hasClass('remove')) {
        $('.patient_info').removeClass('active')
        $('.view_selected_patient').text("View");
        $('.view_selected_patient').removeClass('remove');
        $('.view_selected_patient').addClass('view');
        $('.change_selected_patient').text("Change");
        $('.change_selected_patient').removeClass('remove');
        $('.change_selected_patient').addClass('view')
        $('.button_type_1').removeClass('active');
    }
}

//function that is used to fetch the information of the patient that is being scheduled
function getPatientInfo(formData) {

    $.ajax({
        url: '/patients/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
            fillPatientInfo(info);
        },
        error: function () {
            alert('Error getting patient information');
        },
        cache: false,
        processData: false
    });

}

//function that is used to fill the information about the patient
function fillPatientInfo(data) {

    $('#patient_name').text(data.firstname);
    $('#patient_email').text(data.email);
    var d = new Date (data.birthdate);
    var date = d.getFullYear()+'-'+d.getMonth()+'-'+d.getDate();
    $('#patient_dob').text(date);
    $('#patient_add1').text(data.addressline1 + ',');
    $('#patient_add2').text(data.addressline2 + ',');
    $('#patient_add3').text(data.city);
    $('#patient_phone').text(data.cellphone);
    $('#patient_ssn').text(data.lastfourssn);
    $('.selected_patient_name').text(data.firstname);

}



//function that displays the providers near patients
function showPreviousProvider() {
    var provider_list = new Array("1", "John Doe", "Becker Eye", "Gurgaon", "Eyes");
    var content = '<div class="col-xs-12 list_seperator" data-id="' + provider_list[0] + '"><div class="row"><div class="col-xs-12">' + provider_list[1] + '<br> ' + provider_list[2] + ' </div><div class="col-xs-6"> </div><div class="col-xs-6"> </div></div></div>';
    $('.provider_near_patient_list').html(content);
    $('.provider_near').removeClass('glyphicon-chevron-right');
    $('.provider_near').addClass('glyphicon-chevron-down');
}

//function that displays the previous providers of the patients
function showProviderNear() {
    var provider_list = new Array("1", "John Doe", "Becker Eye", "Gurgaon", "Eyes");
    var content = '<div class="col-xs-12 list_seperator" data-id="' + provider_list[0] + '"><div class="row"><div class="col-xs-12">' + provider_list[1] + '<br> ' + provider_list[2] + ' </div><div class="col-xs-6"></div><div class="col-xs-6"> </div></div></div>';
    $('.previous_provider_patient_list').html(content);
    $('.provider_previous').removeClass('glyphicon-chevron-right');
    $('.provider_previous').addClass('glyphicon-chevron-down');
}

function showProviderInfo(data) {

    $('#practice_name').text(data.practice_name);
    $('#provider_name').text(data.provider['name']);
    $('#zipcode').text(data.provider['zip']);
    $('#phone').text(data.provider['cellphone']);
    $('.schedule_button').attr('data-id', data.provider['id']);
    $('.schedule_button').attr('data-practice-id', data.practice_id);
    var locations = data.locations;
    var content = '';

    if (locations.length > 0) {
        locations.forEach(function (location) {
            content += '<li><p>' + location.addressline1 + ',' + location.addressline1 + ' ' + location.city + ' ' + location.phone + '</p></li>';
        });
    }

    $('.locations').html(content);
    $('.practice_list').removeClass('active');
    $('.practice_info').addClass('active');
    $('.patient_previous_information').removeClass('active');

}

function getProviderInfo(formData) {

    $.ajax({
        url: '/providers/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
            showProviderInfo(info);
        },
        error: function () {
            alert('Error getting practice information');
        },
        cache: false,
        processData: false
    });

}

function getProviders(formData) {
    $('.practice_list').addClass('active');
    $('.practice_info').removeClass('active');
    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/providers/search',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var practices = $.parseJSON(e);
            console.log(practices);
            var content = '<p><bold>' + practices.length + '<bold> results found</p><br>';
            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    content += '<div class="col-xs-12 practice_list_item" data-id="' + practice.provider_id + '"  practice-id="' + practice.practice_id + '" ><div class="row content-row-margin"><div class="col-xs-6">' + practice.provider_name + ' <br> ' + practice.practice_name + ' </div><div class="col-xs-6">' + '' + '<br> ' + '' + ' </div></div></div>';
                });
            }
            $('.practice_list').html(content);
            $('.practice_list').addClass('active');
        },
        error: function () {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });

}

function getOptionContent(type, value) {
    var content = '<div class="search_filter_item"><span class="item_type">' + type + '</span>:<span class="item_value">' + value + '</span><span class="remove_option">x</span></div>';

    return content;
}

function getSearchType() {
    var searchdata = [];

    $('.search_filter_item').each(function () {
        var stype = $(this).children('.item_type').text();
        var name = $(this).children('.item_value').text();
        searchdata.push({
            "type": stype,
            "value": name,
        });
    });

    return searchdata;
}

function scheduleAppointment(providerId, practiceID) {
    $('#form_provider_id').val(providerId);
    $('#form_practice_id').val(practiceID);
    $('#form_select_provider').submit();
}
