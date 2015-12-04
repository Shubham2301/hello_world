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
