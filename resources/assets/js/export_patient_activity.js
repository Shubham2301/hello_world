$(document).ready(function() {


    $('#export_patient_activity_excel').on('click', function() {
        var formData = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            network_id: $('#network_id').val(),
        };
        var query = $.param(formData);
        window.location = '/report/export-patient-activity/get-patient-excel?' + query;
    });

});