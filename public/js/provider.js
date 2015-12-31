'use strict';

$(document).ready(function () {

    $('#search_provider_button').on('click', function () {
        var type = $('#search_provider_input_type').val();
        var value = $('#search_provider_input').val();

        if (value === '') {
            $('#search_provider_input').focus();
        }
        var formData = {
            'type': type,
            'value': value
        };
        getProviders(formData);
    });

    $('.provider_list').on('click', '.provider_list_item', function () {
        var id = $(this).attr('data-id');
        var formData = {
            'id': id
        };

        getProviderInfo(formData);
    });
});

function showProviderInfo(data) {

    $('.provider_list').removeClass('active');
    $('.provider_info').addClass('active');
}

function getProviderInfo(formData) {

    $.ajax({
        url: '/providers/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var info = $.parseJSON(e);
            showProviderInfo(info);
        },
        error: function error() {
            alert('Error getting provider information');
        },
        cache: false,
        processData: false
    });
}

function getProviders(formData) {

    $.ajax({
        url: '/providers/search',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var providers = $.parseJSON(e);
            var content = '<p><bold>' + providers.length + '<bold> results found</p><br>';

            if (providers.length > 0) {
                providers.forEach(function (provider) {
                    content += '<div class="col-xs-12 provider_list_item" data-id="' + provider.id + '"><div class="row content-row-margin"><div class="col-xs-6">' + provider.name + ' <br> ' + provider.email + ' </div><div class="col-xs-6">' + '' + '<br> ' + '' + ' </div></div></div>';
                });
            }
            $('.provider_list').html(content);
            $('.provider_list').addClass('active');
        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}
//# sourceMappingURL=provider.js.map
