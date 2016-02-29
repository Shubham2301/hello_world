'use strict';

$(document).ready(function() {
    loadAllPractices();
    if ($('#editmode').val() && $('#editmode').val() != "-1") {
        var val = $('#editmode').val();
        var location_index = parseInt($('#location_index').val());
        showinfo = false;
        var formData = {
            'practice_id': val
        };
        getPracticeInfo(formData);
        setEditMode(location_index);
    }
    $('#search_practice_button').on('click', function() {
        var searchvalue = $('#search_practice_input').val();
        $('.no_item_found > p:eq(1)').text(searchvalue);
        $('.no_item_found > p:eq(1)').css('padding-left', '4em');
        $('.no_item_found').removeClass('active');
        var formData = {
            'value': searchvalue
        };
        getPractices(formData, 0);
        $('#refresh_practices').addClass('active');
    });
    $('#back').on('click', function() {
        $('.practice_info').removeClass('active');
        $('.practice_action').addClass('active');
        $('.practice_list').addClass('active');
    });
    $('#savepractice').on('click', function() {
        showinfo = true;
        var formdata = [];
        var practice_name = $('#practice_name').val();

        if (practice_name != '') {

            var counter = parseInt($('.location_counter').text());
            if ((!locations[counter]) && validateLocation()) {
                getLocationData();
            }
            var practice_id = $('#editmode').val();
            if (practice_id == "-1") {
                formdata.push({
                    "practice_name": $('#practice_name').val(),
                    "locations": locations,
                    "practice_email": $('#practice_email').val()
                });
                setNewLocationField();
                locations = [];
                createPractice(formdata);
            } else {
                //for edit mode
                if (locations[counter])
                    updateLocationData(counter);
                formdata.push({
                    "practice_id": practice_id,
                    "practice_name": $('#practice_name').val(),
                    "locations": locations,
                    "practice_email": $('#practice_email').val()
                });
                updatePracticedata(formdata);

            }

        } else {
            $('p.alert_message').text('practice name is missing');
            $('#alert').modal('show');
        }
    });
	$('#dontsave_new_practice').on('click',function(){
		$('#back_to_select_practice_btn').trigger('click');
	});

    $('#add_location').on('click', function() {
        var counter = parseInt($('.location_counter').text());
        if (!locations[counter]) {
            getLocationData();
        }
        if (locations[counter]) {
            updateLocationData(counter);
        }
        setNewLocationField();
        var counter = parseInt($('.location_counter').text());
        $('.location_counter').text(locations.length);

    });
    $('#location_next').on('click', function() {
        var counter = parseInt($('.location_counter').text());
        if (locations[counter]) {
            updateLocationData(counter);
        }

        if (counter < locations.length - 1) {
            $('.location_counter').text(counter + 1);
            popupLocationFields(locations[counter + 1]);

        }
    });
    $('#location_previous').on('click', function() {
        var counter = parseInt($('.location_counter').text());
        if (locations[counter]) {
            updateLocationData(counter);
        }
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
    $('#remove_location').on('click', function() {
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
    $('.practice_list').on('click', '.search_name', function() {
        var practice_id = $(this).parents('.search_item').attr('data-id');
        showinfo = true;
        var formData = {
            'practice_id': practice_id
        };
        getPracticeInfo(formData);
    });
    $('#openModel').on('click', function() {
        var val = -1;
        $('.editmode').val(val);
        refreshAttributes();

    });
    $('#edit_practice').on('click', function() {
        var val = $(this).attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/-1';
    });
    $('.practice_list').on('click', '.editpractice_from_row', function() {
        var val = $(this).parents('.search_item').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/-1';
    });
    $('.practice_list').on('click', '.removepractice_from_row', function() {
        var val = $(this).parents('.search_item').attr('data-id');
        showModalConfirmDialog('Are you sure?', function(outcome) {
            if (outcome) {
                var formData = {
                    'practice_id': val
                };
                removePractice(formData);
                var formData = {
                    'value': ''
                };
                getPractices(formData, currentpage);
                $(this).parents('.search_item').remove();
            }
        });
    });
    $('#remove_practice').on('click', function() {
        var val = $('#edit_practice').attr('data-id');
        showModalConfirmDialog('Are you sure?', function(outcome) {
            if (outcome) {
                var formData = {
                    'practice_id': val
                };
                removePractice(formData);
                $('#back').trigger('click');
                loadAllPractices();
            }
        });
    });
    $('.p_left').on('click', function() {
        var formData = {
            'value': ''
        };
        if (currentpage > 1)
            getPractices(formData, currentpage - 1);
    });
    $('.p_right').on('click', function() {
        var formData = {
            'value': ''
        };
        if (currentpage < lastpage)
            getPractices(formData, currentpage + 1);
    });
    $('#refresh_practices').on('click', function() {
        $('#search_practice_input').val('');
        loadAllPractices();
    });
    $('#back_to_select_practice_btn').on('click', function() {
        window.location = "/administration/practices";
    });
    $('#open_practice_form').on('click', function() {
        window.location = '/practices/create';
    });
    $('.practice_list').on('change', '#checked_all_practice', function() {
        if ($(this).is(":checked")) {
            $('.practice_search_content').each(function() {
                $(this).find('input').prop('checked', true);
            });
        } else
            $('.practice_search_content').each(function() {
                $(this).find('input').prop('checked', false);
            });
    });
    $('#new_location').on('click', function() {
		var val = $('#edit_practice').attr('data-id');
		window.location = '/administration/practices/edit/' + val + '/' + currentPractice.locations.length;
    });
    $('.practice_location_item_list').on('click', '.edit_location_frominfo', function() {
        var index = $(this).parent().parent().attr('data-index');
        var val = $('#edit_practice').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/' + index;
    });
    $('.practice_location_item_list').on('click', '.edit_location_frominfo', function() {
        var index = $(this).parent().parent().attr('data-index');
        var val = $('#edit_practice').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/' + index;
    });
    $('.practice_location_item_list').on('click', '.remove_location_frominfo', function() {
		var index = $(this).parent().parent().attr('data-locationid');
		var location_dom = $(this).parent().parent();
		var formData = {
			'location_id':index
		};
		removeLocation(formData,location_dom);

    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#search_practice_button").trigger("click");
        }
    });
});

var locations = [];
var currentPractice = [];
var showinfo = true;
var currentpage = 0;
var lastpage = 0;

function getLocationData() {

    if (validateLocation()) {
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
        $('p.alert_message').text('all fields are required');
        $('#alert').modal('show');
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

function loadAllPractices() {
    var formData = {
        'value': ''
    };
    getPractices(formData, 0);
    $('#refresh_practices').removeClass('active');
    $('.no_item_found').removeClass('active');
    if ($('#checked_all_practice').is(":checked")) {
        $('.practice_search_content').each(function() {
            $(this).find('input').prop('checked', true);
        });
    } else
        $('.practice_search_content').each(function() {
            $(this).find('input').prop('checked', false);
        });
}

function getPractices(formData, page) {
    var tojson = JSON.stringify(formData);
    var scheduleimg = $('#schedule_practice_img').val();
    var deleteimage = $('#delete_practice_img').val();
    $('.practice_info').removeClass('active');
    $('.practice_action').addClass('active');
    var assign_role_image = $('#assign_role_image_path').val();
    var assign_user_image = $('#assign_user_image_path').val();
    $.ajax({
        url: '/practices/search?page=' + page,
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var practices = $.parseJSON(e);
            var content = '';
            $('#search_results').text('');
            if (practices.length > 0 && practices[0]['total'] > 0) {
                practices.forEach(function(practice) {
                    content += '<div class="row search_item" data-id="' + practice.id + '"><div class="col-xs-3" style="display:inline-flex;"><div><input type="checkbox">&nbsp;&nbsp;</div><div class="search_name"><p>' + practice.name + '</p></div></div><div class="col-xs-3">' + practice.address + '</div><div class="col-xs-1"></div><div class="col-xs-3"><p>' + practice.ocuapps + '</p></div> <div class="col-xs-2 search_edit"><p><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;"></span><ul class="dropdown-menu" id="row_action_dropdown"><li><a href=""><img src="' + assign_role_image + '" class="assign_role_image" style="width:20px">Assign Roles</a></li><li><a href=""><img src="' + assign_user_image + '" class="assign_user_image" style="width:20px">Assign Users</a></li></ul></div></p>&nbsp;&nbsp;<p class="editpractice_from_row" data-toggle="modal" data-target="#create_practice">Edit</p><div class="dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepractice_from_row"><img src="' + deleteimage + '" alt="" class="removepractice_img"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete this?</strong></p></li><li class="confirm_buttons"><button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button"  class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                    //<img class="delete_practice_im" src="' + deleteimage + '">
                    //<img class="schedule_practice_img" src="' + scheduleimg + '">
                });



                currentpage = practices[0]['currentPage'];
                lastpage = practices[0]['lastpage'];
                var result = currentpage * 5;
                if (result > practices[0]['total'])
                    result = practices[0]['total'];
                $('.page_info').text(result + ' of ' + practices[0]['total']);


                $('.practice_list').addClass('active');
                $('.practice_search_content').html(content);
                if ($('#checked_all_practice').is(":checked")) {
                    $('.practice_search_content').each(function() {
                        $(this).find('input').prop('checked', true);
                    });
                } else
                    $('.practice_search_content').each(function() {
                        $(this).find('input').prop('checked', false);
                    });
            } else {
                $('.practice_list').removeClass('active');
                $('.no_item_found').addClass('active');
            }

        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function createPractice(formData) {
    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/practices/store',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var practiceid = $.parseJSON(e);
            window.location = "/administration/practices";
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
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
            currentPractice = info;
            if (showinfo)
                showPracticeInfo(info);
        },
        error: function() {
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function showPracticeInfo(info) {
    $('#edit_practice').attr('data-id', info.practice_id);
    $('#the_practice_name').text(info.practice_name);
    var content = '';
    if (info.locations.length > 0) {
        var i = 0;
        info.locations.forEach(function(location) {
            content += '<div class="row practice_location_item" data-locationid = "' + location.id + '" data-index="' + i + '"><div class="col-xs-3 practice_info"><p>' + location.locationname + '</p><p>' + location.addressline1 + '<br>' + location.addressline2 + '</p><p>' + location.phone + '</p></div><div class="col-xs-4 practice_assign"><p>Assign roles </p><p>Assign users</p><p class="edit_location_frominfo">Edit</p><br><center class="remove_location_frominfo"><span class="glyphicon glyphicon-remove" area-hidden="true" style="background: maroon;color: white;padding: 3px;border-radius: 3px;font-size: 0.9em;"></span></center></div><div class="col-xs-5"><div class="row">';
            info.users.forEach(function(user) {
                content += '<div class="col-xs-12 practice_users "><p style="width: 100%;"><input type="checkbox"><span>' + user.firstname + '</span><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background:#e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;float: right;margin-bottom: 5px;"></span></p></div>';
            });
            content += '</div></div></div>';
            i++;
        });

        $('.practice_location_item_list').html(content);
        $('.practice_list').removeClass('active');
        $('.practice_info').addClass('active');
        $('.practice_action').removeClass('active');


    }
}

function setEditMode(location_index) {
    locations = currentPractice.locations;
    var practice_name = currentPractice.practice_name;
    if (locations.length > 0) {
        var index = locations.length - 1;
        if (location_index == -1) {
            popupLocationFields(locations[index]);
            $('.location_counter').text(index);
        } else if (location_index > index) {
            setNewLocationField();
            $('.location_counter').text(locations.length);
            $('#locationname').focus();
        } else {
            popupLocationFields(locations[location_index]);
            $('.location_counter').text(location_index);
			$('#locationname').focus();
        }


    } else
        $('.location_counter').text(0);
    $('#practice_name').val(currentPractice.practice_name);
    $('#practice_email').val(currentPractice.practice_email);

}

function updatePracticedata(formdata) {
    var tojson = JSON.stringify(formdata);
    $.ajax({
        url: '/practices/update',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            window.location = "/administration/practices";
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function removePractice(formdata) {
    var tojson = JSON.stringify(formdata);
    $.ajax({
        url: '/practices/remove',
        type: 'GET',
        data: $.param(formdata),
        contentType: 'text/html',
        async: false,
        success: function success(e) {},
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function updateLocationData(index) {
    locations[index]['locationname'] = $('#locationname').val();
    locations[index]['location_code'] = $('#location_code').val();
    locations[index]['addressline1'] = $('#addressline1').val();
    locations[index]['addressline2'] = $('#addressline2').val();
    locations[index]['city'] = $('#city').val();
    locations[index]['state'] = $('#state').val();
    locations[index]['zip'] = $('#zip').val();
    locations[index]['phone'] = $('#phone').val();
}

function removeLocation(formData, location_dom){
	$.ajax({
		url: '/administration/practices/removelocation',
		type: 'GET',
		data: $.param(formData),
		contentType: 'text/html',
		async: false,
		success: function success(e) {
		location_dom.remove();
	},
		   error: function error() {
		$('p.alert_message').text('Error searching');
		$('#alert').modal('show');
	},
		cache: false,
			processData: false
});
}

function showModalConfirmDialog(msg, handler) {
    $('#practice_listing').on('click', '.confirm_yes', function(evt) {
        handler(true);
    });
    $('#practice_listing').on('click', '.confirm_no', function(evt) {
        handler(false);
    });

}
