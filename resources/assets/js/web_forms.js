'use strict';
$(document).ready(function() {

    $('#search_patient_button').on('click', function() {
        var searchText = $('#search_patient_input').val();
        $('.search_section').addClass('active');
        $('.form_section').html('');
        $('.form_section').removeClass('active');
        if (searchText != '') {
            var searchType = 'name';
            var searchdata = [];
            searchdata.push({
                "type": searchType,
                "value": searchText
            });

            getPatients(searchdata, 0);
        }
    });

    $('#search_listing').on('click', '.patient_list_item', function() {
        $('.section').removeClass('active');
        $('.search_section').removeClass('active');
        $('#search_patient_input').val($(this).attr('data-name'));
        $('#search_patient_input').attr('data-id', $(this).attr('data-id'));
        $('#search_patient_input').prop('readonly', true);
        $('#search_patient_button').hide();
        $('#remove_patient_button').show();
        $('.select_form_dropdown').find('p').show();
    });

    $('.search_input_box').on('click', '#remove_patient_button', function() {
        var removeBtnObj = this;

        if ($('.form_section').hasClass('active')) {
            showModalConfirmDialog('all data will be lost if you change patients', function(outcome) {
                if (outcome) {
                    unSelectPatient(removeBtnObj);
                }
            });
        } else {
            unSelectPatient(removeBtnObj);
        }
    });

    $('.showwebform').on('click', function() {
        var name = $(this).attr('value');
        templateID = $(this).attr('data-id');
        $('.form_name').text($(this).text());

        if ($('.form_section').hasClass('active')) {
            showModalConfirmDialog('all data will be lost if you change forms', function(outcome) {
                if (outcome) {
                    showWebForm(name);
                }
            });
        } else {
            showWebForm(name);
        }
    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            if ($('#search_patient_button').css('display') != 'none') {
                $('#search_patient_button').trigger("click");
            }
        }
    });

    $('.form_section').on('click', '#previous_btn', function() {

        var dataid = $('.form_chunk.active').attr('data-index');
        $('#continue_btn').show();
        dataid--;

        if (dataid.toString() === "1") {
            $(this).hide();
        } else {
            $(this).show();
        }

        $('.form_chunk').removeClass('active');
        $('.form_chunk_' + dataid).addClass('active');

    });

    $('.form_section').on('click', '#continue_btn', function() {
        var dataid = $('.form_chunk.active').attr('data-index');
        $('#previous_btn').show();
        dataid++;
        if (dataid.toString() === $('#count_form_sections').val()) {
            $(this).hide();
        } else {
            $(this).show();
        }
        $('.form_chunk').removeClass('active');
        $('.form_chunk_' + dataid).addClass('active');
    });

    $('.form_section').on('click', '.tgl_text', function() {
        var isChecked = $(this).find('.tgl').prop('checked');
        if (isChecked) {
            $(this).addClass('checkfiled');
        } else {
            $(this).removeClass('checkfiled');
        }
    });

    $('.form_section').on('click', '.tgl_radio', function(event) {
        var name = $(this).find('.tgl').attr('name');
        var selectedRadioObjarry = $('input[name=' + name + ']');
        var isChecked = $(this).find('.tgl').prop('checked');

        if (event.target.tagName != "LABEL") {
            if (isCheckedAgain) {
                $(this).find('.tgl').prop('checked', false);
                $(this).removeClass('checkfiled');
                isCheckedAgain = false;
            }
            return;
        }

        if (isChecked) {
            isCheckedAgain = true;
            return;
        }

        isCheckedAgain = false;

        $(this).find('.tgl').prop('checked', true);
        $.each(selectedRadioObjarry, function(key, obj) {
            obj = $(obj);
            if ($(obj).prop('checked')) {

                obj.parent().addClass('checkfiled');
            } else {
                obj.parent().removeClass('checkfiled');

            }

        });

    });

    $('.form_section').on('click', '#create_record', savePatientRecord);

    $('.health_record').addClass('active');

    $('.form_section').on('click', '.input_checkbox_lable', function() {
        var isChecked = $(this).find('.input_checkbox').prop('checked');
        if (isChecked) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });

    $('.form_section').on('click', '.radio_checkbox_lable', function(event) {
        var name = $(this).find('.radio_checkbox').attr('name');
        var selectedRadioObjarry = $('input[name=' + name + ']');
        var isChecked = $(this).find('.radio_checkbox').prop('checked');

        if (event.target.tagName != "LABEL") {
            if (isCheckedAgain) {
                $(this).find('.radio_checkbox').prop('checked', false);
                $(this).removeClass('active');
                isCheckedAgain = false;
            }
            return;
        }

        if (isChecked) {
            isCheckedAgain = true;
            return;
        }

        isCheckedAgain = false;

        $(this).find('.radio_checkbox').prop('checked', true);
        $.each(selectedRadioObjarry, function(key, obj) {
            obj = $(obj);
            if ($(obj).prop('checked')) {

                obj.parent().addClass('active');
            } else {
                obj.parent().removeClass('active');

            }

        });

    });

});

var templateID = 0;
var isCheckedAgain = false;

function getPatients(formData, page) {
    var tojson = JSON.stringify(formData);
    var sortInfo = {
        'order': $('#current_sort_order').val(),
        'field': $('#current_sort_field').val()
    };
    sortInfo = JSON.stringify(sortInfo);
    $.ajax({
        url: '/patientlistforcreaterecord',
        type: 'GET',
        data: $.param({
            data: tojson,
            tosort: sortInfo
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            $('.section').removeClass('active');
            $('.search_section').addClass('active');
            $('#search_listing').html(e);
            $('#search_patient_button').show();
            $('#remove_patient_button').hide();
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function showWebForm(name) {
    var patientID = $('#search_patient_input').attr('data-id');

    $.ajax({
        url: '/createrecord/' + name + '/' + patientID,
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            $('.section').removeClass('active');
            $('.search_section').removeClass('active');
            $('.form_section').html(e);
            $('.form_section').addClass('active');
            $('.field_date').datetimepicker({
                format: 'MM/DD/YYYY'
            });

            $('.sigPad').signaturePad({
                bgColour: '#fff',
                drawOnly: true,
                lineWidth: 0,
            });

            changeTheSizeOfPad();
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function savePatientRecord() {
    var recordForm = document.getElementById("patient_record_form");
    $('#patient_id').val($('#search_patient_input').attr('data-id'));
    $('#template_id').val(templateID);
    $('.form_footer').hide();
    $('#footer_loader_section').show();
    recordForm.submit();
}

function showModalConfirmDialog(msg, handler) {
    $('p.alert_message>.msg').text(msg);
    var clicked = false;
    $('#confirm').modal('show');
    var result = false;
    $('.alert').on('click', '.confirm_yes', function() {
        if (!clicked) {
            handler(true);
        }
        clicked = true;
    });
    $('.alert').on('click', '.confirm_no', function() {
        if (!clicked) {

            handler(false);
        }
        clicked = true;
    });
}

function unSelectPatient(removeBtnObj) {
    $(removeBtnObj).hide();
    $('.section').removeClass('active');
    $('.search_section').addClass('active');
    $('#search_patient_button').show();
    $('#search_patient_input').prop('readonly', false);
    $('.select_form_dropdown').find('p').hide();
}

function changeTheSizeOfPad()
{
    $('.sigWrapper').css({
        "height":"120px",
        "padding":"10px",
    });

    $('.sigPad').css('width', '400px');
}
