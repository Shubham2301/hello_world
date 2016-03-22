$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#details').on('click', function () {
        var id = $("input[type='checkbox']:checked").attr('data-id');
        var name = $("input[type='checkbox']:checked").attr('data-name');
        showInfo(id, name);
        $('.item_info').addClass('active');
    });
    $('#item_info').on('click', '#close_item_info',function () {
        $('.item_info').removeClass('active');
    });
    $('#item_info').on('focusout', '#description',function () {
            var new_description = $('#description').val();
            var old_description = $('#old_description').val();
            if(new_description != old_description)
                updateDescription();
    });
    $('#add_document').on('change',function () {
        var path = $('#add_document').val();
        $('#new_filename').html('');
        if (path) {
            var startIndex = (path.indexOf('\\') >= 0 ? path.lastIndexOf('\\') : path.lastIndexOf('/'));
            var filename = path.substring(startIndex);
            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1);
            }
            $('#new_filename').html(filename);
        }
    });
    $('.share-button').on('click', function () {
        var files = [];
        var folders = [];
        $.each($('.folder-check.checkbox:checkbox:checked'), function (index, val) {
           folders.push($(this).attr('data-id'));
        });
        $.each($('.file-check.checkbox:checkbox:checked'), function (index, val) {
           files.push($(this).attr('data-id'));
        });
        $('#share_files').val(files);
        $('#share_folders').val(folders);
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
		$(this).toggleClass('show_discription');
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

function showInfo(id, name) {
    var formData = {
        'id' : id,
        'name' : name
    };
    $.ajax({
        url: '/file_exchange/showinfo',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(data) {
            var content = '';
            content +='<span class="title arial_bold"><span>' + data.name + '</span><span class="glyphicon glyphicon-remove" id="close_item_info"></span></span><br><span class="modifications arial_bold">Modifications</span><br>';
            var i;
            for(i = 0; i < data.modified_by.length; i++)
                content +='<span class="modification_history"><span>' + data.modified_by[0] + '</span><span>' + data.updated_at[0] + '</span></span>';
            content +='<br><span class="modifications arial_bold">Edit Description</span><br><textarea name="textarea" id="description" class="description arial_italic"></textarea><input type="hidden" id="old_description" value="' + data.description + '"><input type="hidden" id="description_id" value="'+ id +'"><input type="hidden" id="name" value="'+ name +'">';
            $('#item_info').html(content);
            $('#description').val(data.description);
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function updateDescription(){
    var description = $('#description').val();
    var id = $('#description_id').val();
    var name = $('#name').val();
    var formData = {
        'id' : id,
        'description' : description,
        'name' : name
    };
    $.ajax({
        url: 'file_exchange/update_description',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var id = '#'+e.id+'_'+e.name;
            $(id).html(e.description);
        },
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function practiceUsers(id) {
        var formData = {
            'id' : id
        };
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
