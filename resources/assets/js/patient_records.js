$(document).ready(function () {
    $('#search_patient_input').val($('#patient_id').val());

    if ($('#search_patient_input').val()) {
        searchReportPatient();
    }

    $('#search_patient_button').on('click', function () {
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

    $('.listing_section').on('click', '#pagination>img', function () {
        var page = $(this).attr('data-index');
        var searchdata = [];
        searchdata.push({
            "type": 'name',
            "value": searchValue
        });

        getPatients(searchdata, page);
    });

    $('.listing_section').on('click', '.list_item_name', function () {
        var patientID = $(this).attr('data-id');
        if (!$(this).attr('aria-expanded') || $(this).attr('aria-expanded') === "false") {
            showCareTimeLine(patientID);
        } else {
            var content = defaultCareTimeline();
            $('.care_timeline').html(content);
        }
    });

    $('.listing_section').on('click', '.show_more_text', function () {
        var patientID = $(this).attr('data-id');
        showCareTimeLine(patientID);
        $('.timeline_section').scrollTop($('.timeline_section')[0].scrollHeight);
    });

    $(document).keypress(function (e) {
        if (e.which == 13) {
            $('#search_patient_button').trigger("click");
        }
    });

    $('.patient_record').addClass('active');

    $('.care_timeline').html(defaultCareTimeline());

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
            $('.patient_listing_section').html(e);
            $('.patient_listing_section').addClass('active');
            var content = defaultCareTimeline();
            $('.care_timeline').html(content);
            if ($('#patient_id').val()) {
                $('.list_item_name').trigger('click');
                if (e != 'No result found')
                    showCareTimeLine($('#patient_id').val());
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

function showCareTimeLine(patientID) {
    $.ajax({
        url: '/getcaretimeline',
        data: $.param({
            patient_id: patientID,
            getresult: $('.show_more_text').attr('data-result')
        }),

        type: 'GET',
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            $('.care_timeline').html(e);
        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });

}

function defaultCareTimeline() {
    var content = '<div class="timeline_section">';
    var default_active = 'default_active';
    for (var i = 0; i < 4; i++) {
        content += '<div class="row"><div class="col-xs-1"> </div><div class="col-xs-2"><p class="date_left"></p></div><div class="col-xs-1"><div class="timeline"><ul><li class="' + default_active + '"></li></ul></div></div><div class="col-xs-6"></div></div>';

        default_active = '';
    }

    content += '</div>';
    return content;

}

function searchReportPatient() {
    var searchText = $('#search_patient_input').val();
    searchValue = searchText;
    $('.search_section').addClass('active');
    $('.form_section').html('');
    $('.form_section').removeClass('active');
    if (searchText != '') {
        var searchType = 'id';
        var searchdata = [];
        searchdata.push({
            "type": searchType,
            "value": searchText
        });

        getPatients(searchdata, 0);
    }
}
