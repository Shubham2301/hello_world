$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ajaxStart(function () {
    $("#loader-container").css("display", "block");
    $(".loader-container").css("display", "inline-block");
});

$(document).ajaxComplete(function () {
    $("#loader-container").css("display", "none");
    $(".loader-container").css("display", "none");
});

$(document).ready(function () {

    $("a").tooltip({
        container: 'body'
    });
    
    setTimeout(function () {
        $('#flash-message').hide();
    }, 10000);

    //    $('.menu-item').on('click', function () {
    //        if ($(this).hasClass('active') || $(this).hasClass('dropdown-toggle'))
    //            return;
    //        var id = $(this).attr('data-id');
    //        $('.menu-item').removeClass('active');
    //        $('.content-section').removeClass('active');
    //        $(this).addClass('active');
    //        $('#' + id).addClass('active');
    //    });

    $('.admin-console-item').on('click', function () {
        var id = $(this).attr('data-id');
        $('.admin-console-section').removeClass('active');
        $('#' + id).addClass('active');
    });

    $("#ses_logout_form").submit(function () {
        window.location.href = '/auth/logout';
    });

    $('.open_main_sidebar_mobile').on('click', function () {
        $('.mobile_sidebar_content').addClass('mobile_sidebar_active');
        $('.main_content').addClass('mobile_sidebar_active');
        $('.content-right').addClass('mobile_sidebar_active');
        $('.open_main_sidebar_mobile').addClass('hide');
        $('.close_main_sidebar_mobile').removeClass('hide');
    });
    $('.close_main_sidebar_mobile').on('click', function () {
        $('.mobile_sidebar_content').removeClass('mobile_sidebar_active');
        $('.main_content').removeClass('mobile_sidebar_active');
        $('.content-right').removeClass('mobile_sidebar_active');
        $('.open_main_sidebar_mobile').removeClass('hide');
        $('.close_main_sidebar_mobile').addClass('hide');
    });

});

function singleSignOff() {
    $("#ses_logout_form").submit();
}

//TODO: Based on the size and orientation of devide hide or show sidebar 
/*
sidebar begin
*/

/*
sidebar end
*/
