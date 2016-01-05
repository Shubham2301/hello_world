'use strict';

$(document).ready(function () {

    $('#search_practice_button').on('click', function () {
        $("#add_practice_search_option").trigger("click");
        $('#search_practice_input').val('');
        var searchdata = getSearchType();
        getProviders(searchdata);
    });

    $('.practice_list').on('click', '.practice_list_item', function () {
        var id = $(this).attr('data-id');
        var formData = {
            'id': id
        };

        showProviderInfo(fetcheddata[id]);

        //getProviderInfo(formData);
    });
    $('#change_practice_button').on('click', function () {

        $('.practice_list').addClass('active');
        $('.practice_info').removeClass('active');
        $('.patient_previous_information').addClass('active');
    });

    $('#add_practice_search_option').on('click', function () {
        var type = $('#search_practice_input_type').val();
        var value = $('#search_practice_input').val();
        if (value != '') {
            var searchoption = getOptionContent(type, value);
            $('.search_filter').append(searchoption);
            $('#search_practice_input').val('');
        }
    });

    $('.search_filter').on('click', '.remove_option', function () {
        $(this).parent().remove();
    });
    $('.schedule_button').on('click', function () {

        alert($(this).attr('data-id'));
    });
});

$(document).ready(function () {

    $('.provider_near_patient').on('click', function () {
        $('.provider_near_patient_list').toggleClass("active");
        if ($('.provider_near_patient_list').hasClass("active")) showPreviousProvider();else {
            $('.provider_near').removeClass('glyphicon-chevron-down');
            $('.provider_near').addClass('glyphicon-chevron-right');
        }
    });

    $('.previous_provider_patient').on('click', function () {
        $('.previous_provider_patient_list').toggleClass("active");
        if ($('.previous_provider_patient_list').hasClass("active")) showProviderNear();else {
            $('.provider_previous').removeClass('glyphicon-chevron-down');
            $('.provider_previous').addClass('glyphicon-chevron-right');
        }
    });
});

var fetcheddata = [];

//function that displays the providers near patients
function showPreviousProvider() {
    var provider_list = new Array("2536", "ColoredCow", "Tushar", "Gurgaon", "Eyes");
    var content = '<div class="col-xs-12 list_seperator" data-id="' + provider_list[0] + '"><div class="row"><div class="col-xs-12">' + provider_list[1] + '<br> ' + provider_list[2] + ' </div><div class="col-xs-6">' + provider_list[3] + ' </div><div class="col-xs-6">' + provider_list[4] + ' </div></div></div>';
    $('.provider_near_patient_list').html(content);
    $('.provider_near').removeClass('glyphicon-chevron-right');
    $('.provider_near').addClass('glyphicon-chevron-down');
}

//function that displays the previous providers of the patients
function showProviderNear() {
    var provider_list = new Array("2536", "ColoredCow", "Tushar", "Gurgaon", "Eyes");
    var content = '<div class="col-xs-12 list_seperator" data-id="' + provider_list[0] + '"><div class="row"><div class="col-xs-12">' + provider_list[1] + '<br> ' + provider_list[2] + ' </div><div class="col-xs-6">' + provider_list[3] + ' </div><div class="col-xs-6">' + provider_list[4] + ' </div></div></div>';
    $('.previous_provider_patient_list').html(content);
    $('.provider_previous').removeClass('glyphicon-chevron-right');
    $('.provider_previous').addClass('glyphicon-chevron-down');
}

function showProviderInfo(data) {
    $('#docter_name').text(data.doctor_name);
    $('#zipcode').text(data.zipcode);
    $('#phone').text(data.user_phone);
    $('.schedule_button').attr('data-id', data.user_id);
    $('.practice_list').removeClass('active');
    $('.practice_info').addClass('active');
    $('.patient_previous_information').removeClass('active');
}

function getProviderInfo(formData) {

    $.ajax({
        url: '/practices/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var info = $.parseJSON(e);
            showProviderInfo(info);
        },
        error: function error() {
            alert('Error getting practice information');
        },
        cache: false,
        processData: false
    });
}

function getProviders(formData) {
    $('.practice_list').addClass('active');
    $('.practice_info').removeClass('active');
    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/practices/search',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var practices = $.parseJSON(e);
            var content = '<p><bold>' + practices.length + '<bold> results found</p><br>';
            var provider_index = 0;
            if (practices.length > 0) {
                practices.forEach(function (practice) {
                    content += '<div class="col-xs-12 practice_list_item" data-id="' + provider_index + '"><div class="row content-row-margin"><div class="col-xs-6">' + practice.doctor_name + ' <br> ' + practice.name + ' </div><div class="col-xs-6">' + '' + '<br> ' + '' + ' </div></div></div>';
                    fetcheddata.push(practice);
                    provider_index++;
                });
            }
            $('.practice_list').html(content);
            $('.practice_list').addClass('active');
        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}

function getOptionContent(type, value) {
    var content = '<div class="search_filter_item"><span class="item_type">' + type + '</span>:<span class="item_value">' + value + '</span><span class="remove_option">x</span></div>';

    return content;
}

function getSearchType() {
    var searchdata = [];
    $('.search_filter_item').each(function () {
        var stype = $(this).children('.item_type').text();
        var name = $(this).children('.item_value').text();
        searchdata.push({
            "type": stype,
            "value": name

        });
    });
    return searchdata;
}
//# sourceMappingURL=practice.js.map
