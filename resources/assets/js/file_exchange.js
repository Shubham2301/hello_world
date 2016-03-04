$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#details').on('click', function () {
        $('.item_info').addClass('active');
    });
    $('#close_item_info').on('click', function () {
        $('.item_info').removeClass('active');
    });
    $('.files').on('change', '.checkbox', function () {
        if($('.checkbox:checkbox:checked').length === 0){
            $('.file_exchange_navbar_content_right').removeClass('active');
        }
        else if($('.checkbox:checkbox:checked').length !== 0 && !$('.file_exchange_navbar_content_right').hasClass('active')){
            $('.file_exchange_navbar_content_right').addClass('active');
        }
        if ($('.checkbox:checkbox:checked').length > 1) {
            $('.download-button').hide();
        }   
        else{
            $('.download-button').show();
        }
    });
    $('.files').on('click', '.description_text', function () {
        $(this).css('height', 'auto');
    });
    $('.download-button').on('click', function(){
        if($('.file-check:checkbox:checked').length > 1) {
            return;
        }
        var file_id = $('.file-check:checkbox:checked').attr('data-id');
        window.location = '/downloadFile?id=' + file_id;
    });
    $('.trash-button').on('click', function(){
        if($('.file-check:checkbox:checked').length > 1) {
            return;
        }
        var file_id = $('.file-check:checkbox:checked').attr('data-id');
        window.location = '/deleteFile?id=' + file_id;
    });
});
