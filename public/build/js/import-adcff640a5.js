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
        $('.import_form').addClass('active');
        $('.success_message').text('');
        $('.success_message').removeClass('active');
        $('.save_ccda_button').addClass('active');
        $('.dismiss_button').text('Cancel');
    });
    $('.save_ccda_button').on('click', function () {
        saveCcdafile();
    });
});

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
        success: function success(dataofconfirm) {
            if (dataofconfirm != 'unsuccessful') {
                $('.import_form').removeClass('active');
                $('.success_message').text(dataofconfirm);
                $('.success_message').addClass('active');
                $('.save_ccda_button').removeClass('active');
                $('.dismiss_button').text('Ok');
            }
        }
    });
}
//# sourceMappingURL=import.js.map
