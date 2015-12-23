'use strict';

$(document).ready(function () {

    $('.configuration_tile').on('click', function () {
        $(this).addClass('active');
        $(this).children('p').children('span').removeClass('glyphicon-pencil');
        $(this).children('p').children('span').addClass('glyphicon-plus');
        $('.remove_referral_type').addClass('active');
    });

    $('.remove_referral_type').on('click', function () {
        var id = $(this).attr('data-id');
        removeReferralType(id);
    });
});

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
        success: function success(e) {
            $('.referral_tile[data-id="' + id + '"]').parent().remove();
        },
        error: function error() {
            alert('Error removing');
        },
        cache: false,
        processData: false
    });
}

/*function addReferralType(arguments) {

}*/
//# sourceMappingURL=referraltype.js.map
