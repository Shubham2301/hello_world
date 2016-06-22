$(document).ready(function() {


    $(document).on('change', '#practice_list', function(e) {
        var practice_id = $(this).find(":selected").val();
        getLocation(practice_id);

    });
//    $(document).on('change', '.file-input input[type="file"]', function() {
//        if ($(this).val() != '')
//            saveCcdafile();
//    });

    $(document).on('change', '.xlsx_file-input input[type="file"]', function() {
        var filename = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        var clear_image_path = $('#clear_image_path').val();
        filename += '&nbsp;&nbsp;<img src="' + clear_image_path + '" class="clear_image_path" data-toggle="tooltip" title="Remove File" data-placement="top">';
        $('.xlsx_file_filename').html(filename);
        if (filename.length > 0)
            $('.xlsx_file-input').removeClass('active');

        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).on('click', '.import_button', function() {
        if ($('.xlsx_file-input input[type="file"]').val())
            importPatients();
        else {
            $('p.alert_message').text('Please select a valid xlsx file');
            $('#alert').modal('show');
            $('#alert').css("z-index", "1500");
        }
    });

    $('.filename').on('click', '.clear_image_path', function() {


        $('.xlsx_file-input input[type="file"]').val('');
        $('.filename').html('');
        $('.xlsx_file-input').addClass('active');
    });

    $(document).on('click', '.open_import', function() {
        $('.xlsx_file-input input[type="file"]').val('');
        $('.filename').html('');
        $('.xlsx_file-input').addClass('active');
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

        if ($('#form_edit_mode').val()) {
            setCheckedFieldInForm();
            return;
        }
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
    });
    $(document).on('click', '#view_ccda', function() {
        var url = $(this).attr('data-href');
        showCCDA(url);
    });
    $(document).on('click', '.dismiss_button', function() {
        $('.view_patient_ccda').removeClass('active');
        $('.view_patient_ccda').html(' ');
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
    $('.patient_files_show').on('click', function() {
        $('.patient_files_content').toggleClass('active');
        if ($('.patient_files_content').hasClass('active')) {
            $('.patient_files_icon').removeClass('glyphicon-chevron-right');
            $('.patient_files_icon').addClass('glyphicon-chevron-down');
        } else {
            $('.patient_files_icon').removeClass('glyphicon-chevron-down');
            $('.patient_files_icon').addClass('glyphicon-chevron-right');
        }
    });

    $(document).on('change', '#import_from_ccda input[type="file"]', function() {
        if ($(this).val() != '') {
            getCCDAData();
        }
    });

    $(document).on('change', '.file_upload_form_input input[type="file"]', function() {
        var filename = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        var clear_image_path = $('#clear_image_path').val();
        filename += '&nbsp;&nbsp;<img src="' + clear_image_path + '" class="remove_file_name " data-toggle="tooltip" title="Remove File" data-placement="top">';
        var parent = $(this).parent();
        if (filename.length > 0) {
            parent.removeClass('active');
        }
        parent.siblings('.file_upload_form_filename').html(filename);

        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).on('click', '.remove_file_name', function() {
        $(this).parent().siblings('.file_upload_form_input').addClass('active');
        var inputDom = $(this).parent().siblings('.file_upload_form_input');
        inputDom.find('input').val('');
        $(this).parent().html('');
    });

    $(document).on('click', '.upload_files_btn', function() {
        uploadPatientFiles();
    })

	$(document).on('click', '.upload_file_view_btn', function(){
		getFileUploadView();

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
                $('.dismiss_button').text('OK');


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
            $('.dismiss_button').text('OK');
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
            if (data.error) {
                $('p.alert_message').text('Error: Please provide a valid CCDA file.');
                $('#alert').modal('show');
            } else {
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
                $('.dismiss_button').text('OK');
            } else {

                $('#compare_ccda_button').trigger('click');
                $('.update_header').removeClass('active');
                $('.compare_form').removeClass('active');
                $('.success_message').text("No File Found!");
                $('.success_message').removeClass('active');
                $('.compare_ccda_button').removeClass('active');
                $('.dismiss_button').text('OK');

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

function fillPatientData(data) {
    $('#patient_name').text(data.lastname + ', ' + data.firstname);
    $('#patient_email').text(data.email);
    $('#patient_dob').text(data.birthdate);
    $('#patient_add1').text(data.addressline1 + '');
    $('#patient_add2').text(data.addressline2 + '');
    $('#patient_add3').text(data.city);
    var phone = '';
    if (data.cellphone != '')
        phone += '<span>Cellphone: ' + data.cellphone + '</span><br>';
    if (data.workphone != '')
        phone += '<span>Workphone: ' + data.workphone + '</span><br>';
    if (data.homephone != '')
        phone += '<span>Homephone: ' + data.homephone + '</span>';
    if (phone == '')
        phone += '<span>-</span>'
    $('#patient_phone').html(phone);
    $('#patient_ssn').text(data.lastfourssn);
    $('#select_provider_button').attr('data-id', data.id);
    $('#select_provider_button').addClass('active');
    $('.action-btns').removeClass('active');
    $('#import_patients').hide();

    $('.lastseen_content').html('<p class="patient_dropdown_data">' + data.referred_to_practice_user + '</p><p class="patient_dropdown_data">' + data.referred_to_practice + '</p>');

    $('.insurance_provider_content').html('<p class="patient_dropdown_data">' + data.insurance + '</p>');
    if (data.referred_to_practice_user == '' && data.referred_to_practice == '')
        $('.lastseenby_icon').addClass('hide');
    else {
        $('.lastseenby_icon').removeClass('hide');
        $('.patient_table_header').removeClass('hide');
    }
    $('.referredby_content').addClass('active');

    if (data.referred_by_provider == '' && data.referred_by_practice == '') {
        $('.referredby_content').html('<div><a data-toggle="modal" data-target="#referredby_details" id="referred_by_details_btn" class="button_type_1"> Add Details</a></div>');
    } else {
        $('.referredby_content').html('<p class="patient_dropdown_data">' + data.referred_by_provider + '</p><p class="patient_dropdown_data">' + data.referred_by_practice + '</p>');
        $('.patient_table_header').removeClass('hide');
    }
    if (data.insurance == '')
        $('.insurance_provider_icon').addClass('hide');
    else {
        $('.insurance_provider_icon').removeClass('hide');
        $('.patient_table_header').removeClass('hide');
    }

    if (data.ccda) {
        $('.patient_files_content').html(' <div class="patient_file_item row"><div class="col-xs-7"><p class="file_name" >CCDA ' + data.ccda_date + '</p></div><div class="col-xs-2"><a href="/show/ccda/' + data.id + '" class="view_file" target="_blank" >View</a></div><div class="col-xs-3"><a href="/download/ccda/' + data.id + '" class="download_file">Get</a></div></div>');
    } else {
        $('.patient_files_content').html('No Files');
    }
}

function getCCDAData() {
    var myform = document.getElementById("import_from_ccda_form");
    var fd = new FormData(myform);
    $.ajax({
        url: "/getccdadataforform",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(e) {
            var data = $.parseJSON(e);
            if (data.error) {
                $('p.alert_message').text('Error: Please provide a valid CCDA file.');
                $('#alert').modal('show');
            } else if ($('#form_edit_mode').val()) {
                $('#compareCcda').modal('show');
                showComparisionData(data);
            } else {
                for (var key in data.ccda) {
                    $('#' + key).val(data.ccda[key]);
                }

            }
        }
    });
}

function setCheckedFieldInForm() {
    var $inputs = $('#compare_ccda_form :input');
    $inputs.each(function() {
        if ($(this).prop('checked')) {
            $('#form_add_patients').find('#' + this.name).val($(this).val());
        }
    });
    $('#compareCcda').modal('hide');
}

function uploadPatientFiles(){
    $('#upload_patient_id').val($('.patient_info').attr('data-id'));
    $('#upload_referral_id').val($('#form_referraltype_id').val());

	var myform = document.getElementById("upload_files_form");
	var fd = new FormData(myform);
	$.ajax({
		url: "/uploadpatientfiles",
		data: fd,
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function(e) {
			alert('successfull');
			$('#upload_files').modal('hide');
			//var data = $.parseJSON(e);

		}
	});
}

function getFileUploadView() {

    var formData = {
            'upload_referral_id': $('#form_referraltype_id').val(),
        };
    $.ajax({
		url: "/getfileuploadview",
        cache: false,
        data: $.param(formData),
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(e) {
            $('#file_upload_view').html(e);
            $('#upload_files').modal('show');

        }
    });
}

function showPatientFiles(){

}



