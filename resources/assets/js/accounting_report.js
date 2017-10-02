$(document).ready(function() {

    var cur_date = new Date();
    var set_start_date = new Date(cur_date.getTime());

    $('.start_date').datetimepicker({
        defaultDate: set_start_date.setDate(cur_date.getDate() - 31),
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
    });
    set_start_date = new Date(cur_date.getTime());
    $('.end_date').datetimepicker({
        defaultDate: cur_date,
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
        minDate: set_start_date.setDate(cur_date.getDate() - 30),
    });

    $('.start_date').datetimepicker().on('dp.hide', function(ev) {
        var report = $(this).attr('report');
        var start_date = $(".start_date[report='" + report + "']").val();
        $(".end_date[report='" + report + "']").data("DateTimePicker").minDate(new Date(start_date));
    });
    $('.end_date').datetimepicker().on('dp.hide', function(ev) {
        var report = $(this).attr('report');
        var end_date = $(".end_date[report='" + report + "']").val();
        $(".start_date[report='" + report + "']").data("DateTimePicker").maxDate(new Date(end_date));
    });


    $('.export_button').on('click', function() {
        var report_type = $(this).attr('id');
        switch (report_type) {
            case 'provider_billing':
                getProviderBillingReport();
                break;
            case 'payer_billing':
                getPayerBillingReport();
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

function getPayerBillingReport() {

    var formData = {
        network_id: $('.network_selector.payer_billing').val(),
        start_date: $(".start_date[report='payer_billing']").val(),
        end_date: $(".end_date[report='payer_billing']").val()
    };

    var query = $.param(formData);
    window.location = '/report/accounting_report/payer_billing?' + query;
}