$(document).ready(function () {

    printList();

    $('#referraltypes_list').on('change', '#referr_list', function () {
        $('#add_referral_name').text($('#referr_list :selected').text());
    });

    $('#referraltypes_list').on('click', '#edit_tiles', function () {
        if ($('#referraltypes_list').hasClass('edit')) {
            $('#referraltypes_list').removeClass('edit');
            $('#referraltypes_list').removeClass('add');
            $('p.message').text('Select type of patient you are referring');
            printList();
        } else {
            $('#referraltypes_list').addClass('edit');
            $('p.message').text('Edit referral tiles');
            printList();
        }
    });
    $('#referraltypes_list').on('click', '#add_tiles', function () {
        if ($('#referraltypes_list').hasClass('add')) {

        } else {
            $('#referraltypes_list').addClass('add');
            printList();
            $('#add_referral_name').text($('#referr_list :selected').text());
        }
    });

    $('#referraltypes_list').on('click', '.add_referral_type', function () {
        var id = $('#referr_list').find(":selected").val();
        var display_name = $('#referr_list').find(":selected").attr('data-name');
        var name = $('#referr_list').find(":selected").text();
        addReferralType(id, display_name, name);
    });
    $('#referraltypes_list').on('click', '.remove_add_referral_type', function () {
        $('#referraltypes_list').removeClass('add');
        printList();
    });
    $('#referraltypes_list').on('click', '.remove_referral_type', function () {
        var id = $(this).attr('data-id');
        removeReferralType(id);
    });
    $('#referraltypes_list').on('click', '.referr_patient', function () {
        var id = $(this).attr('data-id');
        selectPatient(id);
    });
});

function printList() {

    $('#referraltypes_list').html('');

    var content = "";

    var info = getReferralTypeList();

    info[0].forEach(function (referraltype) {
        content += '<div class="referral_type"><span class="glyphicon glyphicon-remove-circle remove_referral_type" data-id="' + referraltype.id + '"></span><div class="tile" id="openModel" data-toggle="modal" data-target="#' + referraltype.id + '" style="color:' + referraltype.color_indicator + '"><center><span class="tile_bar" style="background-color:' + referraltype.description + '"></span><br><p class="tile_name">' + referraltype.name + '</p></center></div>' + referraltype.display_name + '</div><div id="' + referraltype.id + '" class="modal fade" role="dialog" data-id="' + referraltype.id + '"><div class="modal-dialog modal-style" style="color:#000;padding:50px;margin-top:35vh;background-color:#f2f2f2;">' + referraltype.clinical_protocol + '<div class="modal-footer" style="text-align:center;"><button type="button" class="btn btn-primary referr_patient" data-id="' + referraltype.id + '">Confirm</button><button type="button" class="btn dismiss_button" data-dismiss="modal">Cancel</button></div></div></div>';
    });

    if ($('#referraltypes_list').hasClass('edit')) {

        if ($('#referraltypes_list').hasClass('add')) {
            content += '<div class="referral_type"><span class="glyphicon glyphicon-ok-circle add_referral_type"></span><span class="glyphicon glyphicon-remove-circle remove_add_referral_type" style="margin-left:1.4em;"></span><div class="tile"><center><span class="tile_bar"></span><br><p class="tile_name" id="add_referral_name"></p></center></div><select id="referr_list" class="referral_type_list" name="referr_list">';
            info[1].forEach(function (referraltype) {
                content += '<option value="' + referraltype.id + '" data-name="' + referraltype.display_name + '">' + referraltype.name + '</option>';
            });
            content += '</select></div>';

        } else {
            content += '<div class="referral_type"><span class="glyphicon glyphicon-remove-circle remove_referral_type" style="visibility:hidden;"></span><div class="tile add_tile" id="add_tiles"><center><p class="referral_edit"><span class=" glyphicon glyphicon-plus-sign" aria-hidden="true"></span></p></center></div></div>';
            content += '<div class="referral_type"><span class="glyphicon glyphicon-remove-circle remove_referral_type" style="visibility:hidden;"></span><div class="tile configuration_tile" id="edit_tiles"><center><p class="referral_edit"><span class=" glyphicon glyphicon-ok" aria-hidden="true"></span></p></center></div></div>';
        }

        $('#referraltypes_list').html(content);

        if ($('#referraltypes_list').hasClass('add')) {
            $('.remove_referral_type').removeClass('edit');
        } else {
            $('.remove_referral_type').addClass('edit');
        }
    } else {
        if( info['user_level'] === '1'){
            content += '';
        } else {
            content += '<div class="referral_type"><span class="glyphicon glyphicon-remove-circle remove_referral_type" style="visibility:hidden;"></span><div class="tile configuration_tile" id="edit_tiles"><center><p class="referral_edit"><span class=" glyphicon glyphicon-pencil" aria-hidden="true"></span></p></center></div></div>';
        }

        $('#referraltypes_list').html(content);

        $('.remove_referral_type').removeClass('edit');
    }
}

function getReferralTypeList() {

    var info = "";

    $.ajax({
        url: '/home/getreferrallist',
        type: 'GET',
        contentType: 'text/html',
        async: false,
        success: function (e) {
            info = $.parseJSON(e);
        },
        error: function () {
            $('p.alert_message').text('Error getting patient information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    return (info);

}

function selectPatient(id) {

    $('#form_referraltype_id').val(id);
    $('#form_select_patient').submit();

}

function removeReferralType(id) {

    var formData = {
        id: id
    };

    $.ajax({
        url: '/home/removereferral',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            printList();
        },
        error: function () {
            $('p.alert_message').text('Error removing');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

// TODO: function to send AJAX call and add another tile. 
function addReferralType(id, display_name, name) {

    var formData = {
        id: id
    };

    $.ajax({
        url: '/home/addreferral',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            $('#referraltypes_list').removeClass('add');
            printList();
        },
        error: function () {
            $('p.alert_message').text('Error removing');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
