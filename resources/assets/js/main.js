$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ajaxStart(function(){
    $("#loader-container").css("display", "block");
});

$(document).ajaxComplete(function(){
    $("#loader-container").css("display", "none");
});

$(document).ready(function () {

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
