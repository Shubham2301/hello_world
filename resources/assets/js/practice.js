$(document).ready(function() {
    $('#search_practice_button').on('click', function() {
        var searchvalue = $('#search_practice_input').val();
        var formData = {
            'value': searchvalue
        };

        getPractices(formData);
    });
    $('#savepractice').on('click', function() {
        var formdata = [];
        var practice_name = $('#practice_name').val();
        if (practice_name != '') {
            getLocationData();
            formdata.push({
                "practice_name": $('#practice_name').val(),
                "locations": locations
            });
            setNewLocationField();
            locations = [];
            createPractice(formdata);
        }
    });
    $('#add_location').on('click', function() {
        getLocationData();
        setNewLocationField();
    });

});

var locations = [];

function getLocationData() {

    if (validateLocation()) {
        locations.push({
            "location_name": $('#location_name').val(),
            "location_code": $('#location_code').val(),
            "location_address1": $('#location_address1').val(),
            "location_address2": $('#location_address2').val(),
            "location_city": $('#location_city').val(),
            "location_state": $('#location_state').val(),
            "location_zip": $('#location_zip').val(),
            "location_phone": $('#location_phone').val()
        });
    }
}

function validateLocation() {
    if ($('#location_name').val() == "")
        return false;
    else if ($('#location_code').val() == "")
        return false;
    else if ($('#location_address1').val() == "")
        return false;
    else if ($('#location_city').val() == "")
        return false;
    else if ($('#location_state').val() == "")
        return false;
    else if ($('#location_zip').val() == "")
        return false;
    else if ($('#location_phone').val() == "")
        return false;
    else
        return true;

}

function setNewLocationField() {
    $('#location_name').val('');
    $('#location_code').val('');
    $('#location_address').val('');
    $('#location_city').val('');
    $('#location_state').val('');
    $('#location_zip').val('');
    $('#location_phone').val('');

}

function getPractices(formData) {

    var tojson = JSON.stringify(formData);
    var scheduleimg = $('#schedule_practice_img').val();
    var deleteimage = $('#delete_practice_img').val();
    $.ajax({
        url: '/practices/search',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var practices = $.parseJSON(e);
            var content = '';
            $('#search_results').text(practices.length + ' results found');
            if (practices.length > 0) {
                practices.forEach(function(practice) {
                    content += '<div class="row search_item" data-id="' + practice.id + '"><div class="col-md-1"><input type="checkbox"></div><div class="col-md-2"><p>' + practice.name + '</p></div><div class="col-md-2">' + practice.address + '</div><div class="col-md-1"><img src="ass.gpg"></div><div class="col-md-3"><p>' + practice.ocuapps + '</p></div> <div class="col-md-1"><p ><img class="schedule_practice_img" src="' + scheduleimg + '"></p></div><div class="col-md-1"><p>Edit</p></div><div class="col-md-1"><img class="delete_practice_im" src="' + deleteimage + '"></div></div>';
                });
                $('.practice_list').addClass('active');
                $('.practice_search_content').html(content);

            }

        },
        error: function() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}

function createPractice(formData) {
    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/practices/create',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var practices = $.parseJSON(e);

        },
        error: function() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });









}
