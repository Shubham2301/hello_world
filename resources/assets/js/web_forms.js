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
		var r = confirm("All data will be lost");
		if (r == true) {
			$(this).hide();
			$('.section').removeClass('active');
			$('.search_section').addClass('active');
			$('#search_patient_button').show();
			$('#search_patient_input').prop('readonly', false);
			$('.select_form_dropdown').find('p').hide();
		}
    });

    $('.showwebform').on('click', function() {
        var name = $(this).attr('value');
		templateID = $(this).attr('data-id');

        showWebForm(name);
    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
			if ($('#search_patient_button').css('display') != 'none') {
                $('#search_patient_button').trigger("click");
            }
        }
    });

    $('.form_section').on('click', '#previous_btn',  function() {

        var  dataid =   $('.form_chunk.active').attr('data-index');
        $('#continue_btn').show();
        dataid--;

        if(dataid+'' === "1") {
           $(this).hide();
        }
        else {
           $(this).show();
        }

        $('.form_chunk').removeClass('active');
        $('.form_chunk_'+dataid).addClass('active');

    });

    $('.form_section').on('click', '#continue_btn' , function() {
        var  dataid =   $('.form_chunk.active').attr('data-index');
        $('#previous_btn').show();
        dataid++;
        console.log(dataid );
        console.log($('#count_form_sections').val());
        if(dataid+'' === $('#count_form_sections').val())
        {
            $(this).hide();
        }
        else
        {
            $(this).show();
        }
        $('.form_chunk').removeClass('active');
        $('.form_chunk_'+dataid).addClass('active');
    });

    $('.form_section').on('click', '.tgl_text', function () {
          var isd = $(this).find('.tgl').prop('checked');
          if(isd)
          {
            $(this).addClass('checkfiled');
          }
          else{
            $(this).removeClass('checkfiled');
          }
    });

	$('.form_section').on('click', '#create_record', savePatientRecord);

});

var templateID = 0;

function getPatients(formData, page) {
    var tojson = JSON.stringify(formData);
    var sortInfo = {
        'order': $('#current_sort_order').val(),
        'field': $('#current_sort_field').val()
    };
    sortInfo = JSON.stringify(sortInfo);
    $.ajax({
        url: '/patients/search',
        type: 'GET',
        data: $.param({
            data: tojson,
            tosort: sortInfo
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var patients = $.parseJSON(e);
			$('.section').removeClass('active');
            $('.search_section').addClass('active');
            $('#search_listing').html(patients[0]['view']);
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

    $.ajax({
        url: '/createrecord/' + name,
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function success(e) {
			$('.section').removeClass('active');
            $('.search_section').removeClass('active');
            $('.form_section').html(e);
            $('.form_section').addClass('active');
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function savePatientRecord(){
	var myform = document.getElementById("patient_record_form");
	$('#patient_id').val($('#search_patient_input').attr('data-id'));
	$('#template_id').val(templateID);
	myform.submit();

//	var fd = new FormData(myform);
//	$.ajax({
//		url: "/save_records",
//		data:fd,
//		cache: false,
//		processData: false,
//		contentType: false,
//		type: 'POST',
//		success: function(e) {
//			alert('success');
//		}
//	});
}
