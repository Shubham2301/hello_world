function referredByProviderSuggestions(searchValue) {
    if (searchValue != '') {
        $.ajax({
            url: '/referredbyproviders',
            type: 'GET',
            data: $.param({
                'provider': searchValue,
            }),
            contentType: 'text/html',
            async: false,
            success: function success(e) {
                var data = $.parseJSON(e);
                var content = '';
                data.forEach(function(providerName) {
                    content += '<p class="provider_suggestion_item">' + providerName + '</p>';
                });
                if (content != '') {
                    $('.provider_suggestions').addClass('active');
                    $('.provider_suggestions').html(content);
                } else {
                    $('.provider_suggestions').removeClass('active');
                }

            },
            error: function error() {
                $('p.alert_message').text('Error searching');
                $('#alert').modal('show');
            },
            cache: false,
            processData: false
        });
    } else {
        $('.provider_suggestions').removeClass('active');
    }
}

function referredByPracticeSuggestions(searchValue) {
    if (searchValue != '') {
        $.ajax({
            url: '/referredbypractice',
            type: 'GET',
            data: $.param({
                'practice': searchValue,
            }),
            contentType: 'text/html',
            async: false,
            success: function success(e) {
                var data = [];
                data = $.parseJSON(e);
                var content = '';
                data.forEach(function(practiceName) {
                    content += '<p class="practice_suggestion_item">' + practiceName + '</p>';
                });
                if (content != '') {
                    $('.practice_suggestions').addClass('active');
                    $('.practice_suggestions').html(content);
                } else {
                    $('.practice_suggestions').removeClass('active');
                }

            },
            error: function error() {
                $('p.alert_message').text('Error searching');
                $('#alert').modal('show');
            },
            cache: false,
            processData: false
        });
    } else {
        $('.practice_suggestions').removeClass('active');
    }
}

function saveReferredByDetails(formData) {
    $.ajax({
        url: '/savereferredby',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            $('#referredby_details').modal('hide');
            var formData = {
                'id': e,
            };
            getPatientInfo(formData);
        },
        error: function() {
            $('p.alert_message').text('Error getting patient information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}