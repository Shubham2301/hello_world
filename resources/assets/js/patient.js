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
    
    $('.patient_list').on('click', '.patient_list_item', function () {
        var id = $(this).attr('data-id');
        var formData = {
            'id': id
        };
        console.log(id);
        getPatientInfo(formData);
    });
    
    
});



function showPatientInfo(data) {
    
    $('.patient_list').removeClass('active');
    $('.patient_info').addClass('active');
    
}

function getPatientInfo(formData){
    
    $.ajax({
        url: '/patients/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var info = $.parseJSON(e);
            showPatientInfo(info);
        },
        error: function () {
            alert('Error getting patient information');
        },
        cache: false,
        processData: false
    });
    
}

function getPatients(formData) {

    $.ajax({
        url: '/patients/search',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var patients = $.parseJSON(e);
            var content = '<p><bold>' + patients.length + '<bold> results found</p><br>';

            if (patients.length > 0) {
                patients.forEach(function (patient) {
                    content += '<div class="col-xs-12 patient_list_item" data-id="' + patient.id + '"><div class="row content-row-margin"><div class="col-xs-6">' + patient.fname + ' ' + patient.lname + '<br> ' + patient.birthdate + ' </div><div class="col-xs-6">' + patient.email + '<br> ' + patient.city + ' </div></div></div>';
                });
            }
            $('.patient_list').html(content);
            $('.patient_list').addClass('active');
        },
        error: function () {
            alert('Error searching');
        },
        cache: false,
        processData: false
    });

}