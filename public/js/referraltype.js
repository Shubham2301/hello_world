'use strict';

$(document).ready(function () {

    $('.configuration_tile').on('click', function () {
        //console.log('hiiiii');
        //console.log($('#referrlist').find(":selected").text());
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).children('p').children('span').removeClass('glyphicon-ok');
            $(this).children('p').children('span').addClass('glyphicon-pencil');
            $('.remove_referral_type').removeClass('active');
            $('.referral_tile_add').css('display', 'none');
        } else {
            $(this).addClass('active');
            $(this).children('p').children('span').removeClass('glyphicon-pencil');
            $(this).children('p').children('span').addClass('glyphicon-ok');
            $('.remove_referral_type').addClass('active');
            $('.referral_tile_add').css('display', 'inline');
            $('#referrname').text($('#referr_list').find(":selected").text());
            $('.add_referral_type').attr('data-id', $('#referr_list').find(":selected").val());
        }
    });

    $('#existing_referraltypes').on('click', '.remove_referral_type', function () {
        var id = $(this).attr('data-id');
        removeReferralType(id);
    });
    $('.add_referral_type').on('click', function () {
        var id = $(this).attr('data-id');
        var display_name = $('#referr_list').find(":selected").attr('data-name');
        var name = $('#referr_list').find(":selected").text();

        addReferralType(id, display_name, name);
    });

    $('#existing_referraltypes').on('click', '.referr_patient', function () {
        var id = $(this).attr('data-id');
        selectPatient(id);
    });

    $('#referr_list').on('change', function (e) {

        $('#referrname').text($(this).find(":selected").text());
        $('.add_referral_type').attr('data-id', $(this).find(":selected").val());
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

// TODO: function to send AJAX call and add another tile.
function addReferralType(id, display_name, name) {

    var formData = {
        id: id
    };

    $.ajax({
        url: '/home/addreferral',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            // success :
            $('#existing_referraltypes').append("<div class=\"col-xs-2 referral_tile_outer\"><div class=\"referral_tile referr_patient\" data-id=\"" + id + "\"><span class=\"remove_referral_type glyphicon glyphicon-remove active \" data-id=\"" + id + "\" aria-hidden=\"true\"></span><div class=\"referral_tile_inner\"></div><p>" + name + "</p></div><p>" + display_name + "</p></div> ");
        },
        error: function error() {
            alert('Error removing');
        },
        cache: false,
        processData: false
    });
}
//# sourceMappingURL=referraltype.js.map
