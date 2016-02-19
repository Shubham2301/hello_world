$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#details').on('click', function () {
        $('.item_info').addClass('active');
    });
    $('#close_item_info').on('click', function () {
        $('.item_info').removeClass('active');
    });
    $('.files').on('change', '.checkbox', function () {
        if ($('.file_exchange_navbar_content_right').hasClass('active'))
            $('.file_exchange_navbar_content_right').removeClass('active');
        else
            $('.file_exchange_navbar_content_right').addClass('active');
    });
    $('.files').on('click', '.description_text', function () {
        $(this).css('height', 'auto');
    });
});
