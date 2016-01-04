$(document).ready(function () {

    $('.provider_near_patient').on('click', function () {
            showPreviousProvider();
    });

    $('.previous_provider_patient').on('click', function () {
            showProviderNear();
    });
});

//function that displays the providers near patients
function showPreviousProvider() {
        var provider_list = new Array("2536","ColoredCow", "Tushar", "Gurgaon", "Eyes");
        var content = '<div class="col-xs-12 list_seperator" data-id="' + provider_list[0] + '"><div class="row"><div class="col-xs-12">' + provider_list[1] + '<br> ' + provider_list[2] + ' </div><div class="col-xs-6">' + provider_list[3] + ' </div><div class="col-xs-6">' + provider_list[4] + ' </div></div></div>';
        $('.provider_near_patient_list').html(content);
        $('.provider_near').removeClass('glyphicon-chevron-right');
        $('.provider_near').addClass('glyphicon-chevron-down');
}

//function that displays the previous providers of the patients
function showProviderNear() {
        var provider_list = new Array("2536","ColoredCow", "Tushar", "Gurgaon", "Eyes");
        var content = '<div class="col-xs-12 list_seperator" data-id="' + provider_list[0] + '"><div class="row"><div class="col-xs-12">' + provider_list[1] + '<br> ' + provider_list[2] + ' </div><div class="col-xs-6">' + provider_list[3] + ' </div><div class="col-xs-6">' + provider_list[4] + ' </div></div></div>';
        $('.previous_provider_patient_list').html(content);
        $('.provider_previous').removeClass('glyphicon-chevron-right');
        $('.provider_previous').addClass('glyphicon-chevron-down');
}
