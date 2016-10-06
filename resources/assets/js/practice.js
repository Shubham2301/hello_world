'use strict';

window.onload = function () {
    tinymce.init({
        selector: 'textarea'
    });
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
    $('#search_practice_button').on('click', function () {
        var searchvalue = $('#search_practice_input').val();
        $('.no_item_found > p:eq(1)').text(searchvalue);
        $('.no_item_found > p:eq(1)').css('padding-left', '4em');
        $('.no_item_found').removeClass('active');
        if (searchvalue != '') {
            var formData = {
                'value': searchvalue,
                'include_deactivated': $('#include_deactivated').prop('checked') ? $('#include_deactivated').prop('checked') : false
            };
            getPractices(formData, 0);
            $('#refresh_practices').addClass('active');
        } else {
            $('#refresh_practices').removeClass('active');
            loadAllPractices();
        }
    });
    $('#back').on('click', function () {
        $('.practice_info').removeClass('active');
        $('.practice_action_header').removeClass('hide');
        $('.practice_list').addClass('active');
    });

    $('.practice_location_item_list').on('click', '.user_disable', function () {
        var val = $(this).attr('data-id');
        var id = [];
        id.push(val);
        removeUser(id);
    });

    $('#savepractice').on('click', function () {
        showinfo = true;
        var formdata = [];
        var practice_name = $('#practice_name').val();
        $('#practice_email').val($('#practice_email').val().trim());
        var patt = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
        if (practice_name != '' && $('#practice_email').val() != '' && $('#practice_network').val() != '') {

            if (patt.test($('#practice_email').val())) {

                var counter = parseInt($('.location_counter').text());
                if ((!locations[counter]) && validateLocation() && validateEmail()) {
                    getLocationData();
                }
                var practice_id = $('#editmode').val();
                if (practice_id == "-1") {
                    formdata.push({
                        "practice_name": $('#practice_name').val(),
                        "locations": locations,
                        "practice_email": $('#practice_email').val(),
                        "practice_network": $('#practice_network').val()
                    });
                    setNewLocationField();
                    locations = [];
                    createPractice(formdata);
                } else {
                    if (locations[counter])
                        updateLocationData(counter);
                    formdata.push({
                        "practice_id": practice_id,
                        "practice_name": $('#practice_name').val(),
                        "locations": locations,
                        "removed_location": removedLocation,
                        "practice_email": $('#practice_email').val(),
                        "practice_network": $('#practice_network').val()
                    });
                    updatePracticedata(formdata);
                }
            } else {
                $('p.alert_message').text('Please check the email format');
                $('#alert').modal('show');
            }

        } else {
            if ($('#practice_network').val() != '') {
                $('p.alert_message').text('Practice name or practice email is missing');
                $('#alert').modal('show');
            } else {
                $('p.alert_message').text('Please select a network for practice');
                $('#alert').modal('show');
            }
        }
    });
    $('#dontsave_new_practice').on('click', function () {
        $('#back_to_select_practice_btn').trigger('click');
    });
    $('#add_location').on('click', function () {
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
    $('#location_next').on('click', function () {
        var counter = parseInt($('.location_counter').text());
        if (locations[counter]) {
            updateLocationData(counter);
        }

        if (counter < locations.length - 1) {
            $('.location_counter').text(counter + 1);
            popupLocationFields(locations[counter + 1]);

        }
    });
    $('#location_previous').on('click', function () {
        var counter = parseInt($('.location_counter').text());
        if (locations[counter]) {
            updateLocationData(counter);
        }
        if (counter > 0) {
            $('.location_counter').text(counter - 1);
            popupLocationFields(locations[counter - 1]);
        }
    });
    $('.location_input').on('change', function () {
        var index = parseInt($('.location_counter').text());
        var field = $(this).attr('id');
        var value = $(this).val();
        if (locations[index])
            locations[index][field] = value;
    });
    $('#remove_location').on('click', function () {
        var index = parseInt($('.location_counter').text());
        removedLocation.push(locations[index]);
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
    $('.practice_list').on('click', '.search_name', function () {
        var practice_id = $(this).parents('.search_item').attr('data-id');
        showinfo = true;
        var formData = {
            'practice_id': practice_id
        };
        getPracticeInfo(formData);
    });
    $('#openModel').on('click', function () {
        var val = -1;
        $('.editmode').val(val);
        refreshAttributes();

    });
    $('#edit_practice').on('click', function () {
        var val = $(this).attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/-1';
    });
    $('.practice_list').on('click', '.editpractice_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/-1';
    });
    $('.practice_list').on('click', '.removepractice_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');
        var id = [];
        id.push(val);
        showModalConfirmDialog('Are you sure?', function (outcome) {
            if (outcome) {
                removePractice(id);
                $(this).parents('.search_item').remove();
            }
        });
    });
    $('#remove_practice').on('click', function () {
        var val = $('#edit_practice').attr('data-id');
        var id = [];
        id.push(val);
        showModalConfirmDialog('Are you sure?', function (outcome) {
            if (outcome) {
                removePractice(id);
                $('#back').trigger('click');
            }
        });
    });
    $('#refresh_practices').on('click', function () {
        $('#search_practice_input').val('');
        loadAllPractices();
    });
    $('#back_to_select_practice_btn').on('click', function () {
        window.location = "/administration/practices";
    });
    $('#open_practice_form').on('click', function () {
        window.location = '/practices/create';
    });
    $('#checked_all_practice').on('change', function () {
        if ($(this).is(":checked")) {
            $('.practice_search_content').each(function () {
                $(this).find('input').prop('checked', true);
            });
            $('.admin_delete').addClass('active');
            $('.delete_from_row_dropdown').addClass('invisible');
        } else {
            $('.practice_search_content').each(function () {
                $(this).find('input').prop('checked', false);
            });
            $('.admin_delete').removeClass('active');
            $('.delete_from_row_dropdown').removeClass('invisible');
        }
    });
    $('#new_location').on('click', function () {
        var val = $('#edit_practice').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/' + currentPractice.locations.length;
    });
    $('.practice_location_item_list').on('click', '.edit_location_frominfo', function () {
        var index = $(this).parent().parent().attr('data-index');
        var val = $('#edit_practice').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/' + index;
    });
    $('.practice_location_item_list').on('click', '.edit_location_frominfo', function () {
        var index = $(this).parent().parent().attr('data-index');
        var val = $('#edit_practice').attr('data-id');
        window.location = '/administration/practices/edit/' + val + '/' + index;
    });
    $('.practice_location_item_list').on('click', '.remove_location_frominfo', function () {

        var index = $(this).closest('.practice_location_item').attr('data-locationid');
        var location_dom = $(this).closest('.panel-default');
        var formData = {
            'location_id': index
        };
        removeLocation(formData, location_dom);

    });
    $('.practice_search_content').on('change', '.admin_checkbox_row', function () {
        if ($("input[name='checkbox']:checked").length > 0) {
            $('.admin_delete').addClass('active');
            $('.delete_from_row_dropdown').addClass('invisible');
        } else {
            $('.admin_delete').removeClass('active');
            $('.delete_from_row_dropdown').removeClass('invisible');
        }
    });
    $('.admin_delete').on('click', function () {
        showModalConfirmDialogTotal('Are you sure?', function (outcome) {
            if (outcome) {
                getCheckedID();
            }
        });
    });
    $('.practice_list').on('click', '.location_address_previous', function () {
        var index = $(this).parent().attr('data-index');
        var counter = parseInt($(this).parent().find('.location_address_counter').text());
        var totalAddress = locationAddress[index].length;

        if (counter > 0) {
            var address = locationAddress[index][counter - 1].addressline1 + '<br>' + locationAddress[index][counter - 1].city + ',' + locationAddress[index][counter - 1].state + '&nbsp;' + locationAddress[index][counter - 1].zip;
            $(this).parent().parent().find('.location_address').html(address);
            $(this).parent().find('.location_address_counter').text(counter - 1);

        }
    });
    $('.practice_list').on('click', '.location_address_next', function () {
        var index = $(this).parent().attr('data-index');
        var counter = parseInt($(this).parent().find('.location_address_counter').text());
        var totalAddress = locationAddress[index].length;

        if (counter < totalAddress - 1) {
            var address = locationAddress[index][counter + 1].addressline1 + '<br>' + locationAddress[index][counter + 1].city + ',' + locationAddress[index][counter + 1].state + '&nbsp;' + locationAddress[index][counter + 1].zip;
            $(this).parent().parent().find('.location_address').html(address);
            $(this).parent().find('.location_address_counter').text(counter + 1);
        }
    });

    $(document).keypress(function (e) {
        if (e.which == 13) {
            $("#search_practice_button").trigger("click");
        }
    });

    $('#include_deactivated').on('change', function () {
        $("#search_practice_button").trigger("click");
    });
}

var locations = [];
var removedLocation = [];
var currentPractice = [];
var showinfo = true;
var locationAddress = [];

function showModalConfirmDialogTotal(msg, handler) {
    $('.admin_delete_dropdown').on('click', '.confirm_yes', function (evt) {
        handler(true);
    });
    $('.admin_delete_dropdown').on('click', '.confirm_no', function (evt) {
        handler(false);
    });
}

function getCheckedID() {
    var id = [];
    $.each($("input[name='checkbox']:checked"), function () {
        id.push($(this).attr('data-id'));
    });
    removePractice(id);
    var formData = {
        'value': '',
        'include_deactivated': $('#include_deactivated').prop('checked') ? $('#include_deactivated').prop('checked') : false
    };
    getPractices(formData, 0);
    $('.admin_delete').removeClass('active');
}

function getLocationData() {

    if (validateLocation()) {
        if (validateEmail()) {
            locations.push({
                "locationname": $('#locationname').val(),
                "location_code": $('#location_code').val(),
                "email": $('#location_email').val(),
                "addressline1": $('#addressline1').val(),
                "addressline2": $('#addressline2').val(),
                "city": $('#city').val(),
                "state": $('#state').val(),
                "zip": $('#zip').val(),
                "phone": $('#phone').val(),
                "special_instructions": tinyMCE.activeEditor.getContent(),
                "special_instructions_plain_text": tinyMCE.activeEditor.getContent({
                    format: 'text'
                }),
            });
        } else {
            $('p.alert_message').text('Please check the email format');
            $('#alert').modal('show');
        }
    } else {
        $('p.alert_message').text('All fields are required');
        $('#alert').modal('show');
    }


}

function validateLocation() {
    if ($('#locationname').val() == "") return false;
    else if ($('#location_email').val() == "") return false;
    else if ($('#location_code').val() == "") return false;
    else if ($('#addressline1').val() == "") return false;
    else if ($('#city').val() == "") return false;
    else if ($('#state').val() == "") return false;
    else if ($('#zip').val() == "") return false;
    else if ($('#phone').val() == "") return false;
    else return true;
}

function validateEmail() {
    var patt = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    var email = $('#location_email').val().trim();
    if (patt.test(email)) return true;
    else return false;
}

function setNewLocationField() {
    $('#locationname').val('');
    $('#location_email').val('');
    $('#location_code').val('');
    $('#addressline1').val('');
    $('#addressline2').val('');
    $('#city').val('');
    $('#state').val('');
    $('#zip').val('');
    $('#phone').val('');
    tinymce.activeEditor.setContent('');
}

function loadAllPractices() {
    var formData = {
        'value': '',
        'include_deactivated': $('#include_deactivated').prop('checked') ? $('#include_deactivated').prop('checked') : false
    };
    getPractices(formData, 0);
    $('#refresh_practices').removeClass('active');
    $('.no_item_found').removeClass('active');
    if ($('#checked_all_practice').is(":checked")) {
        $('.practice_search_content').each(function () {
            $(this).find('input').prop('checked', true);
        });
    } else
        $('.practice_search_content').each(function () {
            $(this).find('input').prop('checked', false);
        });
}

function getPractices(formData, page) {
    var tojson = JSON.stringify(formData);
    var scheduleimg = $('#schedule_practice_img').val();
    var deleteimage = $('#delete_practice_img').val();
    $('.practice_info').removeClass('active');
    $('.practice_action_header').removeClass('hide');
    var assign_role_image = $('#assign_role_image_path').val();
    var assign_user_image = $('#assign_user_image_path').val();
    var location_previous_icon = $('#location_previous_icon').val();
    var location_next_icon = $('#location_next_icon').val();
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
            locationAddress = [];
            var content = '';
            var i = 0;
            $('#search_results').text('');
            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    locationAddress.push(practice.locations);
                    var totalLocation = practice.locations.length - 1;
                    content += '<div class="row search_item" data-id="' + practice.id + '"><div class="col-xs-3" style="display:inline-flex;"><div><input type="checkbox" class="admin_checkbox_row" data-id="' + practice.id + '" name="checkbox">&nbsp;&nbsp;</div><div class="search_name"><p>' + practice.name + '</p></div></div>';
                    if (totalLocation >= 0) {
                        content += '<div class="col-xs-3 location_address">' + practice.locations[totalLocation].addressline1 + '<br>' + practice.locations[totalLocation].city + ',' + practice.locations[totalLocation].state + ' ' + practice.locations[totalLocation].zip + '</div><div class="col-xs-1 location_counter_toggle" data-index = "' + i + '">';
                    } else {
                        content += '<div class="col-xs-3 location_address"><br></div><div class="col-xs-1 location_counter_toggle" data-index = "' + i + '">';
                        totalLocation = 0;
                    }
                    content += '<img class="location_address_next" src="' + location_next_icon + '">';
                    content += '<span><p class="location_address_counter">' + totalLocation + '</p></span>';
                    content += '<img class="location_address_previous" src="' + location_previous_icon + '">';
                    content += '</div><div class="col-xs-3"><p>' + practice.email + '</p></div>';
                    if (practice.deleted == 'true') {
                        content += '<div class="col-xs-2 search_edit" style="visibility:hidden;">';
                    } else {
                        content += '<div class="col-xs-2 search_edit">';
                    }
                    content += '<p><div class="dropdown hide"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;"></span><ul class="dropdown-menu" id="row_action_dropdown"><li><a href=""><img src="' + assign_role_image + '" class="assign_role_image" style="width:20px">Assign Roles</a></li><li><a href=""><img src="' + assign_user_image + '" class="assign_user_image" style="width:20px">Assign Users</a></li></ul></div></p>&nbsp;&nbsp;<p class="editpractice_from_row arial_bold" data-toggle="modal" data-target="#create_practice">Edit</p><div class="dropdown delete_from_row_dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepractice_from_row"><img src="' + deleteimage + '" alt="" class="removepractice_img" data-toggle="tooltip" title="Delete Practice" data-placement="bottom"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete this?</strong></p></li><li class="confirm_buttons"><button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button"  class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                    i++;
                });

                $('.practice_list').addClass('active');
                $('.practice_search_content').html(content);
                $('[data-toggle="tooltip"]').tooltip();
                if ($('#checked_all_practice').is(":checked")) {
                    $('.practice_search_content').each(function () {
                        $(this).find('input').prop('checked', true);
                    });
                } else
                    $('.practice_search_content').each(function () {
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
            $('p.alert_message').text('Error creating practices');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function popupLocationFields(locationdata) {
    $('#locationname').val(locationdata['locationname']);
    $('#location_code').val(locationdata['location_code']);
    $('#location_email').val(locationdata['email']);
    $('#addressline1').val(locationdata['addressline1']);
    $('#addressline2').val(locationdata['addressline2']);
    $('#city').val(locationdata['city']);
    $('#state').val(locationdata['state']);
    $('#zip').val(locationdata['zip']);
    $('#phone').val(locationdata['phone']);
    if (locationdata['special_instructions']) {
        tinymce.activeEditor.setContent(locationdata['special_instructions']);
    } else {
        tinymce.activeEditor.setContent('');
    }
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
        success: function (e) {
            var info = $.parseJSON(e);
            currentPractice = info;
            if (showinfo)
                showPracticeInfo(info);
        },
        error: function () {
            $('p.alert_message').text('Error getting practice information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function showPracticeInfo(info) {
    var deleteImg = $('#delete_practice_img').val();
    $('#edit_practice').attr('data-id', info.practice_id);
    $('#the_practice_name').text(info.practice_name);
    var deactivate_user_img = $('#deactivate_user_img').val();
    var content = '';
    if (info.locations.length > $('#provider_location_display_limit').val()) {
        var i = 0;
        content += '<div class="panel-group accordian_margin location_accordian" id="accordion">';
        info.locations.forEach(function (location) {
            content += '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse' + i + '">' + location.locationname + '</a></h4></div>';
            if (i == 0)
                content += '<div id="collapse' + i + '" class="panel-collapse collapse in"><div class="panel-body location_accordian_body">';
            else
                content += '<div id="collapse' + i + '" class="panel-collapse collapse"><div class="panel-body">';
            var locationEmail = (location.email == null) ? 'Notification Email Not Set' : location.email;
            content += '<div class="row practice_location_item" data-locationid = "' + location.id + '" data-index="' + i + '"><div class="col-xs-3 practice_info"><p>' + locationEmail + '</p><p>' + location.addressline1 + '<br>' + location.city + ',' + location.state + '&nbsp;  ' + location.zip + '<br>' + location.phone + '</p></div><div class="col-xs-4 practice_assign"><p class="hide">Assign roles </p><p class="hide">Assign users</p><p class="edit_location_frominfo">Edit</p><br><center class=""><span class="remove_location_frominfo"><img src="' + deleteImg + '"/></span></center></div><div class="col-xs-5"><div class="row">';
            info.users.forEach(function (user) {
                content += '<div class="col-xs-12 practice_users "><p style="width: 100%;"><span>' + user.firstname + ' ' + user.lastname + '</span><img src="' + deactivate_user_img + '" class="user_disable" data-id="' + user.id + '" data-toggle="tooltip" title="Disable User" data-placement="top"/></p></div>';
            });
            content += '</div></div></div>';
            content += '</div></div></div>';
            i++;
        });
        content += '</div>';
        $('.practice_location_item_list').html(content);
        $('[data-toggle="tooltip"]').tooltip();
        $('.practice_list').removeClass('active');
        $('.practice_info').addClass('active');
        $('.practice_action_header').addClass('hide');
    } else if (info.locations.length > 0 && info.locations.length <= $('#provider_location_display_limit').val()) {
        var i = 0;
        content += '<div class="panel-group accordian_margin location_accordian" id="accordion">';
        info.locations.forEach(function (location) {
            content += '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse' + i + '">' + location.locationname + '</a></h4></div>';
            if (i == 0)
                content += '<div id="collapse' + i + '" class=""><div class="panel-body location_accordian_body">';
            else
                content += '<div id="collapse' + i + '" class=""><div class="panel-body">';
            var locationEmail = (location.email == null) ? 'Notification Email Not Set' : location.email;
            content += '<div class="row practice_location_item" data-locationid = "' + location.id + '" data-index="' + i + '"><div class="col-xs-3 practice_info"><p>' + locationEmail + '</p><p>' + location.addressline1 + '<br>' + location.city + ',' + location.state + '&nbsp;  ' + location.zip + '<br>' + location.phone + '</p></div><div class="col-xs-4 practice_assign"><p class="hide">Assign roles </p><p class="hide">Assign users</p><p class="edit_location_frominfo">Edit</p><br><center class=""><span class="remove_location_frominfo"><img src="' + deleteImg + '"/></span></center></div><div class="col-xs-5"><div class="row">';
            info.users.forEach(function (user) {
                content += '<div class="col-xs-12 practice_users "><p style="width: 100%;"><span>' + user.firstname + ' ' + user.lastname + '</span><img src="' + deactivate_user_img + '" class="user_disable" data-id="' + user.id + '" data-toggle="tooltip" title="Disable User" data-placement="top"/></p></div>';
            });
            content += '</div></div></div>';
            content += '</div></div></div>';
            i++;
        });
        content += '</div>';
        $('.practice_location_item_list').html(content);
        $('[data-toggle="tooltip"]').tooltip();
        $('.practice_list').removeClass('active');
        $('.practice_info').addClass('active');
        $('.practice_action_header').addClass('hide');
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
        type: 'POST',
        data: $.param({
            data: tojson
        }),
        async: false,
        success: function success(e) {
            window.location = "/administration/practices";
        },
        error: function error() {
            $('p.alert_message').text('Error saving info');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function removePractice(id) {
    var removeId = {};
    var i = 0;
    id.forEach(function (item) {
        removeId[i] = item;
        i++;
    });
    $.ajax({
        url: '/practices/remove',
        type: 'GET',
        data: $.param(removeId),
        contentType: 'text/html',
        async: false,
        success: function (e) {},
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    $('.practice_search_content').each(function () {
        $(this).find('input').prop('checked', false);
    });
    $('#checked_all_practice').prop('checked', false);
    loadAllPractices();
}

function updateLocationData(index) {
    locations[index]['locationname'] = $('#locationname').val();
    locations[index]['location_code'] = $('#location_code').val();
    locations[index]['email'] = $('#location_email').val();
    locations[index]['addressline1'] = $('#addressline1').val();
    locations[index]['addressline2'] = $('#addressline2').val();
    locations[index]['city'] = $('#city').val();
    locations[index]['state'] = $('#state').val();
    locations[index]['zip'] = $('#zip').val();
    locations[index]['phone'] = $('#phone').val();
    locations[index]['special_instructions'] = tinyMCE.activeEditor.getContent();
    locations[index]['special_instructions_plain_text'] = tinyMCE.activeEditor.getContent({
        format: 'text'
    });
}

function removeLocation(formData, location_dom) {
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
    $('#practice_listing').on('click', '.confirm_yes', function (evt) {
        handler(true);
    });
    $('#practice_listing').on('click', '.confirm_no', function (evt) {
        handler(false);
    });

}

function removeUser(id) {
    var removeId = {};
    var i = 0;
    id.forEach(function (item) {
        removeId[i] = item;
        i++;
    });
    $.ajax({
        url: '/users/remove',
        type: 'GET',
        data: $.param(removeId),
        contentType: 'text/html',
        async: false,
        success: function (e) {},
        error: function error() {
            $('p.alert_message').text('Error:');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    var formData = {
        'practice_id': $('#edit_practice').attr('data-id')
    };
    $('.practice_location_item_list').html('');
    getPracticeInfo(formData);
}
