$(document).ready(function() {


    $('#dob').datetimepicker({
        format: 'YYYY/MM/DD'
    });


    $('#search_patient_button').on('click', function () {
        $("#add_search_option").trigger("click");
        $('#search_patient_input').val('');
        var searchdata = getsearchtype();
        if (searchdata.length != 0)
            getPatients(searchdata);
        else
            $('#search_patient_input').focus();
    });

    $('#add_patient_form').on('click', function() {
        $('.add_patient_form').addClass('active');
        $('.patient_admin_header').removeClass('active');
    });

    $('#back_to_select_patient_btn').on('click', function() {
        $('#back_to_select_patient').submit();
    });

    $('#add_patient_btn').on('click', function () {
        $('#form_patient_id').prop('disabled', true);
        $('#form_select_provider').attr('action', "/administration/patients/create");
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
        }
    });
    $('.search_filter').on('click', '.remove_option', function() {
        $(this).parent().remove();
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

    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#search_patient_button").trigger("click");
        }
    });

});

function showPatientInfo(data) {
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
            alert('Error getting patient information');
        },
        cache: false,
        processData: false
    });

}

function getPatients(formData) {
    $('.patient_list').addClass('active');
    $('.patient_info').removeClass('active');
    $('.action-btns').addClass('active');
    $('#select_provider_button').removeClass('active');
    $('#select_provider_button').attr('data-id', 0);
    $('#import_patients').show();

    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/patients/search',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var patients = $.parseJSON(e);
            var content = '<p><bold>' + patients.length + '<bold> results found</p><br>';

            if (patients.length > 0) {
                patients.forEach(function(patient) {
                    content += '<div class="col-xs-12 patient_list_item" data-id="' + patient.id + '"><div class="row content-row-margin"><div class="col-xs-6">' + patient.fname + ' ' + patient.lname + '<br> ' + patient.birthdate + ' </div><div class="col-xs-6">' + patient.email + '<br> ' + patient.city + ' </div></div></div>';
                });
            }
            $('.patient_list').html(content);
            $('.patient_list').addClass('active');
        },
        error: function() {
            alert('Error searching');
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
