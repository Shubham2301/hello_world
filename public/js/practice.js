'use strict';

$(document).ready(function () {

    $('#search_practice_button').on('click', function () {
        var type = $('#search_practice_input_type').val();
        var value = $('#search_practice_input').val();

        if (value === '') {
            $('#search_practice_input').focus();
        }
        var formData = {
            'type': type,
            'value': value
        };
        getProviders(formData);
    });

    $('.practice_list').on('click', '.practice_list_item', function () {
        var id = $(this).attr('data-id');
        var formData = {
            'id': id
        };
        getProviderInfo(formData);
    });
});

function showProviderInfo(data) {

    $('.practice_list').removeClass('active');
    $('.practice_info').addClass('active');
}

function getProviderInfo(formData) {

    $.ajax({
        url: '/practices/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var info = $.parseJSON(e);
            showProviderInfo(info);
        },
        error: function error() {
            alert('Error getting practice information');
        },
        cache: false,
        processData: false
    });
}

function getProviders(formData) {

    $.ajax({
        url: '/practices/search',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var practices = $.parseJSON(e);
            var content = '<p><bold>' + practices.length + '<bold> results found</p><br>';

            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    content += '<div class="col-xs-12 practice_list_item" data-id="' + practice.id + '"><div class="row content-row-margin"><div class="col-xs-6">' + practice.name + ' <br> ' + practice.email + ' </div><div class="col-xs-6">' + '' + '<br> ' + '' + ' </div></div></div>';
                });
            }
            $('.practice_list').html(content);
            $('.practice_list').addClass('active');
        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}
//# sourceMappingURL=practice.js.map
