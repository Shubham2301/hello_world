'use strict';

$(document).ready(function () {

    $('#search_patient_button').on('click', function () {
        var type = $('#search_patient_input_type').val();
        var value = $('#search_patient_input').val();

        if (value === '') {
            $('#search_patient_input').focus();
        }
        var formData = {
            'type': type,
            'value': value
        };
        getPatients(formData);
    });
});

function getPatients(formData) {

    $.ajax({
        url: '/patients/search',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var patients = $.parseJSON(e);
            var content = '<p><bold>' + patients.length + '<bold> results found</p><br>';

            if (patients.length > 0) {
                patients.forEach(function (elem) {
                    content += '<div class="col-xs-12 patient_list_item"><div class="row content-row-margin"><div class="col-xs-6">' + elem.fname + ' ' + elem.lname + '<br> ' + elem.birthdate + ' </div><div class="col-xs-6">' + elem.email + '<br> ' + elem.city + ' </div></div></div>';
                });
            }
            $('.patient_list').html(content);
            $('.patient_list').addClass('active');
        },
        error: function error() {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });
}
//# sourceMappingURL=patient.js.map
