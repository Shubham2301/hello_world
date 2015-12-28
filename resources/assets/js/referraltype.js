$(document).ready(function () {

    $('.configuration_tile').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).children('p').children('span').removeClass('glyphicon-ok');
            $(this).children('p').children('span').addClass('glyphicon-pencil');
            $('.remove_referral_type').removeClass('active');
        } else {
            $(this).addClass('active');
            $(this).children('p').children('span').removeClass('glyphicon-pencil');
            $(this).children('p').children('span').addClass('glyphicon-ok');
            $('.remove_referral_type').addClass('active');
        }
    });

    $('.remove_referral_type').on('click', function () {
        var id = $(this).attr('data-id');
        removeReferralType(id);
    });

    $('.referr_patient').on('click', function () {
        var id = $(this).attr('data-id');
        selectPatient(id);
    });
});

function selectPatient(id) {
    
    $('#form_referraltype_id').val(id);
    $('#form_select_patient').submit();

}

function removeReferralType(id) {

    var formData = {
        id: id
    };

    $.ajax({
        url: '/home/removereferral',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            $('.referral_tile[data-id="' + id + '"]').parent().remove();
        },
        error: function () {
            alert('Error removing');
        },
        cache: false,
        processData: false
    });

}

// TODO: function to send AJAX call and add another tile. 
/*function addReferralType(arguments) {

}*/
