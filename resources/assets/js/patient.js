$(document).ready(function() {

    if ($('#from_admin').val())
        loadAllPatients();

    $('#dob').datetimepicker({
        format: 'YYYY/MM/DD'
    });
    $('#search_patient_button').on('click', function() {

        if ($('#from_admin').val()) {
            var val = $('#search_patient_input').val();
            var searchdata = [];
            searchdata.push({
                "type": 'name',
                "value": val,
            });
            getPatients(searchdata, 0);
            $('#refresh_patients').addClass('active');
        } else {
            $("#add_search_option").trigger("click");
            $('#search_patient_input').val('');
            var searchdata = getsearchtype();
            if (searchdata.length != 0)
                getPatients(searchdata, 0);
            else {
                $('#search_patient_input').focus();
                $('.patient_list').removeClass('active');
                $('.search_filter').removeClass('active');
            }
        }
    });
    $('#add_patient_form').on('click', function() {
        $('.add_patient_form').addClass('active');
        $('.patient_admin_header').removeClass('active');
    });

    $('#back_to_select_patient_btn').on('click', function() {
        $('#back_to_select_patient').submit();
    });
    $('#add_patient_btn').on('click', function() {
        $('#form_patient_id').prop('disabled', true);
        $('#form_select_provider').attr('action', "/patients/create");
        $('#form_select_provider').submit();
    });
    $('.patient_list').on('click', '.patient_list_item', function() {
        var id = $(this).attr('data-id');
        var formData = {
            'id': id
        };
        getPatientInfo(formData);
    });
    $('#save_patient_info').on('click', function() {

    });
    $('#change_patient_button').on('click', function() {
        $('.search_filter').addClass('active');
        $('.patient_list').addClass('active');
        $('.patient_info').removeClass('active');
        $('.action-btns').addClass('active');
        $('#select_provider_button').removeClass('active');
        $('#select_provider_button').attr('data-id', 0);
        $('#import_patients').show();
    });
    $('#select_provider_button').on('click', function() {
        var id = $(this).attr('data-id');
        selectProvider(id);
    });
    $('#add_search_option').on('click', function() {
        var type = $('#search_patient_input_type').val();
        var value = $('#search_patient_input').val();
        if (value != '') {
            var searchoption = getOptionContent(type, value);
            $('.search_filter').append(searchoption);
            $('#search_patient_input').val('');
            $('#search_patient_input').focus();

        }
    });
    $('.search_filter').on('click', '.remove_option', function() {
        $(this).parent().remove();
        $("#search_patient_button").trigger("click");

    });
    $('.lastseenby_show').on('click', function() {
        $('.lastseen_content').toggleClass('active');
        if ($('.lastseen_content').hasClass('active')) {
            $('.lastseenby_icon').removeClass('glyphicon-chevron-right');
            $('.lastseenby_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.lastseenby_icon').removeClass('glyphicon-chevron-down');
            $('.lastseenby_icon').addClass('glyphicon-chevron-right');
        }
    });
    $('.referredby_show').on('click', function() {
        $('.referredby_content').toggleClass('active');
        if ($('.referredby_content').hasClass('active')) {
            $('.referredby_icon').removeClass('glyphicon-chevron-right');
            $('.referredby_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.referredby_icon').removeClass('glyphicon-chevron-down');
            $('.referredby_icon').addClass('glyphicon-chevron-right');
        }
    });
    $('.insurance_provider_show').on('click', function() {
        $('.insurance_provider_content').toggleClass('active');
        if ($('.insurance_provider_content').hasClass('active')) {
            $('.insurance_provider_icon').removeClass('glyphicon-chevron-right');
            $('.insurance_provider_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.insurance_provider_icon').removeClass('glyphicon-chevron-down');
            $('.insurance_provider_icon').addClass('glyphicon-chevron-right');
        }
    });
    $('#refresh_patients').on('click', function() {
        $('#search_patient_input').val('');
        loadAllPatients();
    });
    $('.p_left').on('click', function() {
        var searchdata = [];
        if (currentpage > 1)
            getPatients(searchdata, currentpage - 1);
    });
    $('.p_right').on('click', function() {
        var searchdata = [];
        if (currentpage < lastpage)
            getPatients(searchdata, currentpage + 1);
    });
    $('.patient_list').on('change', '#checked_all_patients', function() {
        if ($(this).is(":checked")) {
            $('.patient_search_content').each(function() {
                $(this).find('input').prop('checked', true);
            });
        } else
            $('.patient_search_content').each(function() {
                $(this).find('input').prop('checked', false);
            });
    });
    $('.patient_list').on('click', '.search_name', function() {
        var patient_id = $(this).parents('.search_item').attr('data-id');
        var formData = {
            'id': patient_id
        };
        getPatientInfo(formData);
    });
    $('#open_patient_form').on('click', function() {
        window.location = '/administration/patients/create';
    });

    $('#back_to_admin_patient_btn').on('click', function() {
        window.location = '/administration/patients';
    });

    $('.patient_list').on('click', '.editPatient_from_row', function() {
        var val = $(this).parents('.search_item').attr('data-id');

        window.location = '/administration/patients/edit/' + val + '';
    });
    $('#dontsave_new_patient').on('click', function() {
        if ($('#from_admin').val())
            $('#back_to_admin_patient_btn').trigger('click');
        else{
            $('#back_to_select_patient_btn').trigger('click');
        }

    });


    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#search_patient_button").trigger("click");
        }
    });
    loadImportForm();
});
var currentpage = 0;
var lastpage = 0;

function loadAllPatients() {
    var searchdata = [];
    searchdata.push({
        "type": 'all',
        "value": '',
    });
    getPatients(searchdata, 0);
    $('#refresh_patients').removeClass('active');
}

function showPatientInfo(data) {
    $('.search_filter').removeClass('active');
    $('.patient_list').removeClass('active');
    $('.patient_info').addClass('active');
    $('#patient_name').text(data.firstname);
    $('#patient_email').text(data.email);
    $('#patient_dob').text(data.birthdate);
    $('#patient_add1').text(data.addressline1 + ',');
    $('#patient_add2').text(data.addressline2 + ',');
    $('#patient_add3').text(data.city);
    $('#patient_phone').text(data.cellphone);
    $('#patient_ssn').text(data.lastfourssn);
    $('#select_provider_button').attr('data-id', data.id);
    $('#select_provider_button').addClass('active');
    $('.action-btns').removeClass('active');
    $('#import_patients').hide();
    $('#import_ccda_button').attr('data-id', data.id);
    $('#download_ccda').attr('data-href', '/download/' + data.id);
    $('#view_ccda').attr('data-href', '/show/ccda/' + data.id);
    $('.lastseen_content').html('<p class="patient_dropdown_data">' + data.referred_to_practice_user + '</p><p class="patient_dropdown_data">' + data.referred_to_practice + '</p>');
    $('.referredby_content').html('<p class="patient_dropdown_data">' + data.referred_by_provider + '</p><p class="patient_dropdown_data">' + data.referred_by_practice + '</p>');
    $('.insurance_provider_content').html('<p class="patient_dropdown_data">' + data.insurance + '</p>');
    if (data.referred_to_practice_user == '' && data.referred_to_practice == '')
        $('.lastseenby_icon').addClass('hide');
    else
        $('.lastseenby_icon').removeClass('hide');
    if (data.referred_by_provider == '' && data.referred_by_practice == '')
        $('.referredby_icon').addClass('hide');
    else
        $('.referredby_icon').removeClass('hide');
    if (data.insurance == '')
        $('.insurance_provider_icon').addClass('hide');
    else
        $('.insurance_provider_icon').removeClass('hide');
    if (data.ccda == 0)
        $('.ccda_present').addClass('hide');
    else
        $('.ccda_present').removeClass('hide');
}

function getPatientInfo(formData) {

    $.ajax({
        url: '/patients/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var info = $.parseJSON(e);
            showPatientInfo(info);
        },
        error: function() {
            $('p.alert_message').text('Error getting patient information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getPatients(formData, page) {
    $('.search_filter').addClass('active');
    $('.patient_info').removeClass('active');
    $('.action-btns').addClass('active');
    $('#select_provider_button').removeClass('active');
    $('#select_provider_button').attr('data-id', 0);
    $('#import_patients').show();
    var active_img = $('#schedule_patient_img').val();
    var delete_img = $('#delete_practice_img').val();

    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/patients/search?page=' + page,
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var patients = $.parseJSON(e);

            if ($('#from_admin').val()) {
                var content = '';
                if (patients.length > 0) {
                    patients.forEach(function(patient) {
                        content += '<div class="row search_item" data-id="' + patient.id + '"><div class="col-xs-3" style="display:inline-flex"><div><input type="checkbox">&nbsp;&nbsp;</div><div class="search_name"><p>' + patient.fname + ' ' + patient.lname + '</p></div></div><div class="col-xs-3">' + patient.addressline1 + '<br>' + patient.addressline2 + '</div><div class="col-xs-1"></div><div class="col-xs-3"><p>' + patient.email + '</p></div><div class="col-xs-2 search_edit"><p><div><span area-hidden="true" data-toggle="dropdown" class="dropdown-toggle"><img class="action_dropdown_img" src="' + active_img + '" alt=""></span></div></p>&nbsp;&nbsp;<p class="editPatient_from_row" data-toggle="modal" data-target="#create_practice">Edit</p><div class="dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepatient_from_row"><img src="' + delete_img + '" alt="" class="removepatient_img"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete this?</strong></p></li><li class="confirm_buttons"<button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button" class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                    });
                }
                currentpage = patients[0]['currentPage'];
                lastpage = patients[0]['lastpage'];
                var result = currentpage * 5;
                if (result > patients[0]['total'])
                    result = patients[0]['total'];
                $('.page_info').text(result + ' of ' + patients[0]['total']);
                $('.patient_search_content').html(content);
                if ($('#checked_all_patients').is(":checked")) {
                    $('.patient_search_content').each(function() {
                        $(this).find('input').prop('checked', true);
                    });
                } else
                    $('.patient_search_content').each(function() {
                        $(this).find('input').prop('checked', false);
                    });

            } else {
                var content = '<p><bold>' + patients.length + '<bold> results found</p><br>';
                if (patients.length > 0) {
                    patients.forEach(function(patient) {
                        content += '<div class="col-xs-12 patient_list_item" data-id="' + patient.id + '"><div class="row content-row-margin"><div class="col-xs-6">' + patient.fname + ' ' + patient.lname + '<br> ' + patient.birthdate + ' </div><div class="col-xs-6">' + patient.email + '<br> ' + patient.city + ' </div></div></div>';
                    });
                }
                $('.patient_list').html(content);
            }

            $('.patient_list').addClass('active');

        },
        error: function() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getsearchtype() {
    var searchdata = [];
    $('.search_filter_item').each(function() {
        var stype = $(this).children('.item_type').text();
        var name = $(this).children('.item_value').text();
        searchdata.push({
            "type": stype,
            "value": name,
        });
    });
    return searchdata;
}

function getOptionContent(type, value) {
    var content = '<div class="search_filter_item"><span class="item_type">' + type + '</span>:<span class="item_value">' + value + '</span><span class="remove_option">x</span></div>';
    return content;
}

function selectProvider(id) {

    $('#form_patient_id').val(id);
    $('#form_select_provider').submit();
}

function updatePatientData() {
    var myform = document.getElementById("compare_ccda_form");
    var fd = new FormData(myform);
    $.ajax({
        url: "update/ccda",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(dataofconfirm) {
            if (dataofconfirm != 'false') {
                $('.update_header').removeClass('active');
                $('.compare_form').removeClass('active');
                $('.success_message').text("You have successfully updated the data.");
                $('.success_message').addClass('active');
                $('.compare_ccda_button').removeClass('active');
                $('.dismiss_button').text('OK');
            }

            var formData = {
                'id': dataofconfirm
            };
            getPatientInfo(formData);
        }
    });
}
