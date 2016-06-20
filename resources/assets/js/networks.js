'use strict';

$(document).ready(function () {
    loadAllNetworks();

    $('.popover_text').popover({
        trigger: "manual"
    });

    $('.save_network_button').on('click', function () {
        checkForm();
    });

    $("#form_add_networks").submit(function (event) {
        if(!checkForm())
            event.preventDefault();
    });
    $('#search_network_button').on('click', function () {
        var searchvalue = $('#search_network_input').val();
        $('.no_item_found > p:eq(1)').text(searchvalue);
        $('.no_item_found > p:eq(1)').css('padding-left', '4em');
        $('.no_item_found').removeClass('active');
        if (searchvalue != '') {
            var formData = {
                'value': searchvalue
            };
            getNetworks(formData, 0);
            $('#refresh_networks').addClass('active');
        } else {
            $('#refresh_networks').removeClass('active');
            loadAllNetworks();
        }
    });
    $('.network_listing').on('click', '.editnetwork_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');
        window.location = '/networks/edit/' + val + '';
    });
    $('.network_listing').on('click', '.removenetwork_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');
        showModalConfirmDialog('Are you sure?', function (outcome) {
            if (outcome) {
                removeNetwork(val);
                $(this).parents('.search_item').remove();
            }
        });
    });
    $('#dontsave_network').on('click', function () {
        $('.back-btn').trigger('click');
    });
    $('#checked_all_networks').on('change', function () {
        if ($(this).is(":checked")) {
            $('.network_search_content').each(function () {
                $(this).find('input').prop('checked', true);
            });
        } else
            $('.network_search_content').each(function () {
                $(this).find('input').prop('checked', false);
            });
    });
    $('#refresh_networks').on('click', function () {
        $('#search_network_input').val('');
        loadAllNetworks();
    });
    $('.network_search_content').on('mouseenter', '.action_dropdown', function () {
        $(this).attr('src', $('#dropdown_onhover_img').val());
    });

    $('.network_search_content').on('mouseleave', '.action_dropdown', function () {
        $(this).attr('src', $('#dropdown_natural_img').val());
    });


    $(document).keypress(function (e) {
        if (e.which == 13) {
            $("#search_network_button").trigger("click");
        }
    });
});
var flag = 0;
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

function getNetworks(formData, page) {
    var tojson = JSON.stringify(formData);
    var deleteimg = $('#delete_network_img').val();
    var scheduleimg = $('#dropdown_natural_img').val();
    var assign_role_image = $('#assign_role_image_path').val();
    var assign_user_image = $('#assign_user_image_path').val();
    $.ajax({
        url: '/networks/search?page=' + page,
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var networks = $.parseJSON(e);
            var content = '';
            $('#search_results').text('');
            if (networks.length > 0) {
                networks.forEach(function (network) {
                    content += '<div class="row search_item" data-id="' + network.id + '"><div class="col-xs-3 search_name"><input type="checkbox">&nbsp;&nbsp;<p>' + network.name + '</p></div>';
                    if (network.email === '-' && network.phone === '-')
                        content += '<div class="col-xs-3">' + network.email + '</div>';
                    else
                        content += '<div class="col-xs-3">' + network.email + '<br>' + network.phone + '</div>';

                    if (network.addressline1 === '-' && network.addressline2 === '-')
                        content += '<div class="col-xs-1"></div><div class="col-xs-3"><p>' + network.addressline1 + '</p></div>';
                    else
                        content += '<div class="col-xs-1"></div><div class="col-xs-3"><p>' + network.addressline1 + '<br>' + network.addressline2 + '</p></div>';

                    content += '<div class="col-xs-2 search_edit"><p><div class="dropdown dropdown_action hide"><span  area-hidden="true" data-toggle="dropdown" class="dropdown-toggle"><img class="action_dropdown" src="' + scheduleimg + '" alt=""></span><ul class="dropdown-menu" id="row_action_dropdown"><li><a href=""><img src="' + assign_role_image + '" class="assign_role_image" style="width:20px">Assign Roles</a></li><li><a href=""><img src="' + assign_user_image + '" class="assign_user_image" style="width:20px">Assign Users</a></li></ul></div></p><p class="editnetwork_from_row arial_bold">Edit</p><div class="dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removenetwork_from_row"><img src="' + deleteimg + '" alt="" class="removenetwork_img" data-toggle="tooltip" title="Delete Network" data-placement="bottom"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete this?</strong></p></li><li class="confirm_buttons"><button type="button"  class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button"  class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                });
                $('.network_search_content').html(content);
                $('.network_listing').addClass('active');
                $('[data-toggle="tooltip"]').tooltip();
                if ($('#checked_all_networks').is(":checked")) {
                    $('.network_search_content').each(function () {
                        $(this).find('input').prop('checked', true);
                    });
                }
            } else {
                $('.network_listing').removeClass('active');
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

function loadAllNetworks() {
    var formData = {
        'value': ''
    };
    getNetworks(formData, 0);
    $('#refresh_networks').removeClass('active');
    $('.no_item_found').removeClass('active');
}

function showModalConfirmDialog(msg, handler) {
    $('#network_listing').on('click', '.confirm_yes', function (evt) {
        handler(true);
    });
    $('#network_listing').on('click', '.confirm_no', function (evt) {
        handler(false);
    });

}

function removeNetwork(id) {
    $.ajax({
        url: '/networks/destroy/' + id,
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            getNetworks(null, currentpage);

        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function checkForm() {
    $('.network_email_field').val($('.network_email_field').val().trim());
    var fields = $('.panel-body').find('.add_network_input');
    var patt = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    fields.each(function (field) {
        if ($(this).prop('required')) {
            if ($(this).val() == "") {
                $($(this).parents('.panel-default').find('.popover_text')).attr('data-content', 'Please fill all the required fields');
                $($(this).parents('.panel-default').find('.popover_text')).popover("show");
                flag = 1;
                return false;
            }
        }
        if ($(this).hasClass('network_email_field') && $(this).val() != "" && !patt.test($(this).val())) {
            $($(this).parents('.panel-default').find('.popover_text')).attr('data-content', 'Please enter the email in correct format');
            $($(this).parents('.panel-default').find('.popover_text')).popover("show");
            flag = 1;
            return false;
        }
    });
    if(flag == 0)
        return true;
}
