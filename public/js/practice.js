'use strict';

$(document).ready(function () {
    $('#search_practice_button').on('click', function () {
        var searchvalue = $('#search_practice_input').val();
        var formData = {
            'value': searchvalue
        };

        getPractices(formData);
    });
    $('#savepractice').on('click', function () {

        var formdata = [];
        var practice_name = $('#practice_name').val();
        if (practice_name != '') {
            $('#add_location').trigger('click');
            formdata.push({
                "practice_name": $('#practice_name').val(),
                "locations": locations
            });
            setNewLocationField();
            locations = [];
            createPractice(formdata);
        } else {
            alert('practice name is missing');
        }
    });
    $('#add_location').on('click', function () {
        var counter = parseInt($('.location_counter').text());
        if (!locations[counter]) {
            getLocationData();
        }
        setNewLocationField();
        var counter = parseInt($('.location_counter').text());
        $('.location_counter').text(counter + 1);
    });
    $('#location_next').on('click', function () {
        var counter = parseInt($('.location_counter').text());

        if (counter < locations.length - 1) {
            $('.location_counter').text(counter + 1);
            popupLocationFields(locations[counter + 1]);
        }
    });
    $('#location_previous').on('click', function () {
        var counter = parseInt($('.location_counter').text());
        if (counter > 0) {
            $('.location_counter').text(counter - 1);
            popupLocationFields(locations[counter - 1]);
        }
    });
    /*$('.location_input').on('change',function(){
        var index = parseInt($('.location_counter').text());
        var field = $(this).attr('id');
        var value = $(this).val();
        if(locations.length >= 0)
        locations[index][field] = value;
    });  */
    $('.remove-location').on('click', function () {
        var index = parseInt($('.location_counter').text());
        locations.splice(index, 1);
        var length = locations.length;

        if (length > 0) {

            if (index > 0) {
                popupLocationFields(locations[index - 1]);
                $('.location_counter').text(index - 1);
            } else {
                popupLocationFields(locations[index + 1]);
                $('.location_counter').text(index + 1);
            }
        } else {
            setNewLocationField();
            var counter = parseInt($('.location_counter').text());
            $('.location_counter').text(0);
        }
    });
    $('.practice_list').on('click', '.search_item', function () {

        var practice_id = $(this).attr('data-id');
        alert(practice_id);
        var formData = {
            'practice_id': practice_id
        };
        getPracticeInfo(formData);
    });
});

var locations = [];

function getLocationData() {

    if (validateLocation()) {
        alert('push');
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
    } else {
        alert('all fields are required');
    }

    locations[0]['location_name'] = "hiii";
    console.log(locations[0]['location_name']);
    console.log(JSON.stringify(locations));
}

function validateLocation() {
    if ($('#location_name').val() == "") return false;else if ($('#location_code').val() == "") return false;else if ($('#location_address1').val() == "") return false;else if ($('#location_city').val() == "") return false;else if ($('#location_state').val() == "") return false;else if ($('#location_zip').val() == "") return false;else if ($('#location_phone').val() == "") return false;else return true;
}

function setNewLocationField() {
    $('#location_name').val('');
    $('#location_code').val('');
    $('#location_address1').val('');
    $('#location_address2').val('');
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
        success: function success(e) {
            var practices = $.parseJSON(e);
            var content = '';
            $('#search_results').text(practices.length + ' results found');
            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    content += '<div class="row search_item" data-id="' + practice.id + '"><div class="col-md-1"><input type="checkbox"></div><div class="col-md-2"><p>' + practice.name + '</p></div><div class="col-md-2">' + practice.address + '</div><div class="col-md-1"><img src="ass.gpg"></div><div class="col-md-3"><p>' + practice.ocuapps + '</p></div> <div class="col-md-1"><p ><img class="schedule_practice_img" src="' + scheduleimg + '"></p></div><div class="col-md-1"><p>Edit</p></div><div class="col-md-1"><img class="delete_practice_im" src="' + deleteimage + '"></div></div>';
                });
                $('.practice_list').addClass('active');
                $('.practice_search_content').html(content);
            }
        },
        error: function error() {
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
        success: function success(e) {
            var practices = $.parseJSON(e);
            refreshAttributes();
        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}

function popupLocationFields(locationdata) {
    $('#location_name').val(locationdata['location_name']);
    $('#location_code').val(locationdata['location_code']);
    $('#location_address1').val(locationdata['location_address1']);
    $('#location_address2').val(locationdata['location_address2']);
    $('#location_city').val(locationdata['location_city']);
    $('#location_state').val(locationdata['location_state']);
    $('#location_zip').val(locationdata['location_zip']);
    $('#location_phone').val(locationdata['location_phone']);
}

function refreshAttributes() {
    locations = [];
    setNewLocationField();
    $('.location_counter').text(0);
    $('#dontsave').trigger('click');
}

function getPracticeInfo(formdata) {

    $.ajax({
        url: '/practices/show',
        type: 'GET',
        data: $.param(formdata),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var info = $.parseJSON(e);
            showPracticeInfo(info);
        },
        error: function error() {
            alert('Error getting practice information');
        },
        cache: false,
        processData: false
    });
}

function showPracticeInfo(info) {
    $('#the_practice_name').text(info.practice_name);
    var content = '';
    if (true) {
        info.locations.forEach(function (location) {
            content += '<div class="row practice_location_item"><div class="col-md-3"><p>' + location.locationname + '</p><br><br><p>' + location.addressline1 + '</p><p>' + location.addressline2 + '</p><br><p>' + location.phone + '</p></div><div class="col-md-2"><p>Assign roles </p><p>Assign users</p><p>edit</p><img src="" alt="x"></div><div class="col-md-7">';
            info.users.forEach(function (user) {
                content += '<div class="practice_users"><input type="checkbox"><span><p class="user_name">' + user.firstname + '</p></span><span><img src="" alt="0"></span></div>';
            });
            content += '</div></div>';
        });

        $('.practice_location_item_list').html(content);
        $('.practice_list').removeClass('active');
        $('.practice_info').addClass('active');
    }
}
//# sourceMappingURL=practice.js.map
