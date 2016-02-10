$(document).ready(function () {

    var id = $('#form_patient_id').attr('value');
    var formData = {
        'id': id
    };
    getPatientInfo(formData);

    $('#appointment_date').datetimepicker(
        {format: 'MM/DD/YYYY',
         minDate: new Date(),
         daysOfWeekDisabled: ['0']
        }
    );

    $('.availability').on('click', 'p.appointment_start_time', function () {
        $('#form_appointment_time').val($(this).text());
        $('#form_appointment_date').val($(this).closest('.weekday').find('p.date').text());
        $('#appointment_time').text($(this).text());
        $('.availability').removeClass('active');
        $('.schedule_button').addClass('active');
    });


    $('.view_selected_patient').on('click', showPatientInfo);

    $('#change_patient_button').on('click', function () {
        $('#form_select_provider').attr('action', "/patients");
        $('#form_provider_id').prop('disabled', true);
        $('#form_practice_id').prop('disabled', true);
        $('#form_appointment_type_id').prop('disabled', true);
        $('#form_appointment_type_name').prop('disabled', true);
        $('#form_appointment_time').prop('disabled', true);
        $('#form_appointment_date').prop('disabled', true);
        $('#form_location').prop('disabled', true);
        $('#form_select_provider').submit();
    });
    $('#search_practice_button').on('click', function () {
        $('.schedule_button').removeClass('active');
        $('.schedule_button').attr('data-id', 0);
        $('.schedule_button').attr('data-practice-id', 0);
        $('.availability').removeClass('active');
        $("#add_practice_search_option").trigger("click");
        $('#search_practice_input').val('');
        var searchdata = getSearchType();
        if ($('.view_selected_patient').hasClass('remove')) {
            showPatientInfo();
        }
        if (searchdata.length != 0)
            getProviders(searchdata);
        else
            $('#search_practice_input').focus();
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
        $('.schedule_button').removeClass('active');
        $('.schedule_button').attr('data-id', 0);
        $('.schedule_button').attr('data-practice-id', 0);
        $('.availability').removeClass('active');
        $('.patient_previous_information').addClass('active');

    });

    $('#add_practice_search_option').on('click', function () {
        var stype = $('#search_practice_input_type').val();
        var type = $('#search_practice_input_type').find(":selected").text();
        var value = $('#search_practice_input').val();
        if (value != '') {
            if ($('.view_selected_patient').hasClass('remove')) {
                showPatientInfo();
            }
            var searchoption = getOptionContent(type, value, stype);
            $('.search_filter').append(searchoption);
            $('#search_practice_input').val('');
        }
    });

    $('.search_filter').on('click', '.remove_option', function () {
        $(this).parent().remove();

    });
    $('.schedule_button').on('click', function () {
        scheduleAppointment($(this).attr('data-id'), $(this).attr('data-practice-id'));
    });

    $('#location').on('change', function () {
        $('#get_availability').attr('disabled','disabled');
        $('#form_location').val($('#location').val());
        $('#location_address').text($('#location').val());
        $('.schedule_button').removeClass('active');
        getAppointmentTypes();
    });

    $('#appointment-type').on('change', function () {
        $('#get_availability').removeAttr('disabled');
        $('.schedule_button').removeClass('active');
    });

    $('#get_availability').on('click', function(){
        getOpenSlots();
    });

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

    $(document).keypress(function (e) {
        if (e.which == 13) {
            $("#search_practice_button").trigger("click");
        }
    });


});

function showPatientInfo() {
    if ($('.view_selected_patient').hasClass('view')) {
        $('.patient_info').addClass('active')
        $('.view_selected_patient').text("Hide");
        $('.view_selected_patient').removeClass('view');
        $('.view_selected_patient').addClass('remove');
        $('.patient_previous_information').removeClass('active');
    } else if ($('.view_selected_patient').hasClass('remove')) {
        $('.patient_info').removeClass('active')
        $('.view_selected_patient').text("View");
        $('.view_selected_patient').removeClass('remove');
        $('.view_selected_patient').addClass('view');
        if(!$('.practice_info').hasClass('active'))
        $('.patient_previous_information').addClass('active');
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
            $('p.alert_message').text('Error getting patient information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

//function that is used to fill the information about the patient
function fillPatientInfo(data) {

    $('#patient_name').text(data.firstname);
    $('#patient_email').text(data.email);
    $('#patient_dob').text(data.birthdate);
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
    $('#speciality').text(data.provider['speciality']);
    $('.schedule_button').attr('data-id', data.provider['id']);
    $('.schedule_button').attr('data-practice-id', data.practice_id);
    var locations = data.locations;
    var content = '';

    if (locations.length > 0) {
        $('#location').html('<option value="0">Select Location</option>');
        locations.forEach(function (location) {
            $('#location').append('<option value="' + location.addressline1 + ' ' + location.addressline2    + '">' +  location.locationname + '</option>');
//            content += '<div class="practice_location"><span>' + location.addressline1 + ',' + location.addressline1 + ' ' + location.city + ' ' + location.phone + '</span></div>';
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
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
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
            var content = '<p><bold>' + practices.length + '<bold> results found</p><br>';
            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    content += '<div class="col-xs-12 practice_list_item" data-id="' + practice.provider_id + '"  practice-id="' + practice.practice_id + '" ><div class="row content-row-margin"><div class="col-xs-6">' + practice.provider_name + ' <br> ' + practice.practice_name + ' </div><div class="col-xs-6">' + practice.practice_speciality + '' + '<br> ' + '' + ' </div></div></div>';
                });
            }
            $('.practice_list').html(content);
            $('.practice_list').addClass('active');
        },
        error: function () {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getOptionContent(type, value, stype) {
    var content = '<div class="search_filter_item"><span class="item_type" data-stype="' + stype + '">' + type + '</span>:<span class="item_value">' + value + '</span><span class="remove_option">x</span></div>';

    return content;
}

function getSearchType() {
    var searchdata = [];

    $('.search_filter_item').each(function () {
        var stype = $(this).children('.item_type').attr('data-stype');
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

function getOpenSlots() {

    $('.ajax.appointment_schedule').addClass('active');
    var provider_id = 0;
    var location_id = 0;
    var appointment_type = $('#appointment-type').val();
    var appointment_date = $('#appointment_date').val();
    var formData = {
        'provider_id': provider_id,
        'location_id': location_id,
        'appointment_type': appointment_type,
        'appointment_date': appointment_date,
    };
    $('#form_appointment_type_name').val($('#appointment-type :selected').text());
    $('#form_appointment_type_id').val($('#appointment-type').val());
    var content = '';
    $.ajax({
        url: '/providers/openslots',
        type: 'GET',
        async: true,
        data: $.param(formData),
        contentType: 'text/html',
//        async: false,
        success: function (e) {
            e = $.parseJSON(e);
            var apptSlots = e.GetOpenApptSlotsResult;
            e.forEach(function (elem) {
                content += '<div class="weekday"><p class="date">' + elem.date + '</p>';
                var slot = elem.slots['ApptSlots'];
                if (slot) {
                    slot.forEach(function (time) {
                        //                    content += '<div class="weekday"><p class="date">' + elem.date + '</p>';
                        content += '<p class="appointment_start_time">' + time.ApptStartTime + '</p>';
                    });
                }
                content += '</div>'
            });
            $('.availability').html(content);
            $('.availability').addClass('active');
            $('.ajax.appointment_schedule').removeClass('active');
        },
        error: function () {
            $('.ajax.appointment_schedule').removeClass('active');
        },
        cache: false,
        processData: false
    });
}

function getAppointmentTypes() {

    $('.ajax.appointment_type').addClass('active');
    var provider_id = 0;
    var location_id = 0;

    var formData = {
        'provider_id': provider_id,
        'location_id': location_id,
    };

    $.ajax({
        url: '/providers/appointmenttypes',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: true,
        success: function (e) {
            e = $.parseJSON(e);
            var apptTypes = e.GetApptTypesResult.ApptType;
            $('#appointment-type').html('<option value="0">Select Appointment Type</option>');
            apptTypes.forEach(function (elem) {
                $('#appointment-type').append('<option value="' + elem.ApptTypeKey + '">' +  elem.ApptTypeName + '</option>');
            });
            $('#appointment-type').removeClass('hidden');
            $('.ajax.appointment_type').removeClass('active');
        },
        error: function () {
            $('.ajax.appointment_type').removeClass('active');
        },
        cache: false,
        processData: false
    });
}
