$(document).ready(function () {

    $('#update_value').attr('disabled', true);
    $('#update_button').attr('disabled', true);

    getCleanUpList();

    $('#clean_up_text').on('keyup', function () {
        getCleanUpList();
    });
    $('#clean_up_option').on('change', function () {
        $('.list_row').html('');
        $('#clean_up_text').val('');
        $('#update_value').attr('disabled', true);
        $('#update_button').attr('disabled', true);
        getCleanUpList();
    });
    $('#update_button').on('click', function () {
        updateValue();
    });

    $('#update_value').on('keyup', function () {
        if ($.trim($('#update_value').val())) {
            $('#update_button').attr('disabled', false);
        } else {
            $('#update_button').attr('disabled', true);
        }
    });
});

function getCleanUpList() {
    var formData = {
        'value': $('#clean_up_text').val(),
        'filter': $('#clean_up_option').val(),
    };
    $.ajax({
        url: '/administration/cleanup/showlist',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: true,
        success: function success(e) {
            var data = $.parseJSON(e);
            var content = '';
            for (var i = 0; i < data.length; i++) {
                content += '<div class="list_item"><input type="checkbox" value="' + data[i].list_item + '"> ' + data[i].list_item + '</div>';
            }
            if (content != '') {
                $('.list_row').html(content);
                $('#update_value').attr('disabled', false);
            } else {
                $('.list_row').html('');
                $('#update_value').attr('disabled', true);
            }
            $('#update_button').attr('disabled', true);
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function updateValue() {
    var formData = {
        'list': [],
        'correctedValue': $.trim($('#update_value').val()),
        'filter': $('#clean_up_option').val()
    };
    $.each($("input[type='checkbox']:checked"), function () {
        formData.list.push($(this).val());
    });
    if (!formData.list[0]) {
        $('p.alert_message').text('Please select a value to be updated');
        $('#alert').modal('show');
        return;
    }
    $.ajax({
        url: '/administration/cleanup/cleanlist',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: true,
        success: function success(e) {
            $('#clean_up_text').val('');
            $('.list_row').html('');
            $('#update_value').val('');
            $('#update_value').attr('disabled', true);
            $('#update_button').attr('disabled', true);
            getCleanUpList();
        },
        error: function error() {
            $('p.alert_message').text('Error updating');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
