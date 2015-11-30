'use strict';

$(document).ready(function () {

    $('.menu-item').on('click', function () {
        if ($(this).hasClass('active')) return;
        var id = $(this).attr('data-id');
        $('.menu-item').removeClass('active');
        $('.content-section').removeClass('active');
        $(this).addClass('active');
        $('#' + id).addClass('active');
    });
});
//# sourceMappingURL=main.js.map
