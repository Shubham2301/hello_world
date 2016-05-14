'use strict';
$(document).ready(function () {
    if(window.location.pathname != '/editprofile') {
        loadAllUsers();
        getLandingPageByRole();
    }

    $('.profile_img_upload').on('change', function () {
        if ($(this).val() != '')
            changePicture();
    });

    $('.popover_text').popover({
        trigger: "manual"
    });

    $('.add_user_submit_button').on('click', function () {
        checkForm();
    });

    $('#user_level').on('change', function () {
        if ($(this).val() == 3) {
            $('#user_practice').show();
            $('#user_practice').prop('required', true);
        } else {
            $('#user_practice').hide();
            $('#user_practice').prop('required', false);
        }
        if ($(this).val() == 1) {
            $('#user_network').hide();
            $('#user_network').prop('required', false);
        } else {
            $('#user_network').show();
            $('#user_network').prop('required', true);

        }
    });

    $('#search_user_button').on('click', function () {
        var searchvalue = $('#search_user_input').val();
        $('.no_item_found > p:eq(1)').text(searchvalue);
        $('.no_item_found > p:eq(1)').css('padding-left', '4em');
        $('.no_item_found').removeClass('active');
        if (searchvalue != '') {
            var formData = {
                'value': searchvalue
            };
            getUsers(formData, 0);
            $('#refresh_users').addClass('active');
        } else {
            $('#refresh_users').removeClass('active');
            loadAllUsers();
        }
    });
    $('#refresh_users').on('click', function () {
        $('#search_user_input').val('');
        loadAllUsers();
    });
    $('.user_search_content').on('mouseenter', '.action_dropdown', function () {
        $(this).attr('src', $('#dropdown_onhover_img').val());
    });
    $('.user_search_content').on('mouseleave', '.action_dropdown', function () {
        $(this).attr('src', $('#dropdown_natural_img').val());
    });
    $('#checked_all_users').on('change', function () {
        if ($(this).is(":checked")) {
            $('.user_search_content').each(function () {
                $(this).find('input').prop('checked', true);
            });
            $('.admin_delete').addClass('active');
            $('.delete_from_row_dropdown').addClass('invisible');
        } else {
            $('.user_search_content').each(function () {
                $(this).find('input').prop('checked', false);
            });
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
	$('#care-console').on('change', function(){

		if($('#care-console').prop('checked')){
			var content = '<option value="'+landingPage['care-console'][0]+'" id="care-console_page">'+landingPage['care-console'][1]+'</option>';
			$('#landing_page').append(content);
		}
		else{

			$("#landing_page>option[value='"+6+"']").remove();
		}

	});
    $(document).keypress(function (e) {
        if (e.which == 13) {
            $("#search_user_button").trigger("click");
        }
    });
    $('.user_search_content').on('click', 'p.edituser_from_row', function () {
        var user_id = $(this).parents('.search_item').attr('data-id');
        window.location = '/administration/users/edit/' + user_id + '';
    });
    $('.user_search_content').on('change', '.admin_checkbox_row', function () {
        if ($("input[name='checkbox']:checked").length > 0) {
            $('.admin_delete').addClass('active');
            $('.delete_from_row_dropdown').addClass('invisible');
        } else {
            $('.admin_delete').removeClass('active');
            $('.delete_from_row_dropdown').removeClass('invisible');
        }
    });
    $('.user_listing').on('click', '.removeuser_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');
        var id = [];
        id.push(val);
        showModalConfirmDialog('Are you sure?', function (outcome) {
            if (outcome) {
                removeUser(id);
                $(this).parents('.search_item').remove();
            }
        });
    });
	$('.user_listing').on('click', '.user_row_name', function(){
		var id = $(this).closest('.search_item').attr('data-id');
		showUserInfo(id)
	});
	$('.user_info').on('click','.user_back',function(){
		$('.user_admin_index_header').removeClass('hide');
		$('.user_listing').addClass('active');
		$('.no_item_found').removeClass('active');
		$('.user_info').removeClass('active');
	});


});

$(document).click(function () {
    if (flag == 0) {
        $('.popover_text').popover("hide");
    }
    flag = 0;
});
$(document).keypress(function (e) {
    if (flag == 0) {
        $('.popover_text').popover("hide");
    }
    flag = 0;
});
var flag = 0;
var landingPage = [];
function getCheckedID() {
    var id = [];
    $.each($("input[name='checkbox']:checked"), function () {
        id.push($(this).attr('data-id'));
    });
    removeUser(id);
    $('.admin_delete').removeClass('active');
}

function loadAllUsers() {
    var formData = {
        'value': ''
    };
    getUsers(formData, 0);
    $('#refresh_users').removeClass('active');
    $('.no_item_found').removeClass('active');
}

function showModalConfirmDialog(msg, handler) {
    $('.user_listing').on('click', '.confirm_yes', function (evt) {
        handler(true);
    });
    $('.user_listing').on('click', '.confirm_no', function (evt) {
        handler(false);
    });

}

function showModalConfirmDialogTotal(msg, handler) {
    $('.admin_delete_dropdown').on('click', '.confirm_yes', function (evt) {
        handler(true);
    });
    $('.admin_delete_dropdown').on('click', '.confirm_no', function (evt) {
        handler(false);
    });

}

function getUsers(formData, page) {
    var tojson = JSON.stringify(formData);
    var activate_img = $('#active_user_img').val();
    var scheduleimg = $('#dropdown_natural_img').val();
    var assign_role_image = $('#assign_role_image_path').val();
    var assign_user_image = $('#assign_user_image_path').val();
	$('.user_admin_index_header').removeClass('hide');
    $.ajax({
        url: '/users/search?page=' + page,
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var users = $.parseJSON(e);
            var content = '';
            $('#search_results').text('');
            if (users.length > 0) {
                users.forEach(function (user) {
                    content += '<div class="row search_item" data-id="' + user.id + '"><div class="col-xs-3 search_name"><input type="checkbox" class="admin_checkbox_row" data-id="' + user.id + '" name="checkbox">&nbsp;&nbsp;<p class="user_row_name">' + user.name + '</p></div><div class="col-xs-3">' + user.email + '</div><div class="col-xs-2"><p>' + user.level + '</p></div><div class="col-xs-2"><p>' + user.practice + '</p></div><div class="col-xs-2 search_edit">';
                    //                  content += '<li><a href="" style="margin-left: -5.1em;"><img src="' + assign_role_image + '" class="assign_role_image" style="width:20px;">&nbsp;Roles</a></li>';
                    content += '<p class="edituser_from_row arial_bold">Edit</p><div class="dropdown delete_from_row_dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row"><img src="' + activate_img + '" alt="" class="removeuser_img" data-toggle="tooltip" title="Deactivate User" data-placement="bottom"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to deactivate this person?</strong></p></li><li class="confirm_buttons"><button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button"  class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                });
                $('.user_search_content').html(content);
                $('.user_listing').addClass('active');
                $('[data-toggle="tooltip"]').tooltip();
                if ($('#checked_all_users').is(":checked")) {
                    $('.user_search_content').each(function () {
                        $(this).find('input').prop('checked', true);
                    });
                }

            } else {
                $('.user_listing').removeClass('active');
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
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    $('.user_search_content').each(function () {
        $(this).find('input').prop('checked', false);
    });
    $('#checked_all_users').prop('checked', false);
    loadAllUsers();
}

function changePicture() {
    var myform = document.getElementById("profile_form");
    var fd = new FormData(myform);
    $.ajax({
        url: "/updateprofile",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (e) {
            $('#profile_image_view').attr('src', e);
        }
    });
}

function checkForm() {
    var fields = $('.panel-body').find('.add_user_input');
    fields.each(function (field) {
        if ($(this).prop('required')) {
            if ($(this).val() == "") {
                $($(this).parents('.panel-default').find('.popover_text')).popover("show");
                flag = 1;
                return false;
            }
        }
    });
}

function showUserInfo(id){
	$.ajax({
		url: '/users/show/'+id,
		type: 'GET',
		contentType: 'text/html',
		async: false,
		success: function (e) {
		$('.user_admin_index_header').addClass('hide');
		$('.user_listing').removeClass('active');
		$('.no_item_found').removeClass('active');
		$('.user_info').addClass('active');
		$('.user_info').html(e);
	},
		   error: function error() {
		$('p.alert_message').text('Error searching');
		$('#alert').modal('show');
	},
		cache: false,
			processData: false
});

}

function getLandingPageByRole(){
	$.ajax ({
		url: '/getlandingpages',
		cache: false,
		processData: false,
		contentType: false,
		type: 'GET',
		success: function (e) {
		landingPage =$.parseJSON(e);
		}
	});
}
