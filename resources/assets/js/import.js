$(document).ready(function() {
    $(document).on('change', '#practice_list', function(e) {
        var practice_id = $(this).find(":selected").val();
        getLocation(practice_id);

    });
    $(document).on('change', '.file-input input[type="file"]', function() {
        if ($(this).val() != '')
            saveCcdafile();
    });

    $(document).on('change', '.xlsx_file-input input[type="file"]', function() {
        var filename = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        var clear_image_path = $('#clear_image_path').val();
        filename += '&nbsp;&nbsp;<img src="'+ clear_image_path +'" class="clear_image_path" data-toggle="tooltip" title="Remove File" data-placement="top">';
        $('.filename').html(filename);
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).on('click', '.import_button', function() {
        if ($('.xlsx_file-input input[type="file"]').val())
            importPatients();
        else {
            $('p.alert_message').text('please select a valid xlsx file');
            $('#alert').modal('show');
			$('#alert').css("z-index", "1500");
        }
    });

    $('.filename').on('click', '.clear_image_path', function() {
        $('.xlsx_file-input input[type="file"]').val('');
		$('.filename').html('');
    });

    $(document).on('click', '.open_import', function() {
        $('.xlsx_file-input input[type="file"]').val('');
		$('.filename').html('');
        $('.import_form').addClass('active');
        $('.success_message').text('');
        $('.success_message').removeClass('active');
        $('.import_button').addClass('active');
        $('.dismiss_button').text('Cancel');
    });

    $(document).on('click', '#import_ccda_button', function() {
        var id = $(this).attr('data-id');
        $('#ccda_patient_id').val(id);
        // $('.import_form').addClass('active');
        $('.success_message').text('');
        $('.success_message').removeClass('active');
        $('.save_ccda_button').addClass('active');
        $('.dismiss_button').text('Cancel');
    });


    $(document).on('click', '.save_ccda_button', function() {
        //saveCcdafile();
    });
    $(document).on('click', '.compare_ccda_button', function() {
        updatePatientData();
    });
    $(document).on('click', '#compare_ccda_button', function() {
        $('.compare_form').addClass('active');
        $('.success_message').text(" ");
        $('.success_message').removeClass('active');
        $('.compare_ccda_button').addClass('active');
        $('.dismiss_button').text('Cancel');
        $('.compare_row_item').each(function() {
            $(this).find('input').prop('checked', false);
        });
    });
    $(document).on('change', '#checked_all', function() {
        if ($(this).is(":checked")) {
            $('.compare_row_item').each(function() {
                $(this).find('input').prop('checked', true);
            });
        } else
            $('.compare_row_item').each(function() {
                $(this).find('input').prop('checked', false);
            });
    });
    $(document).on('click', '#download_ccda', function() {
        var url = $('#download_ccda').attr('data-href');
        getCcdaFile(url);
    })
    $(document).on('click', '#view_ccda', function() {
        var url = $(this).attr('data-href');
        showCCDA(url);
    });
    $(document).on('click', '.dismiss_button', function() {
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
        success: function(e) {
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
        error: function() {

        },
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
        success: function(e) {
            var locations = $.parseJSON(e);
            var content = '<option value="-1">Select Location</option>';
            locations.forEach(function(location) {
                content += '<option value="' + location.id + '">' + location.name + '</option>';

            });
            $('#practice_locations').html(content);
        },
        error: function() {
            $('p.alert_message').text('Error removing');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function importPatients() {
    $('.import_form').removeClass('active');
    $('.success_message').addClass('active');
    $('.import_button').removeClass('active');
    $('.success_message').html('Importing patients.<br>Please wait.');
    $('.dismiss_button').addClass('hide');
    var myform = document.getElementById("import_form");
    var fd = new FormData(myform);
    $.ajax({
        url: "/import/xlsx",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(dataofconfirm) {
            var patients = $.parseJSON(dataofconfirm);
            var content = '<span class="total_import">You have imported ' + patients.total + ' patients </span> </br><span style="color:#4d4d4d;"> New patients </span><span class="new_patient">' + patients.patients_added + '</span></br><span style="color:#4d4d4d;"> Already existing patients </span><span class="old_patient">' + patients.already_exist + '</span>';

            $('.success_message').html(content);
            $('.dismiss_button').text('Ok');
            $('.dismiss_button').removeClass('hide');
            if (typeof refreshOverview == 'function') {
                refreshOverview();
            };
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
        success: function(e) {
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
        success: function(e) {
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
        error: function() {

        },
        cache: false,
        processData: false
    });
}

function loadImportForm() {
    var formData = {};
    $.ajax({
        url: '/bulkimport',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            $('body').append(e);
        },
        error: function error() {
            $('p.alert_message').text('Error: Could not load import form.');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
