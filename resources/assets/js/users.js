'use strict';
$(document).ready(function() {
    loadAllUsers();

    $('.profile_img_upload').on('change',function () {
        
    });

    $('#search_user_button').on('click', function() {
        var searchvalue = $('#search_user_input').val();
        $('.no_item_found > p:eq(1)').text(searchvalue);
        $('.no_item_found > p:eq(1)').css('padding-left', '4em');
        $('.no_item_found').removeClass('active');
        var formData = {
            'value': searchvalue
        };
        getUsers(formData, 0);
        $('#refresh_users').addClass('active');
    });
    $('.p_left').on('click', function() {
        if (currentpage > 1)
            getUsers(null, currentpage - 1);
    });
    $('.p_right').on('click', function() {
        if (currentpage < lastpage)
            getUsers(null, currentpage + 1);
    });
    $('#refresh_users').on('click', function() {
        $('#search_user_input').val('');
        loadAllUsers();
    });
    $('.user_search_content').on('mouseenter', '.action_dropdown', function() {
        $(this).attr('src', $('#dropdown_onhover_img').val());
    });
    $('.user_search_content').on('mouseleave', '.action_dropdown', function() {
        $(this).attr('src', $('#dropdown_natural_img').val());
    });
    $('#checked_all_users').on('change', function() {
        if ($(this).is(":checked")) {
            $('.user_search_content').each(function() {
                $(this).find('input').prop('checked', true);
            });
        } else
            $('.user_search_content').each(function() {
                $(this).find('input').prop('checked', false);
            });
    });
    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#search_user_button").trigger("click");
        }
    });
    $('.user_search_content').on('click', 'p.edituser_from_row', function(){
        var user_id = $(this).parents('.search_item').attr('data-id');
        window.location = '/administration/users/edit/' + user_id + '';
    });

});
var currentpage = 1;
var lastpage = 0;

function loadAllUsers() {
    var formData = {
        'value': ''
    };
    getUsers(formData, 0);
    $('#refresh_users').removeClass('active');
    $('.no_item_found').removeClass('active');
}

function getUsers(formData, page) {
    var tojson = JSON.stringify(formData);
    var activate_img = $('#active_user_img').val();
    var scheduleimg = $('#dropdown_natural_img').val();
    var assign_role_image = $('#assign_role_image_path').val();
    var assign_user_image = $('#assign_user_image_path').val();
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
            if (users.length > 0 && users[0]['total'] > 0) {
                users.forEach(function(user) {
		content += '<div class="row search_item" data-id="' + user.id + '"><div class="col-xs-3 search_name"><input type="checkbox">&nbsp;&nbsp;<p>' + user.name + '</p></div><div class="col-xs-3">' + user.email + '</div><div class="col-xs-1"></div><div class="col-xs-3"><p>' + user.practice + '</p></div><div class="col-xs-2 search_edit"><p><div class="dropdown dropdown_action"><span  area-hidden="true" data-toggle="dropdown" class="dropdown-toggle"><img class="action_dropdown" src="' + scheduleimg + '" alt=""></span><ul class="dropdown-menu" id="row_action_dropdown"><li><a href="" style="margin-left: -2.1em;"><img src="' + assign_role_image + '" class="assign_role_image" style="width:20px">Permissions</a></li><li><a href=""><img src="' + assign_user_image + '" class="assign_user_image" style="width:20px">Impersonate User</a></li></ul></div></p><p class="edituser_from_row">Edit</p><div class="dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removeuser_from_row"><img src="' + activate_img + '" alt="" class="removeuser_img"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to deactivate this person?</strong></p></li><li class="confirm_buttons"><button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button"  class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                });
                currentpage = users[0]['currentPage'];
                lastpage = users[0]['lastpage'];
                var result = currentpage * 5;
                if (result > users[0]['total'])
                    result = users[0]['total'];
                $('.page_info').text(result + ' of ' + users[0]['total']);
                $('.user_search_content').html(content);
                $('.user_listing').addClass('active');
                if ($('#checked_all_users').is(":checked")) {
                    $('.user_search_content').each(function() {
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
