$(document).ready(function () {

    var id = $('#form_patient_id').attr('value');
    var formData = {
        'id': id
    };
    getPatientInfo(formData);

    $('.carousel').carousel({
        interval: false
    });
    $('.availability').on('click', '#next_week', function () {
        var week = $('.availability').attr('data-value');
        $('.schedule_button').removeClass('active');
        week++;
        $('.availability').attr('data-value', week);
        getOpenSlots(week);
    });
    $('.availability').on('click', '#previous_week', function () {
        var week = $('.availability').attr('data-value');
        $('.schedule_button').removeClass('active');
        if ($('.availability').attr('data-value') > 0) {
            week--;
            $('.availability').attr('data-value', week);
            getOpenSlots(week);
        }
    });
    //    $('#appointment_date').datetimepicker({
    //        format: 'MM/DD/YYYY',
    //        minDate: new Date(),
    //        daysOfWeekDisabled: ['0']
    //    });

    $('.availability').on('click', 'li', function () {
        $('#appointment_date').text($(this).attr('data-date'));
        $('#appointment_time').text($(this).attr('data-time'));
        $('#form_appointment_time').val($(this).attr('data-time'));
        $('#form_appointment_date').val($(this).attr('data-date'));
        $('.availability-btn').addClass('hide');
        $('.schedule_button').addClass('active');
    });
    //    $('.availability').on('click', 'p.appointment_start_time', function () {
    //        $('#form_appointment_time').val($(this).text());
    //        //        $('#form_appointment_date').val($(this).closest('.weekday').find('p.date').text());
    //        $('#appointment_time').text($(this).text());
    //        $('.availability').removeClass('active');
    //        $('.schedule_button').addClass('active');
    //    });


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
        $('.search_filter').removeClass('hide');
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
        $('.search_filter').removeClass('hide');
        clearHTML();
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

    $('.locations').on('click', 'ul.dropdown-menu>li', function () {
        var location_address = $(this).attr('data-address_1') + ' ' + $(this).attr('data-address_2');
        var location_contact = $(this).attr('data-contact');
        $('#location_address').html('');
        $('#location_contact').html('');
        $('#appointment_type').html('');
        $('#appointment_date').html('');
        $('#appointment_time').html('');
        $('#appointment_type_list').html('');
        $('.availability-btn').removeClass('hide');
        $('p.get_availability').addClass('hide');
        $('.availability').html('');
        $('.availability').removeClass('active');
        $('.schedule_button').removeClass('active');
        $('#location_address').html(location_address);
        $('#location_contact').html(location_contact);
        getAppointmentTypes();
    });

    $('.appointment_type_list').on('click', 'ul.appointment_dropdown>li', function () {
        var appointment_type = $(this).attr('data-name');
        $('#appointment_type').html('');
        $('#appointment_date').html('');
        $('#appointment_time').html('');
        $('.availability-btn').removeClass('hide');
        $('.availability').html('');
        $('.availability').removeClass('active');
        $('.schedule_button').removeClass('active');
        $('#appointment_type').html(appointment_type);
        $('#appointment_type').attr('value', $(this).val());
        $('#appointment_type').attr('data-name', appointment_type);
        $('p.get_availability').removeClass('hide');
    });
    $('#get_availability').on('click', function () {
        $('.availability').attr('data-value', 0);
        getOpenSlots(0);
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

function clearHTML() {
    $('#provider_name').html('');
    $('#speciality').html('');
    $('#location_address').html('');
    $('#location').html('');
    $('#location_contact').html('');
    $('#appointment_type').html('');
    $('#appointment_type_list').html('');
    $('#appointment_date').html('');
    $('#appointment_time').html('');
    $('.availability').html('');
    $('.availability-btn').removeClass('hide');
    $('.availability').removeClass('active');
    $('p.get_availability').addClass('hide');
}

function showPatientInfo() {
    if ($('.view_selected_patient').hasClass('view')) {
        $('.patient_info').addClass('active')
        $('.view_selected_patient').text("Hide");
        $('.view_selected_patient').removeClass('view');
        $('.view_selected_patient').addClass('remove');
        $('.practice_info').addClass('hide');
        $('.practice_list').addClass('hide');
        if (!($('.practice_info').hasClass('active'))) {
            $('.search_filter').addClass('hide');
        }
        $('.availability').addClass('hide');
        $('.patient_previous_information').addClass('hide');
    } else if ($('.view_selected_patient').hasClass('remove')) {
        $('.patient_info').removeClass('active')
        $('.view_selected_patient').text("View");
        $('.view_selected_patient').removeClass('remove');
        $('.view_selected_patient').addClass('view');
        $('.practice_info').removeClass('hide');
        $('.practice_list').removeClass('hide');
        $('.availability').removeClass('hide');
        if (!($('.practice_info').hasClass('active'))) {
            $('.search_filter').removeClass('hide');
        }
        $('.patient_previous_information').removeClass('hide');
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
    $('#speciality').text(data.provider['speciality']);
    $('.schedule_button').attr('data-id', data.provider['id']);
    $('.schedule_button').attr('data-practice-id', data.practice_id);
    var locations = data.locations;
    var content = '';
    content += '<div class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="bold">Select Location <b class="caret"></b></span></a><ul class="dropdown-menu location_dropdown" id="custom_dropdown">';
    if (locations.length > 0) {
        locations.forEach(function (location) {
            content += '<li data-address_1="' + location.addressline1 + '" data-address_2="' + location.addressline2 + '" data-contact="' + location.phone + '">' + location.locationname + '</li>';
        });
    }
    content += '</ul></div>';
    $('.locations').html(content);
    $('.practice_list').removeClass('active');
    $('.practice_info').addClass('active');
    $('.patient_previous_information').removeClass('active');
    $('.search_filter').addClass('hide');
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

function getOpenSlots(week) {

    $('.ajax.appointment_schedule').addClass('active');
    var provider_id = 0;
    var location_id = 0;
    var appointment_type = $('#appointment_type').attr('value');
    var week = week;
    var formData = {
        'provider_id': provider_id,
        'location_id': location_id,
        'appointment_type': appointment_type,
        'week': week,
    };
    $('#form_appointment_type_name').val($('#appointment_type').attr('data-name'));
    $('#form_appointment_type_id').val($('#appointment_type').attr('value'));
    var content = '';
    var weekday = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    content += '<span class="glyphicon glyphicon-chevron-left availability_glyph" id="previous_week"></span>';
    var i = 0;
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
                var k = 0;
                content += '<div class="weekday"><p class="date">' + elem.date + '<br>' + weekday[i] + '</p>';


                var slot = elem.slots['ApptSlots'];
                if (slot) {
                    content += '<div id="' + weekday[i] + '" class="carousel mycarousel"><a class="" href="#' + weekday[i] + '" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-up time_glyph" aria-hidden="true"></span></a><ul class="carousel-inner time_selector" role="listbox">';
                    slot.forEach(function (time) {
                        if (k == 0) {
                            content += '<li class="item active" data-time="' + time.ApptStartTime + '" data-date="' + elem.date + '">' + time.ApptStartTime + '</li>';
                            k++;
                        } else
                            content += '<li class="item" data-time="' + time.ApptStartTime + '" data-date="' + elem.date + '">' + time.ApptStartTime + '</li>';
                    });
                    content += '</ul><a class="" href="#' + weekday[i] + '" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-down time_glyph" aria-hidden="true"></span></a></div>';
                } else {
                    content += '<p>N/A</p>';
                }

                content += '</div>';
                i += 1;
            });
            content += '<span class="glyphicon glyphicon-chevron-right availability_glyph available" id="next_week"></span>';
            $('.availability').html(content);
            if ($('.availability').attr('data-value') != 0) {
                $('#previous_week').addClass('available');
            }
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
    $('#form_location').val($('#location_address').text());
    var formData = {
        'provider_id': provider_id,
        'location_id': location_id,
    };
    var content = '';
    $.ajax({
        url: '/providers/appointmenttypes',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: true,
        success: function (e) {
            e = $.parseJSON(e);
            var apptTypes = e.GetApptTypesResult.ApptType;
            content += '<div class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="bold">Select Appointment Type <b class="caret"></b></span></a><ul class="dropdown-menu appointment_dropdown" id="custom_dropdown">';
            apptTypes.forEach(function (elem) {
                content += '<li  value="' + elem.ApptTypeKey + '" data-name=" ' + elem.ApptTypeName + ' ">' + elem.ApptTypeName + '</li>';
                //                $('#appointment-type').append('<option value="' + elem.ApptTypeKey + '">' + elem.ApptTypeName + '</option>');
            });
            $('#appointment-type').removeClass('hidden');
            $('.ajax.appointment_type').removeClass('active');
            content += '</ul></div>';
            $('.appointment_type_list').html(content);
        },
        error: function () {
            $('.ajax.appointment_type').removeClass('active');
        },
        cache: false,
        processData: false
    });
}
