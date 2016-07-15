$(document).ready(function() {

	$('#search_patient_button').on('click', function() {
		var searchText = $('#search_patient_input').val();
		searchValue = searchText;
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

	$('.listing_section').on('click', '#pagination>img', function(){
		var page = $(this).attr('data-index');
		var searchdata = [];
		searchdata.push({
			"type": 'name',
			"value": searchValue
		});

		getPatients(searchdata, page);
	});


	$(document).keypress(function(e) {
		if (e.which == 13) {
			$('#search_patient_button').trigger("click");
		}
	});

});


var searchValue = '';

function getPatients(formData, page) {
	var tojson = JSON.stringify(formData);
	var sortInfo = {
		'order': $('#current_sort_order').val(),
		'field': $('#current_sort_field').val()
	};
	sortInfo = JSON.stringify(sortInfo);
	$.ajax({
		url: '/patientlistforshowrecord',
		type: 'GET',
		data: $.param({
			data: tojson,
			tosort: sortInfo,
			countresult: 6,
			page: page,
		}),
		contentType: 'text/html',
		async: false,
		success: function success(e) {
			$('.listing_section').html(e);
			$('.listing_section').addClass('active');
		},
		error: function error() {
			$('p.alert_message').text('Error searching');
			$('#alert').modal('show');
		},
		cache: false,
		processData: false
	});
}
