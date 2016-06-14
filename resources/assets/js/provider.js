$(document).ready(function () {

    var insurancePrompt = 0;
    var old_ins_type = '';
    var new_ins_type = '';
    var ins_value = '';
    $('.dropdown-menu li').click(function () {
        $('#search_practice_input_type').text($(this).text());
        $('#search_practice_input_type').attr('value', $(this).attr('value'));
    });
    $('#select_date').datetimepicker({
        defaultDate: false,
        format: 'MM/DD/YYYY',
        minDate: new Date(),
    });
    $('#subscriber_dob').datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });
    $(document).on('click', '.dropdown_ins_list>li', function () {
        old_ins_type = $('#ins_selected').html();
        new_ins_type = $(this).attr('data-name');
        $('#ins_selected').html(old_ins_type);
    });
    $('#select_date').datetimepicker().on('dp.hide', function (ev) {
        var selected_date = $('#select_date').val();
        if (selected_date) {
            $('.availability').attr('data-value', 0);
            $('#appointment_date').html('');
            $('#appointment_time').html('');
            $('.appointment_detail').addClass('hide');
            $('.availability-text').removeClass('hide');
            $('.schedule_button').removeClass('active');
            $('.availability').html('');
            getOpenSlots(0);
            $('#availabilityModal').modal('show');
        }
    });
    if (!$('#from_admin').val()) {
        var id = $('#form_patient_id').attr('value');
        var formData = {
            'id': id
        };
        getPatientInfo(formData);
    }
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
        week--;
        $('.availability').attr('data-value', week);
        getOpenSlots(week);
    });

    $('.availability').on('click', 'li', function () {
        $('#appointment_date').text($(this).attr('data-date'));
        $('#appointment_time').text($(this).attr('data-time'));
        $('#form_appointment_time').val($(this).attr('data-time'));
        $('.appointment_detail').removeClass('hide');
        $('#form_appointment_date').val($(this).attr('data-date'));
        $('.availability').removeClass('active');
        $('.availability').html('');
        $('#availabilityModal').modal('hide');
        $('.availability-text').addClass('hide');
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
        $('#form_patient_id').attr('name', 'null');
        $('#form_select_provider').submit();
    });
    $('#search_practice_button').on('click', function () {
        $('.schedule_button').removeClass('active');
        $('.practice_list').removeClass('active');
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
        $('.appointment_type_not_found').hide();
        $('.schedule_button').attr('data-id', 0);
        $('.schedule_button').attr('data-practice-id', 0);
        $('.availability').removeClass('active');
        $('.patient_previous_information').addClass('active');
        $('.search_filter').removeClass('hide');
        clearHTML();
    });

    $('#add_practice_search_option').on('click', function () {
        var stype = $('#search_practice_input_type').attr('value');
        var type = $('#search_practice_input_type').text();
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
        $("#search_practice_button").trigger("click");

    });


    $('.schedule_button').on('click', function() {
        if (insurancePrompt == 0 && $('#insurance_carrier_key').val() == '') {
            $('#insuranceModal').modal('show');
            insurancePrompt++;
            return;
        }
        scheduleAppointment($(this).attr('data-id'), $(this).attr('data-practice-id'));
    });

    $('.location_list').on('click', 'ul>li', function () {
        var location_address = $(this).attr('data-address_1') + ' ' + $(this).attr('data-address_2');
        var location_contact = $(this).attr('data-contact');
        resetLocationData();
        $('#location_address').html(location_address);
        $('#location_contact').html(location_contact);
        $('#form_location_id').val($(this).attr('data-id'));
        $('#form_location_code').val($(this).attr('data-code'));
        getAppointmentTypes();
        getInsuranceList();
    });

    $('.appointment_type_list').on('click', 'ul.appointment_dropdown>li', function () {
        var appointment_type = $(this).attr('data-name');
        $('#appointment_type').html('');
        $('#appointment_date').html('');
        $('#appointment_time').html('');
        $('.appointment_type_not_found').hide();
        $('.appointment_detail').addClass('hide');
        $('.availability-btn').removeClass('hide');
        $('.availability').html('');
        $('.availability').removeClass('active');
        $('.availability-text').removeClass('hide');
        $('.schedule_button').removeClass('active');
        $('#appointment_type').html(appointment_type);
        $('#appointment_type').attr('value', $(this).val());
        $('#appointment_type').attr('data-name', appointment_type);
        $('.get_availability').removeClass('hide');
    });

    $('.provider_near_patient').on('click', function () {
        $('.provider_near_patient_list').toggleClass("active");
        if ($('.provider_near_patient_list').hasClass("active")) {
            var id = $('#form_patient_id').attr('value');
            var formData = {
                'patient_id': id
            };
            getNearByProviders(formData);
        } else {
            $('.provider_near').removeClass('glyphicon-chevron-down');
            $('.provider_near').addClass('glyphicon-chevron-right');
        }
    });

    $('.previous_provider_patient').on('click', function () {
        $('.previous_provider_patient_list').toggleClass("active");
        if ($('.previous_provider_patient_list').hasClass("active")) {
            var id = $('#form_patient_id').attr('value');
			selectPreviousProvider = false;
            var formData = {
                'patient_id': id
            };
            getPreviousProviders(formData);
        } else {
            $('.provider_previous').removeClass('glyphicon-chevron-down');
            $('.provider_previous').addClass('glyphicon-chevron-right');
        }
    });

    $(document).on('click', '.dropdown_ins_list>li', function () {
        $('#insurance_carrier_key').val($(this).val());
        $('#insurance_carrier').val($(this).html());
        if ($(this).val() == '1') {
            new_ins_type = $(this).attr('data-name');
            $('#ins_selected').html(new_ins_type);
        } else {
            $('#insuranceModal').modal('show');
        }
    });

    $('.previous_provider_patient_list').on('click', '.previous_provider_item', function () {
        var provider_id = $(this).attr('data-id');
        var practice_id = $(this).attr('data-practiceid');
        var formData = {
            'provider_id': provider_id,
            'practice_id': practice_id
        };

        getProviderInfo(formData);

    });

    $('.provider_near_patient_list').on('click', '.nearby_provider_item', function () {
        var provider_id = $(this).attr('data-id');
        var practice_id = $(this).attr('data-practiceid');
        var formData = {
            'provider_id': provider_id,
            'practice_id': practice_id
        };
        getProviderInfo(formData);
    });

    $(document).keypress(function (e) {
        if (e.which == 13) {
            $("#search_practice_button").trigger("click");
        }
    });

    $('.patient_table_header').on('click', function () {
        if ($(this).next('.patient_table_content').hasClass('active')) {
            $(this).next('.patient_table_content').removeClass('active');
            $(this).find('.glyphicon').removeClass('glyphicon-chevron-up');
            $(this).find('.glyphicon').addClass('glyphicon-chevron-down');
        } else {
            $('.patient_table_header>.glyphicon').removeClass('glyphicon-chevron-up');
            $('.patient_table_header>.glyphicon').addClass('glyphicon-chevron-down');
            $('.patient_table_content').removeClass('active');
            $(this).next('.patient_table_content').addClass('active');
            $(this).find('.glyphicon').removeClass('glyphicon-chevron-down');
            $(this).find('.glyphicon').addClass('glyphicon-chevron-up');
        }
    });

    $('.confirm_ins_btn').on('click', function () {
        $('#ins_selected').html(new_ins_type);
    });

	if($('#select_previous').val())
		{
			var id = $('#form_patient_id').attr('value');
			selectPreviousProvider = true;
			var formData = {
				'patient_id': id
			};
			getPreviousProviders(formData);
		}

	$(document).on('click', '.lastseen_content',function(){
		var id = $('#form_patient_id').attr('value');
		selectPreviousProvider = true;
		$('.view_selected_patient').trigger('click');
		var formData = {
			'patient_id': id
		};
		getPreviousProviders(formData);

	});

});

var selectPreviousProvider = false;
var unfillfpcFields = null;

var nearByProviders = [];

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
    $('.appointment_detail').addClass('hide');
    $('.availability').html('');
    $('.availability-text').removeClass('hide');
    $('.availability-btn').removeClass('hide');
    $('.availability').removeClass('active');
    $('.get_availability').addClass('hide');
    $('.appointment_type_not_found').hide();
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
        $('.patient_table_content').removeClass('active');
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

function resetLocationData() {
    $('#location_address').html('');
    $('#location_contact').html('');
    $('#appointment_type').html('');
    $('#appointment_date').html('');
    $('#appointment_time').html('');
    $('.appointment_type_not_found').hide();
    $('.appointment_detail').addClass('hide');
    $('#appointment_type_list').html('');
    $('.availability-btn').removeClass('hide');
    $('.get_availability').addClass('hide');
    $('.availability').html('');
    $('.availability').removeClass('active');
    $('.schedule_button').removeClass('active');
    $('.availability-text').removeClass('hide');
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
            if (info.result === true) {
                fillPatientInfo(info.patient_data);
            }
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
    $('.selected_patient_name').text(data.lastname + ', ' + data.firstname);
    $('.provider_section').show();
    fillPatientData(data);
}


//function that displays the previous providers of the patients
function showPreviousProvider(providers) {
    //var provider_list = new Array("1", "John Doe", "Becker Eye", "4885 Olde Towne Parkway", "Eyes");

    var content = '';
    if (providers.length > 0) {
        providers.forEach(function (provider) {
            content += '<div class="col-xs-12 list_seperator previous_provider_item" data-id="' + provider.id + '" data-practiceid="' + provider.practice_id + '"><div class="row"><div class="col-xs-12 arial_bold">' + provider.practice_name + '</div><div class="col-xs-6 arial">' + provider.name + '<br>' + provider.speciality + '</div><div class="col-xs-6 arial">' + provider.location_address + '</div></div></div>';
        });
        $('.previous_provider_patient_list').html(content);
    } else {
        $('.previous_provider_patient_list').html('No previous providers found');
    }

    $('.provider_previous').removeClass('glyphicon-chevron-right');
    $('.provider_previous').addClass('glyphicon-chevron-down');
}

//function that displays the providers near patients
function showProviderNear(providers) {
    var content = '';
    if (providers.length > 0) {
        providers.forEach(function (provider) {
            content += '<div class="col-xs-12 list_seperator nearby_provider_item" data-id="' + provider.id + '" data-practiceid="' + provider.practice_id + '"><div class="row"><div class="col-xs-5 "><span class="arial_bold">' + provider.name + '</span><br><span class="arial">' + provider.practice_name + '<br>' + provider.speciality + '</span></div><div class="col-xs-4 arial">' + provider.location_address + '</div><div class="col-xs-3 arial"><span style ="color:black;">Distance - ' + provider.distance + '</span></div></div></div>';
        });
        $('.provider_near_patient_list').html(content);
    } else {
        $('.provider_near_patient_list').html('No provider found within 50 Miles');
    }
    $('.provider_near').removeClass('glyphicon-chevron-right');
    $('.provider_near').addClass('glyphicon-chevron-down');
}

function showProviderInfo(data) {

    $('#practice_name').text(data.practice_name);
    $('#provider_name').text(data.provider['name']);
    $('#form_provider_acc_key').val(data.provider['acc_key']);
    $('#zipcode').text(data.provider['zip']);
    $('#speciality').text(data.provider['speciality']);
    $('.schedule_button').attr('data-id', data.provider['id']);
    $('.schedule_button').attr('data-practice-id', data.practice_id);
    var locations = data.locations;
    var content = '';
    content += '<div class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="bold arial_bold custom_dropdown">Select Location <img src="/images/dropdown-img.png" class="custom_dropdown_img"></span></a><ul class="dropdown-menu location_dropdown" id="custom_dropdown">';
    if (locations.length > 0) {
        locations.forEach(function (location) {
            content += '<li data-id="' + location.id + '" data-code="' + location.location_code + '" data-address_1="' + location.addressline1 + '" data-address_2="' + location.addressline2 + '" data-contact="' + location.phone + '">' + location.locationname + '</li>';
        });
    }
    content += '</ul></div>';
    $('.locations').html(content);
    if (locations.length < $('#provider_list_count_limit').val()) {
        content = '';
        content += '<ul class="location_list">';
        if (locations.length > 0) {
            locations.forEach(function (location) {
                content += '<li data-id="' + location.id + '" data-code="' + location.location_code + '" data-address_1="' + location.addressline1 + '" data-address_2="' + location.addressline2 + '" data-contact="' + location.phone + '">' + location.locationname + '</li>';
            });
        }
        content += '</ul>';
        $('#location_address').html(content);
    }
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
            $('#ins_list').hide();
            if (info.locations.length == 1)
                $('ul.location_dropdown>li').first().click();
        },
        error: function () {
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getInsuranceList() {
    var provider_id = $('#form_provider_acc_key').val();
    var location_id = $('#form_location_code').val();
    var formData = {
        'provider_id': provider_id,
        'location_id': location_id,
    };
    $('#ins_list').html('');
    $.ajax({
        url: '/providers/insurancelist',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            e = $.parseJSON(e);
            var content = '';
            content += '<div class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="bold arial_bold custom_dropdown">Select Insurance List <img src="/images/dropdown-img.png" class="custom_dropdown_img"></span></a><ul class="dropdown-menu dropdown_ins_list" id="custom_dropdown">';
            if (e.GetInsListResult.InsItem.length > 0) {
                var insList = e.GetInsListResult.InsItem;
                insList.forEach(function (elem) {
                    content += '<li  value="' + elem.InsKey + '" data-name=" ' + elem.InsName + ' ">' + elem.InsName + '</li>';
                });
            }
            content += '</ul></div>';
            $('#ins_list').html(content);
            $('#ins_list').show();

        },
        error: function () {
            $('p.alert_message').text('Error getting Accepted Insurance List');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getProviders(formData) {
    $('.practice_list').addClass('active');
    $('.practice_info').removeClass('active');
    $('.appointment_type_not_found').hide();
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
            var content = '<p>' + practices.length + ' results found</p>';
            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    content += '<div class="col-xs-12 practice_list_item" data-id="' + practice.provider_id + '"  practice-id="' + practice.practice_id + '" ><div class="row content-row-margin"><div class="col-xs-12 arial_bold provider_list_title">' + practice.provider_name + ' </div><div class="col-xs-6 provider_list_detail"> ' + practice.practice_name + ' </div><div class="col-xs-6 provider_list_detail">' + practice.practice_speciality + '' + '<br> ' + '' + ' </div></div></div>';
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
    var provider_id = $('#form_provider_acc_key').val();
    var location_id = $('#form_location_code').val();
    var appointment_type = $('#appointment_type').attr('value');
    var selected_date = $('#select_date').val();
    var week = week;
    var formData = {
        'provider_id': provider_id,
        'location_id': location_id,
        'appointment_type': appointment_type,
        'week': week,
        'selected_date': selected_date,
    };
    $('#form_appointment_type_name').val($('#appointment_type').attr('data-name'));
    $('#form_appointment_type_id').val($('#appointment_type').attr('value'));
    $('.availability').addClass('active');
    var content = '';
    content += '<span class="slot_header" style="margin:1em;"><span class="slot_header_text">Retrieving Available Appointments</span></span>';
    $('.availability').html(content);
    content = '';
    var weekday = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    content += '<span class="slot_header"><span class="glyphicon glyphicon-chevron-left availability_glyph available" id="previous_week" data-toggle="tooltip" title="Previous week slots" data-placement="right"></span><span class="glyphicon glyphicon-chevron-right availability_glyph available" id="next_week" data-toggle="tooltip" title="Next week slots" data-placement="right"></span><span class="slot_header_text">Please pick an appointment time</span><span class="glyphicon glyphicon-remove availability_glyph available" data-dismiss="modal" data-toggle="tooltip" title="Close this window" data-placement="left"></span></span><span class="slot_body">';
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
            if (e.length === 0)
                return;
            e.forEach(function (elem) {
                var k = 0;
                content += '<div class="weekday"><p class="date">' + elem.date + '<br>' + weekday[i] + '</p>';
                var slot = elem.slots['ApptSlots'];
                if (slot) {
                    //                    content += '<div id="' + weekday[i] + '" class="carousel mycarousel"><a class="" href="#' + weekday[i] + '" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-up time_glyph" aria-hidden="true"></span></a><ul class="carousel-inner time_selector" role="listbox">';
                    //                    slot.forEach(function (time) {
                    //                        if (k == 0) {
                    //                            content += '<li class="item active" data-time="' + time.ApptStartTime + '" data-date="' + elem.date + '">' + time.ApptStartTime + '</li>';
                    //                            k++;
                    //                        } else
                    //                            content += '<li class="item" data-time="' + time.ApptStartTime + '" data-date="' + elem.date + '">' + time.ApptStartTime + '</li>';
                    //                    });
                    //                    content += '</ul><a class="" href="#' + weekday[i] + '" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-down time_glyph" aria-hidden="true"></span></a></div>';
                    content += '<div id="' + weekday[i] + '"><ul class="time_selector">';
                    if (slot.length) {
                        slot.forEach(function (time) {
                            content += '<li class="item" data-time="' + time.ApptStartTime + '" data-date="' + elem.date + '">' + time.ApptStartTime + '</li>';
                        });
                    } else {
                        content += '<li class="item" data-time="' + slot.ApptStartTime + '" data-date="' + elem.date + '">' + slot.ApptStartTime + '</li>';
                    }
                    content += '</ul></div>';

                } else {
                    content += '<p>N/A</p>';
                }
                content += '</div>';
                i += 1;
            });
            content += '</span>';
            $('.availability').html(content);
            $('[data-toggle="tooltip"]').tooltip();
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
    $('#select_date').val('');
    $('.ajax.appointment_type').addClass('active');
    var provider_id = $('#form_provider_acc_key').val();
    var location_id = $('#form_location_code').val();
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
            if (apptTypes) {
                content += '<div class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><span class="bold arial_bold custom_dropdown">Select Appointment Type <img src="/images/dropdown-img.png" class="custom_dropdown_img"></span></a><ul class="dropdown-menu appointment_dropdown" id="custom_dropdown">';
                apptTypes.forEach(function (elem) {
                    content += '<li  value="' + elem.ApptTypeKey + '" data-name=" ' + elem.ApptTypeName + ' ">' + elem.ApptTypeName + '</li>';
                });
                $('#appointment-type').removeClass('hidden');
                $('.appointment_type_not_found').hide();
                content += '</ul></div>';
                $('.appointment_type_list').html(content);
            } else {
                $('.appointment_type_not_found').show();
                // $('p.alert_message').text('No data received');
                // $('#alert').modal('show');
            }
            $('.ajax.appointment_type').removeClass('active');
        },
        error: function () {
            $('.ajax.appointment_type').removeClass('active');
        },
        cache: false,
        processData: false
    });
}

function getPreviousProviders(formData) {
    $.ajax({
        url: '/providers/previous',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
			if(selectPreviousProvider){
				var formData = {
					'provider_id': info[0]['id'],
					'practice_id': info[0]['practice_id']
				};
				selectPreviousProvider = false;
				getProviderInfo(formData);

				return;
			}
            showPreviousProvider(info);
        },
        error: function () {
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getNearByProviders(formData) {
    if (nearByProviders.length > 0) {
        showProviderNear(nearByProviders);
        return;
    }
    $.ajax({
        url: '/providers/nearby',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
            nearByProviders = info;
            showProviderNear(info)
        },
        error: function () {
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}


