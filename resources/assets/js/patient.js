$(document).ready(function () {

    if ($('#from_admin').val())
        loadAllPatients();
    loadImportForm();

    var patientID = location.search.split('patient_id=')[1];
    if (patientID) {
        var formData = {
            'id': patientID
        };
        getPatientInfo(formData);
    }

    $("#form_add_patients").submit(function (event) {
        if (!checkForm())
            event.preventDefault();
    });

    $('#birthdate').datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });

    $('#subscriber_birthdate').datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
    });

    $('.popover_text').popover({
        trigger: "manual"
    });

    $('.dropdown-menu li').click(function () {
        $('#search_patient_input_type').text($(this).text());
        $('#search_patient_input_type').attr('value', $(this).attr('value'));
    });
    $('.save_patient_button').on('click', function () {
        checkForm();
    });
    $('#search_patient_button').on('click', function () {

        if ($('#from_admin').val()) {
            var val = $('#search_patient_input').val();
            $('.no_item_found > p:eq(1)').text(val);
            $('.no_item_found > p:eq(1)').css('padding-left', '4em');
            $('.no_item_found').removeClass('active');
            if (val != '') {
                var searchdata = [];
                searchType = 'all';
                searchValue = val;
                searchdata.push({
                    "type": searchType,
                    "value": searchValue,
                });
                showpage = 1;
                getPatients(searchdata, 0);
                $('#refresh_patients').addClass('active');
            } else {
                $('#refresh_patients').removeClass('active');
                loadAllPatients();
            }
        } else {
            $("#add_search_option").trigger("click");
            $('#search_patient_input').val('');
            var searchdata = getsearchtype();
            if (searchdata.length != 0) {
                showpage = 1;
                getPatients(searchdata, 0);

            } else {
                $('#search_patient_input').focus();
                $('.patient_list').removeClass('active');
                $('.search_filter').removeClass('active');
            }
        }
    });
    $('#add_patient_form').on('click', function () {
        $('.add_patient_form').addClass('active');
        $('.patient_admin_header').removeClass('active');
    });
    $('#back_to_select_patient_btn').on('click', function () {
        $('#back_to_select_patient').submit();
    });
    $('#add_patient_btn').on('click', function () {
        $('#form_patient_id').prop('disabled', true);
        $('#form_select_provider').attr('action', "/patients/create");
        $('#form_select_provider').submit();
    });
    $('.patient_list').on('click', '.patient_list_item', function () {
        var id = $(this).attr('data-id');
        var formData = {
            'id': id
        };
        getPatientInfo(formData);
    });
    $('#save_patient_info').on('click', function () {

    });
    $('#change_patient_button').on('click', function () {
        $('.search_filter').addClass('active');
        $('.patient_list').addClass('active');
        $('.patient_info').removeClass('active');
        $('.action-btns').addClass('active');
        $('#select_provider_button').removeClass('active');
        $('#select_provider_button').attr('data-id', 0);
        $('#import_patients').show();
        $('.patient_admin_index_header').removeClass('hide');
        $('.patient_admin_back').removeClass('active');
    });
    $('#select_provider_button').on('click', function () {
        var id = $(this).attr('data-id');
        selectProvider(id);

    });
    $('#add_search_option').on('click', function () {
        var type = $('#search_patient_input_type').attr('value');
        var value = $('#search_patient_input').val();
        if (value != '') {
            var searchoption = getOptionContent(type, value);
            $('.search_filter').append(searchoption);
            $('#search_patient_input').val('');
            $('#search_patient_input').focus();
            $('.search_filter').addClass('active');
        }
    });
    $('.search_filter').on('click', '.remove_option', function () {
        $(this).parent().remove();
        $("#search_patient_button").trigger("click");

    });
    $('#refresh_patients').on('click', function () {
        $('#search_patient_input').val('');
        loadAllPatients();
    });
    $('#checked_all_patients').on('change', function () {
        if ($(this).is(":checked")) {
            $('.patient_search_content').each(function () {
                $(this).find('input').prop('checked', true);
            });
            $('.admin_delete').addClass('active');
            $('.delete_from_row_dropdown').addClass('invisible');
        } else {
            $('.patient_search_content').each(function () {
                $(this).find('input').prop('checked', false);
            });
            $('.admin_delete').removeClass('active');
            $('.delete_from_row_dropdown').removeClass('invisible');
        }
    });
    $('.patient_search_content').on('change', '.admin_checkbox_row', function () {
        if ($("input[name='checkbox']:checked").length > 0) {
            $('.admin_delete').addClass('active');
            $('.delete_from_row_dropdown').addClass('invisible');
        } else {
            $('.admin_delete').removeClass('active');
            $('.delete_from_row_dropdown').removeClass('invisible');
        }
    });
    $('.patient_list').on('click', '.search_name_text', function () {
        var patient_id = $(this).parents('.search_item').attr('data-id');
        var formData = {
            'id': patient_id
        };
        getPatientInfo(formData);
    });
    $('.admin_delete').on('click', function () {
        getCheckedID();
    });
    $('#open_patient_form').on('click', function () {
        window.location = '/administration/patients/create';
    });
    $('#back_to_admin_patient_btn').on('click', function () {
        window.location = '/administration/patients';
    });
    $('.patient_list').on('click', '.editPatient_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');

        window.location = '/administration/patients/edit/' + val + '';
    });
    $('#dontsave_new_patient').on('click', function () {
        if ($('#from_admin').val())
            $('#back_to_admin_patient_btn').trigger('click');
        else {
            $('#back_to_select_patient_btn').trigger('click');
        }

    });
    $('.patient_list').on('click', '.removepatient_from_row', function () {
        var val = $(this).parents('.search_item').attr('data-id');
        var id = [];
        id.push(val);
        showModalConfirmDialog('Are you sure?', function (outcome) {
            if (outcome) {
                removePatient(id);
                $(this).parents('.search_item').remove();
            }
        });
    });
    $('.patient_admin_back').on('click', 'button', function () {
        $('#change_patient_button').trigger('click');
    });
    $('.patient_list.auto_scroll').on('scroll', function () {
        if ($(this).scrollTop() + $(this).innerHeight() + 10 >= $(this)[0].scrollHeight) {
            showpage++;
            var searchdata = [];
            if (toCall == 1) {
                searchdata = getsearchtype();
            }
            if (toCall == 2) {
                searchdata = [];
                searchdata.push({
                    "type": searchType,
                    "value": searchValue,
                });
            }
            if (showpage < lastPage && searchdata.length != 0)
                getPatients(searchdata, 0);
        }
    });
    $('.content-right').on('scroll', function () {
        if ($(this).scrollTop() + $(this).innerHeight() + 2 >= $(this)[0].scrollHeight) {
            setTimeout(function () {
                loadPatientOnScroll();
            }, 1000);
        }
    });
    $(document).keypress(function (e) {
        if (e.which == 13 && !$('.modal.fade').hasClass('in')) {
            $("#search_patient_button").trigger("click");
        }
    });

    $('.edit_patient_button').on('click', function () {
        var patientID = $('.patient_info').attr('data-id');
        if ($('#from_admin').val()) {
            window.location = '/administration/patients/edit/' + patientID + '';
        } else {
            $('#form_patient_id').val(patientID);
            $('#form_select_provider').attr('action', "/patient/editfromreferral");
            $('#form_select_provider').submit();
        }
    });

    $('.add_another_phone').on('click', function () {
        $('.workphone_span').removeClass('hide_phone_field');
        $('.homephone_span').removeClass('hide_phone_field');
        $(this).addClass('hide');
    });

    $('.suggestion_list').on('click', '.practice_suggestion_item', function () {
        var selectedValue = $(this).text();
        $('.referredby_practice').val(selectedValue);
        $(this).closest('.suggestion_list').removeClass('active');
    });

    $('.suggestion_list').on('click', '.provider_suggestion_item', function () {
        var selectedValue = $(this).text();
        $('.referredby_provider').val(selectedValue);
        $(this).closest('.suggestion_list').removeClass('active');
    });

    $('.save_referredby').on('click', function () {
        var formData = {
            'referred_by_practice': $('#referred_by_practice').val(),
            'referred_by_provider': $('#referred_by_provider').val(),
            'patient_id': $('.patient_info').attr('data-id'),
        };
        saveReferredByDetails(formData);
    });

    $(document).on('click', function () {
        $('.suggestion_list').removeClass('active');
    });


    $(document).on('click', '.listing_header', function () {
        var field = $(this).find('.sort_order');
        var img = $(this).find('.sort_indicator');
        if (field.length === 0) {
            return;
        }

        if (field.css('display') !== 'none') {
            if (field.attr('data-order') === 'SORT_DESC') {
                field.attr('data-order', 'SORT_ASC');
                img.attr('src', $('#triangle_up_image_path').val());
            } else if (field.attr('data-order') === 'SORT_ASC') {
                field.attr('data-order', 'SORT_DESC');
                img.attr('src', $('#triangle_down_image_path').val());
            }
        }
        $('.sort_order').css('display', 'none');
        field.css('display', 'inline-block');
        $('#current_sort_field').val(field.attr('data-name'));
        $('#current_sort_order').val(field.attr('data-order'));
        loadAllPatients();

    });

    $(document).on('click', '.lastseen_content', function () {
        selectPreviousProvider();
    });



});
var flag = 0;
var showpage = 1;
var lastPage = 0;
var toCall = 0;
var searchType = 'all';
var searchValue = '';

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

function getCheckedID() {
    var id = [];
    $.each($("input[name='checkbox']:checked"), function () {
        id.push($(this).attr('data-id'));
    });
    removePatient(id);
    $('.admin_delete').removeClass('active');
}

function loadAllPatients() {
    var searchdata = [];
    searchType = 'all';
    searchValue = '';
    searchdata.push({
        "type": searchType,
        "value": searchValue,
    });
    showpage = 1;
    getPatients(searchdata, 0);
    $('#refresh_patients').removeClass('active');
    $('.no_item_found').removeClass('active');

}

function showPatientInfo(data) {
    $('.search_filter').removeClass('active');
    $('.patient_list').removeClass('active');
    $('.patient_info').addClass('active');
    $('.patient_info').attr('data-id', data.id);
    $('#select_provider_button').attr('data-id', data.id);
    $('#select_provider_button').addClass('active');
    $('.action-btns').removeClass('active');
    $('#import_patients').hide();
    $('#ccda_patient_id').val(data.id);
    $('#download_ccda').attr('data-href', '/download/' + data.id);
    $('#view_ccda').attr('data-href', '/show/ccda/' + data.id);
    $('.patient_admin_index_header').addClass('hide');
    $('.patient_admin_back').addClass('active');
    if ($('#from_admin').val()) {
        $('#change_patient_button').hide();
        $('.lastseen_content').css("cursor", "default");
    }
    $('.patient_section').show();
    $('.show_in_provider_patient').show();
    $('.show_in_patient').show();
    fillPatientData(data);
    $('.patient_table_content').removeClass('active');
}

function getPatientInfo(formData) {
    $.ajax({
        url: '/patients/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
            if (info.result === true) {
                showPatientInfo(info.patient_data);
            }
        },
        error: function () {
            $('p.alert_message').text('Error getting patient information');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getPatients(formData, page) {
    $('.search_filter').addClass('active');
    $('.patient_info').removeClass('active');
    $('.action-btns').addClass('active');
    $('#select_provider_button').removeClass('active');
    $('#select_provider_button').attr('data-id', 0);
    $('#import_patients').show();
    var active_img = $('#schedule_patient_img').val();
    var delete_img = $('#delete_practice_img').val();

    var tojson = JSON.stringify(formData);
    var sortInfo = {
        'order': $('#current_sort_order').val(),
        'field': $('#current_sort_field').val()

    };
    sortInfo = JSON.stringify(sortInfo);
    $.ajax({
        url: '/patients/search?page=' + showpage,
        type: 'GET',
        data: $.param({
            data: tojson,
            tosort: sortInfo
        }),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var patients = $.parseJSON(e);
            lastPage = patients[0]['lastpage'] + 1;
            if ($('#from_admin').val()) {
                toCall = 2;
                var content = '';
                lastPage = patients[0]['lastpage'] + 1;
                if (patients.length > 0 && patients[0]['total'] > 0) {
                    patients.forEach(function (patient) {
                        content += '<div class="row search_item" data-id="' + patient.id + '"><div class="col-xs-3 search_name" style="display:inline-flex"><div><input type="checkbox" class="admin_checkbox_row" data-id="' + patient.id + '" name="checkbox">&nbsp;&nbsp;</div><div class="search_name_text"><p style="margin-left:9px;">' + patient.lname + ', ' + patient.fname + '</p></div></div><div class="col-xs-3">' + patient.addressline1 + '<br>' + patient.addressline2 + '</div><div class="col-xs-1"></div><div class="col-xs-3"><p>' + patient.email + '</p></div><div class="col-xs-2 search_edit"><p><div><a href="/providers?referraltype_id=6&action=schedule_appointment&patient_id=' + patient.id + '&patient_view=true" data-toggle="tooltip" title="Schedule Patient" data-placement="bottom"><img class="action_dropdown_img" src="' + active_img + '" alt=""></a></div></p><p class="editPatient_from_row arial_bold" data-toggle="modal" data-target="#create_practice">Edit</p><div class="dropdown delete_from_row_dropdown"><span area-hidden="true" area-hidden="true" data-toggle="dropdown" class="dropdown-toggle removepatient_from_row"><img src="' + delete_img + '" alt="" class="removepatient_img" data-toggle="tooltip" title="Delete Patient" data-placement="bottom"></span><ul class="dropdown-menu" id="row_remove_dropdown"><li class="confirm_text"><p><strong>Do you really want to delete this?</strong></p></li><li class="confirm_buttons"><button type="button" class="btn btn-info btn-lg confirm_yes"> Yes</button><button type="button" class="btn btn-info btn-lg confirm_no">NO</button></li></ul></div></div></div>';
                    });
                    $('.patient_list').addClass('active');
                    if (showpage > 1)
                        $('.patient_search_content').append(content);
                    else
                        $('.patient_search_content').html(content);
                    $('[data-toggle="tooltip"]').tooltip();
                    if ($('#checked_all_patients').is(":checked")) {
                        $('.patient_search_content').each(function () {
                            $(this).find('input').prop('checked', true);
                        });
                    } else
                        $('.patient_search_content').each(function () {
                            $(this).find('input').prop('checked', false);
                        });
                } else {
                    $('.patient_list').removeClass('active');
                    $('.no_item_found').addClass('active');
                }

            } else {
                toCall = 1;
                var content = '';
                if (showpage < 2)
                    content = '<p><bold>' + 0 + '<bold> results found</p>';
                if (patients.length > 0 && patients[0]['total'] > 0) {
                    if (showpage < 2)
                        content = '<p><bold>' + patients[0]['total'] + '<bold> results found</p>';
                    patients.forEach(function (patient) {
                        content += '<div class="col-xs-12 patient_list_item" data-id="' + patient.id + '"><div class="row content-row-margin arial"><div class="col-xs-12 arial_bold patient_list_name">' + patient.lname + ', ' + patient.fname + '</div><div class="col-xs-6 patient_list_data"> ' + patient.birthdate + '<br>' + patient.phone + '</div><div class="col-xs-6 patient_list_data">' + patient.email + '<br> ' + patient.city + ' </div></div></div>';
                    });
                }
                if (showpage > 1)
                    $('.patient_list').append(content);
                else
                    $('.patient_list').html(content);
                $('.patient_list').addClass('active');

            }
        },
        error: function () {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function getsearchtype() {
    var searchdata = [];
    $('.search_filter_item').each(function () {
        var stype = $(this).children('.item_type').text();
        var name = $(this).children('.item_value').text();
        searchdata.push({
            "type": stype,
            "value": name,
        });
    });
    return searchdata;
}

function getOptionContent(type, value) {
    var content = '<div class="search_filter_item"><span class="item_type">' + type + '</span>:<span class="item_value">' + value + '</span><span class="remove_option">x</span></div>';
    return content;
}

function selectProvider(id) {

    var filesIDs = getSelectedFiles();

    $('#selected_patient_files').val(filesIDs);
    $('#form_patient_id').val(id);
    $('#form_select_provider').submit();
}



function removePatient(id) {
    var removeId = {};
    var i = 0;
    id.forEach(function (item) {
        removeId[i] = item;
        i++;
    });
    $.ajax({
        url: '/patient/destroy/',
        type: 'GET',
        data: $.param(removeId),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var searchdata = [];
            showpage = 1;
            getPatients(searchdata, showpage);

        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    $('.patient_search_content').each(function () {
        $(this).find('input').prop('checked', false);
    });
    $('#checked_all_patients').prop('checked', false);
}

function showModalConfirmDialog(msg, handler) {
    $('.patient_list').on('click', '.confirm_yes', function (evt) {
        handler(true);
    });
    $('.patient_list').on('click', '.confirm_no', function (evt) {
        handler(false);
    });
}

function checkForm() {
    $('.patient_email_field').val($('.patient_email_field').val().trim());
    var fields = $('.panel-body').find('.add_patient_input');
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
        if ($(this).hasClass('patient_email_field') && $(this).val() != "" && !patt.test($(this).val())) {
            $($(this).parents('.panel-default').find('.popover_text')).attr('data-content', 'Please enter the email in correct format');
            $($(this).parents('.panel-default').find('.popover_text')).popover("show");
            flag = 1;
            return false;
        }
    });
    if (flag == 0)
        return true;
}

function loadPatientOnScroll() {
    showpage++;
    var searchdata = [];
    if (toCall == 1)
        searchdata = getsearchtype();
    if (toCall == 2) {
        searchdata = [];
        searchdata.push({
            "type": searchType,
            "value": searchValue,
        });
    }
    if (showpage < lastPage && searchdata.length != 0) {
        getPatients(searchdata, 0);
    }

}

function selectPreviousProvider() {
    var patientID = $('#select_provider_button').attr('data-id');
    var content = '<input type="hidden" name="selected_previous_provider" value ="true"/>';
    $('#form_select_provider').append(content);
    $('#form_patient_id').val(patientID);
    $('#form_select_provider').submit();
}
