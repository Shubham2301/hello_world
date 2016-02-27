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
});

function singleSignOff() {
    $("#ses_logoout_form").attr("target", "ocuhubSESiframe");
    $("#ses_logoout_form").submit(function () {
        $('#ocuhub_logout').click();
    });
}

//TODO: Based on the size and orientation of devide hide or show sidebar 
/*
sidebar begin
*/

/*
sidebar end
*/
