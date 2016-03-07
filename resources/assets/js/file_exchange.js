$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#details').on('click', function () {
        $('.item_info').addClass('active');
    });
    $('#close_item_info').on('click', function () {
        $('.item_info').removeClass('active');
    });
    $('#share_practices').on('change', function () {
        practiceUsers($(this).val());
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
            $('.info-button').hide();
        }   
        else{
            $('.download-button').show();
            $('.info-button').show();
        }
    });
    $('.share-button').on('click', function(){
        $('#shareModal').modal('show'); 
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

function practiceUsers(id) {
        var formData = {
            'id' : id
        }
        $.ajax({
        url: '/practices/users',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var data = $.parseJSON(e);
            if (data.length === 0) {
                return;
            }
            var content = '<option value="0">Select User</option>';
            $.each(data, function (index, val) {
                content += '<option value="' + val.id + '">' + val.name + '</option>';
            })
            $('#share_users').html(content);
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
