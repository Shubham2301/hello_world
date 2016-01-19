$(document).ready(function () {
    $('#practice_list').on('change', function (e) {
        var practice_id = $(this).find(":selected").val();
        getLocation(practice_id);

    });
});

function getLocation(id)
{
    var formData = {
        practice_id: id
    };

    $.ajax({
        url: '/import/location',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
        var locations = $.parseJSON(e);
        var content = '<option value="-1">select Location</option>';
        locations.forEach(function(location)
        {
          content+='<option value="'+location.id +'">'+location.name+'</option>';

        });
           $('#practice_locations').html(content);
        },
        error: function () {
        alert('Error removing');
        },
        cache: false,
        processData: false
    });

}
