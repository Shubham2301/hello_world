'use strict';

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
            //$('#add_location').trigger('click');
            var counter = parseInt($('.location_counter').text());
            if ((!locations[counter]) && validateLocation()) {
                getLocationData();
            }
            var practice_id = $('#editmode').val();
            if (practice_id == "-1") {
                formdata.push({
                    "practice_name": $('#practice_name').val(),
                    "locations": locations
                });
                setNewLocationField();
                locations = [];
                createPractice(formdata);
            } else {
                //for edit mode
                formdata.push({
                    "practice_id": practice_id,
                    "practice_name": $('#practice_name').val(),
                    "locations": locations
                });
                updatePracticedata(formdata);

            }

        } else {
            alert('practice name is missing');
        }
    });

    $('#add_location').on('click', function() {
        var counter = parseInt($('.location_counter').text());
        if (!locations[counter]) {
            getLocationData();
        }
        setNewLocationField();
        var counter = parseInt($('.location_counter').text());
        $('.location_counter').text(locations.length);

    });
    $('#location_next').on('click', function() {
        var counter = parseInt($('.location_counter').text());

        if (counter < locations.length - 1) {
            $('.location_counter').text(counter + 1);
            popupLocationFields(locations[counter + 1]);

        }
    });
    $('#location_previous').on('click', function() {
        var counter = parseInt($('.location_counter').text());
        if (counter > 0) {
            $('.location_counter').text(counter - 1);
            popupLocationFields(locations[counter - 1]);
        }
    });
    $('.location_input').on('change', function() {
        var index = parseInt($('.location_counter').text());
        var field = $(this).attr('id');
        var value = $(this).val();
        if (locations[index])
            locations[index][field] = value;
    });
    $('.remove-location').on('click', function() {
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
    $('.practice_list').on('click', '.search_item', function() {
        var practice_id = $(this).attr('data-id');
        var formData = {
            'practice_id': practice_id
        };
        getPracticeInfo(formData);
    });
    $('#openModel').on('click', function() {
        var val = -1;
        $('#editmode').val(val);
        refreshAttributes();

    });

    $('#editPractice').on('click', function() {
        var val = $(this).attr('data-id');
        $('#editmode').val(val);
        console.log(currentPractice);
        setEditMode();

    });


});

var locations = [];
var currentPractice = [];

function getLocationData() {

    if (validateLocation()) {
        alert('push');
        locations.push({
            "locationname": $('#locationname').val(),
            "location_code": $('#location_code').val(),
            "addressline1": $('#addressline1').val(),
            "addressline2": $('#addressline2').val(),
            "city": $('#city').val(),
            "state": $('#state').val(),
            "zip": $('#zip').val(),
            "phone": $('#phone').val()
        });
    } else {
        alert('all fields are required');
    }
}

function validateLocation() {
    if ($('#locationname').val() == "") return false;
    else if ($('#location_code').val() == "") return false;
    else if ($('#addressline1').val() == "") return false;
    else if ($('#addressline2').val() == "") return false;
    else if ($('#city').val() == "") return false;
    else if ($('#state').val() == "") return false;
    else if ($('#zip').val() == "") return false;
    else if ($('#phone').val() == "") return false;
    else return true;
}

function setNewLocationField() {
    $('#locationname').val('');
    $('#location_code').val('');
    $('#addressline1').val('');
    $('#addressline2').val('');
    $('#city').val('');
    $('#state').val('');
    $('#zip').val('');
    $('#phone').val('');
}

function getPractices(formData) {
    var tojson = JSON.stringify(formData);
    var scheduleimg = $('#schedule_practice_img').val();
    var deleteimage = $('#delete_practice_img').val();
    $('.practice_info').removeClass('active');
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
                practices.forEach(function(practice) {
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
            var practiceid = $.parseJSON(e);
            $('#dontsave').trigger('click');
            var formData = {
                'practice_id': practiceid
            };
            getPracticeInfo(formData);

        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}

function popupLocationFields(locationdata) {
    $('#locationname').val(locationdata['locationname']);
    $('#location_code').val(locationdata['location_code']);
    $('#addressline1').val(locationdata['addressline1']);
    $('#addressline2').val(locationdata['addressline2']);
    $('#city').val(locationdata['city']);
    $('#state').val(locationdata['state']);
    $('#zip').val(locationdata['zip']);
    $('#phone').val(locationdata['phone']);
}

function refreshAttributes() {
    locations = [];
    setNewLocationField()
    $('.location_counter').text(0);
    //$('#dontsave').trigger('click');
    $('#practice_name').val('');
}

function getPracticeInfo(formdata) {

    $.ajax({
        url: '/practices/show',
        type: 'GET',
        data: $.param(formdata),
        contentType: 'text/html',
        async: false,
        success: function(e) {
            var info = $.parseJSON(e);
            showPracticeInfo(info);
        },
        error: function() {
            alert('Error getting practice information');
        },
        cache: false,
        processData: false
    });

}

function showPracticeInfo(info) {
    currentPractice = info;
    $('#editPractice').attr('data-id', info.practice_id);
    $('#the_practice_name').text(info.practice_name);
    var content = '';
    if (true) {
        info.locations.forEach(function(location) {
            content += '<div class="row practice_location_item"><div class="col-md-3"><p>' + location.locationname + '</p><br><br><p>' + location.addressline1 + '</p><p>' + location.addressline2 + '</p><br><p>' + location.phone + '</p></div><div class="col-md-2"><p>Assign roles </p><p>Assign users</p><p>edit</p><img src="" alt="x"></div><div class="col-md-7">';
            info.users.forEach(function(user) {
                content += '<div class="practice_users"><input type="checkbox"><span><p class="user_name">' + user.firstname + '</p></span><span><img src="" alt="0"></span></div>';

            });
            content += '</div></div>';
        });

        $('.practice_location_item_list').html(content);
        $('.practice_list').removeClass('active');
        $('.practice_info').addClass('active');

    }
}

function setEditMode() {
    locations = currentPractice.locations;
    popupLocationFields(locations[0]);
    $('.location_counter').text(0);
    $('#practice_name').val(currentPractice.practice_name);
    //alert('heloo');

}

function updatePracticedata(formdata) {
    var tojson = JSON.stringify(formdata);
    $.ajax({
        url: '/practices/edit',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var practiceid = $.parseJSON(e);
            $('#dontsave').trigger('click');
            var formData = {
                'practice_id': practiceid
            };
            getPracticeInfo(formData);

        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });



}
