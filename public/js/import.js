'use strict';

$(document).ready(function () {
    $('#practice_list').on('change', function (e) {
        var practice_id = $(this).find(":selected").val();
        getLocation(practice_id);
    });
    $('.file-input input[type="file"]').change(function () {
        var filename = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        $('.filename').html(filename);
    });

    $('.import_button').on('click', function () {
        importPatients();
    });

    $('.open_import').on('click', function () {
        $('.import_form').addClass('active');
        $('.success_message').text('');
        $('.success_message').removeClass('active');
        $('.import_button').addClass('active');
        $('.dismiss_button').text('Cancel');
    });

    $('#import_ccda_button').on('click', function () {
        var id = $(this).attr('data-id');
        $('#ccda_patient_id').val(id);
        // $('.import_form').addClass('active');
        $('.success_message').text('');
        $('.success_message').removeClass('active');
        $('.save_ccda_button').addClass('active');
        $('.dismiss_button').text('Cancel');
    });
    $('.save_ccda_button').on('click', function () {
        saveCcdafile();
    });
    $('.compare_ccda_button').on('click', function () {
        updatePatientData();
    });
    $('#compare_ccda_button').on('click', function () {
        $('.compare_form').addClass('active');
        $('.success_message').text(" ");
        $('.success_message').removeClass('active');
        $('.compare_ccda_button').addClass('active');
        $('.dismiss_button').text('Cancel');
        $('.compare_row_item').each(function () {
            $(this).find('input').prop('checked', false);
        });
    });
    $('#checked_all').change(function () {
        if ($(this).is(":checked")) {
            $('.compare_row_item').each(function () {
                $(this).find('input').prop('checked', true);
            });
        } else $('.compare_row_item').each(function () {
            $(this).find('input').prop('checked', false);
        });
    });
    $('#download_ccda').on('click', function () {
        var url = $('#download_ccda').attr('data-href');
        getCcdaFile(url);
    });
    $('#view_ccda').on('click', function () {
        var url = $(this).attr('data-href');
        showCCDA(url);
    });
    $('.dismiss_button').on('click', function () {
        $('.view_patient_ccda').removeClass('active');
        $('.view_patient_ccda').html(' ');
    });
});

function getCcdaFile(url) {

    $.ajax({
        url: url,
        type: 'GET',
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            if (e != 'nofile') {
                window.location = url;
            } else {
                $('#compare_ccda_button').trigger('click');
                $('.update_header').removeClass('active');
                $('.compare_form').removeClass('active');
                $('.success_message').text("No File Found!");
                $('.success_message').addClass('active');
                $('.compare_ccda_button').removeClass('active');
                $('.dismiss_button').text('Ok');
            }
        },
        error: function error() {},
        cache: false,
        processData: false
    });
}

function getLocation(id) {
    var formData = {
        practice_id: id
    };

    $.ajax({
        url: '/import/location',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var locations = $.parseJSON(e);
            var content = '<option value="-1">Select Location</option>';
            locations.forEach(function (location) {
                content += '<option value="' + location.id + '">' + location.name + '</option>';
            });
            $('#practice_locations').html(content);
        },
        error: function error() {
            alert('Error removing');
        },
        cache: false,
        processData: false
    });
}

function importPatients() {
    var myform = document.getElementById("import_form");
    var fd = new FormData(myform);
    $.ajax({
        url: "import/xlsx",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function success(dataofconfirm) {

            $('.import_form').removeClass('active');
            $('.success_message').text(dataofconfirm);
            $('.success_message').addClass('active');
            $('.import_button').removeClass('active');
            $('.dismiss_button').text('Ok');
        }
    });
}

function saveCcdafile() {
    var myform = document.getElementById("import_ccda_form");
    var fd = new FormData(myform);
    $.ajax({
        url: "import/ccda",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function success(e) {
            var data = $.parseJSON(e);
            if (data != 'unsuccessful') {
                $('.dismiss_button').trigger('click');
                showComparisionData(data);
                $('#compare_ccda_button').trigger('click');
                $('#compared_patient_id').val(data.patient.id);
            }
        }
    });
}

function showComparisionData(data) {

    $('.ccda_email').find('input').val(data.ccda.email);
    $('.ccda_email').find('.ocuhub_data').text(data.patient.email);
    $('.ccda_email').find('.ccda_data').text(data.ccda.email);

    $('.ccda_firstname').find('input').val(data.ccda.firstname);
    $('.ccda_firstname').find('.ocuhub_data').text(data.patient.firstname);
    $('.ccda_firstname').find('.ccda_data').text(data.ccda.firstname);

    $('.ccda_lastname').find('input').val(data.ccda.lastname);
    $('.ccda_lastname').find('.ocuhub_data').text(data.patient.lastname);
    $('.ccda_lastname').find('.ccda_data').text(data.ccda.lastname);

    $('.ccda_workphone').find('input').val(data.ccda.workphone);
    $('.ccda_workphone').find('.ocuhub_data').text(data.patient.workphone);
    $('.ccda_workphone').find('.ccda_data').text(data.ccda.workphone);

    $('.ccda_homephone').find('input').val(data.ccda.homephone);
    $('.ccda_homephone').find('.ocuhub_data').text(data.patient.homephone);
    $('.ccda_homephone').find('.ccda_data').text(data.ccda.homephone);

    $('.ccda_cellphone').find('input').val(data.ccda.cellphone);
    $('.ccda_cellphone').find('.ocuhub_data').text(data.patient.cellphone);
    $('.ccda_cellphone').find('.ccda_data').text(data.ccda.cellphone);

    $('.ccda_cellphone').find('input').val(data.ccda.cellphone);
    $('.ccda_cellphone').find('.ocuhub_data').text(data.patient.cellphone);
    $('.ccda_cellphone').find('.ccda_data').text(data.ccda.cellphone);

    $('.ccda_title').find('input').val(data.ccda.title);
    $('.ccda_title').find('.ocuhub_data').text(data.patient.title);
    $('.ccda_title').find('.ccda_data').text(data.ccda.title);

    $('.ccda_add1').find('input').val(data.ccda.addressline1);
    $('.ccda_add1').find('.ocuhub_data').text(data.patient.addressline1);
    $('.ccda_add1').find('.ccda_data').text(data.ccda.addressline1);

    $('.ccda_add2').find('input').val(data.ccda.addressline2);
    $('.ccda_add2').find('.ocuhub_data').text(data.patient.addressline2);
    $('.ccda_add2').find('.ccda_data').text(data.ccda.addressline2);

    $('.ccda_city').find('input').val(data.ccda.city);
    $('.ccda_city').find('.ocuhub_data').text(data.patient.city);
    $('.ccda_city').find('.ccda_data').text(data.ccda.city);

    $('.ccda_zip').find('input').val(data.ccda.zip);
    $('.ccda_zip').find('.ocuhub_data').text(data.patient.zip);
    $('.ccda_zip').find('.ccda_data').text(data.ccda.zip);

    $('.ccda_country').find('input').val(data.ccda.country);
    $('.ccda_country').find('.ocuhub_data').text(data.patient.country);
    $('.ccda_country').find('.ccda_data').text(data.ccda.country);

    $('.ccda_bithdate').find('input').val(data.ccda.birthdate);
    $('.ccda_bithdate').find('.ocuhub_data').text(data.patient.birthdate);
    $('.ccda_bithdate').find('.ccda_data').text(data.ccda.birthdate);

    $('.ccda_gender').find('input').val(data.ccda.gender);
    $('.ccda_gender').find('.ocuhub_data').text(data.patient.gender);
    $('.ccda_gender').find('.ccda_data').text(data.ccda.gender);

    $('.ccda_lang').find('input').val(data.ccda.preferredlanguage);
    $('.ccda_lang').find('.ocuhub_data').text(data.patient.preferredlanguage);
    $('.ccda_lang').find('.ccda_data').text(data.ccda.preferredlanguage);
}

function showCCDA(url) {
    $.ajax({
        url: url,
        type: 'GET',
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            if (e != 'nofile') {
                $('#compare_ccda_button').trigger('click');
                $('.update_header').removeClass('active');
                $('.compare_form').removeClass('active');
                $('.success_message').text("No File Found!");
                $('.success_message').removeClass('active');
                $('.compare_ccda_button').removeClass('active');
                $('.view_patient_ccda').addClass('active');
                $('.view_patient_ccda').html(e);
                $('.dismiss_button').text('Ok');
            } else {

                $('#compare_ccda_button').trigger('click');
                $('.update_header').removeClass('active');
                $('.compare_form').removeClass('active');
                $('.success_message').text("No File Found!");
                $('.success_message').removeClass('active');
                $('.compare_ccda_button').removeClass('active');
                $('.dismiss_button').text('Ok');
            }
        },
        error: function error() {},
        cache: false,
        processData: false
    });
}
//# sourceMappingURL=import.js.map
