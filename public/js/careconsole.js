'use strict';

$(document).ready(function () {
    $('#search_bar_open').on('click', function () {
        if ($('#search_bar_open').hasClass('active')) {
            $('#search_bar_open').removeClass('active');
            $('#search_bar_open').removeClass('glyphicon-chevron-left');
            $('#search_bar_open').addClass('glyphicon-chevron-right');
            $('#search_do').addClass('active');
            $('.search').addClass('active');
            $('#search_data').addClass('active');
        } else {
            $('#search_bar_open').addClass('active');
            $('#search_bar_open').removeClass('glyphicon-chevron-right');
            $('#search_bar_open').addClass('glyphicon-chevron-left');
            $('#search_do').removeClass('active');
            $('.search').removeClass('active');
            $('#search_data').removeClass('active');
            $('.search_result').removeClass('active');
        }
    });

    $('#search_do').on('click', searchc3);
});

function searchc3() {
    if (!$('#search_bar_open').hasClass('active')) {
        $('.search_result').addClass('active');
    }
}
//# sourceMappingURL=careconsole.js.map
