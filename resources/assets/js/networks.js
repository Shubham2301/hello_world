'use strict';

$(document).ready(function() {
    loadAllNetworks();
    $('#search_network_button').on('click', function() {
        var searchvalue = $('#search_network_input').val();
        var formData = {
            'value': searchvalue
        };
        getNetworks(formData);
    });
    $('.network_listing').on('click','.editnetwork_from_row',function(){
        var val = $(this).parents('.search_item').attr('data-id');
        window.location = '/networks/edit/' + val + '';
    });
    $('.network_listing').on('click','.removenetwork_from_row',function(){
        var val = $(this).parents('.search_item').attr('data-id');
        showModalConfirmDialog('Are you sure?', function(outcome) {
            if (outcome) {
                removeNetwork(val);
                $(this).parents('.search_item').remove();
            }
        });
    });
    $('#dontsave_network').on('click', function() {
        $('.back-btn').trigger('click');
    });
    $('#checked_all_networks').on('change',function(){
        if ($(this).is(":checked")) {
            $('.network_search_content').each(function() {
                $(this).find('input').prop('checked', true);
            });
        } else
            $('.network_search_content').each(function() {
                $(this).find('input').prop('checked', false);
            });
    })
});

function getNetworks(formData) {
    var tojson = JSON.stringify(formData);
    var deleteimg = $('#delete_network_img').val();
    var scheduleimg = $('#schedule_network_img').val();
    var assign_role_image = $('#assign_role_image_path').val();
    var assign_user_image = $('#assign_user_image_path').val();
    $.ajax({
        url: '/networks/search',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var networks = $.parseJSON(e);
            var content = '';
            $('#search_results').text(networks[0]['total'] + ' Results found');
            if (networks.length > 0) {
                networks.forEach(function(network) {
        content += '<div class="row search_item" data-id="' + network.id + '"><div class="col-xs-3 search_name"><input type="checkbox">&nbsp;&nbsp;<p>' + network.name + '</p></div><div class="col-xs-3">' + network.email + '<br>' + network.phone + '</div><div class="col-xs-1"></div><div class="col-xs-3"><p>' + network.addressline1 + '<br>' + network.addressline2 + '</p></div><div class="col-xs-2 search_edit"><p><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;"></span><ul class="dropdown-menu" id="row_action_dropdown"><li><a href=""><img src="'+assign_role_image +'" class="assign_role_image" style="width:20px">Assign Roles</a></li><li><a href=""><img src="'+assign_user_image+'" class="assign_user_image" style="width:20px">Assign Users</a></li></ul></div></p>&nbsp;&nbsp;<p class="editnetwork_from_row">Edit</p><div class="dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removenetwork_from_row"><img src="'+deleteimg+'" alt="" class="removenetwork_img"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete this?</strong></p></li><li class="confirm_buttons"><button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button"  class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                });
                $('.page_info').text(networks[0]['currentPage'] + ' of ' + networks[0]['lastpage']);
                $('.network_search_content').html(content);
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
function loadAllNetworks() {
    var formData = {
        'value': ''
    };
    getNetworks(formData);
}
function showModalConfirmDialog(msg, handler) {
    $('#network_listing').on('click', '.confirm_yes', function(evt) {
        handler(true);
    });
    $('#network_listing').on('click', '.confirm_no', function(evt) {
        handler(false);
    });

}
function removeNetwork(id) {
    $.ajax({
        url: '/networks/destroy/'+id,
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function success(e) {


},
    error: function error() {
        $('p.alert_message').text('Error searching');
        $('#alert').modal('show');
    },
        cache: false,
            processData: false
});
}
