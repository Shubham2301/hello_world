var providerSuggestions = new Array();
var practiceSuggestions = new Array();


function referredByProviderSuggestions(searchValue) {
    if (searchValue != '') {

        if (searchValue.length > 1) {
            showPrefetchedProviders(searchValue);
            return;
        }
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
                providerSuggestions = data;
                showRefferedBySuggestions(data, 'provider');

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

        if (searchValue.length > 1) {
            showPrefetchedPractices(searchValue);
            return;
        }

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
                practiceSuggestions = data;
                showRefferedBySuggestions(data, 'practice');

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


function showRefferedBySuggestions(data, type) {

    switch (type) {

        case 'practice':
            var content = '';
            var limit = 5;
            if (limit > data.length) {
                limit = data.length;
            }

            for (var i = 0; i < limit; i++) {
                content += '<p class="practice_suggestion_item">' + data[i] + '</p>';
            }
            if (content != '') {
                $('.practice_suggestions').addClass('active');
                $('.practice_suggestions').html(content);
            } else {
                $('.practice_suggestions').removeClass('active');
            }

            break;

        case 'provider':
            var content = '';
			var limit = 5;
			if (limit > data.length) {
				limit = data.length;
			}
			for (var i = 0; i < limit; i++) {
                content += '<p class="provider_suggestion_item">' + data[i] + '</p>';
            }

            if (content != '') {
                $('.provider_suggestions').addClass('active');
                $('.provider_suggestions').html(content);
            } else {
                $('.provider_suggestions').removeClass('active');
            }

            break;
    }
}

function showPrefetchedProviders(searchValue) {
	var data = [];
	var j = 0;
	for (var i = 0; i < providerSuggestions.length; i++) {
		if (providerSuggestions[i].toLowerCase().indexOf(searchValue.toLowerCase()) == 0) {
			data[j] = providerSuggestions[i];
			j++;
		}
	}
	showRefferedBySuggestions(data, 'provider');

	console.log(providerSuggestions);
}

function showPrefetchedPractices(searchValue) {
    var data = [];
    var j = 0;
    for (var i = 0; i < practiceSuggestions.length; i++) {
        if (practiceSuggestions[i].toLowerCase().indexOf(searchValue.toLowerCase()) == 0) {
            data[j] = practiceSuggestions[i];
            j++;
        }
    }
    showRefferedBySuggestions(data, 'practice');

    console.log(practiceSuggestions);
}
