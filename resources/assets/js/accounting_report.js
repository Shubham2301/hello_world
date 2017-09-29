$(document).ready(function() {

    $('.export_button').on('click', function() {

        var report_type = $(this).attr('id');
        console.log(report_type);

        switch (report_type) {
            case 'provider_billing':
                getProviderBillingReport();
                break;
            default:
        }

    });



});


function getProviderBillingReport() {

    var formData = {
        network_id: $('.network_selector.provider_billing').val(),
    };

    var query = $.param(formData);
    window.location = '/report/accounting_report/provider_billing?' + query;

}