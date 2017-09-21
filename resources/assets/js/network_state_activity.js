$(document).ready(function() {

    var cur_date = new Date();
    var set_start_date = new Date(cur_date.getTime());
    $('#start_date').datetimepicker({
        defaultDate: set_start_date.setDate(cur_date.getDate() - 31),
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
    });
    set_start_date = new Date(cur_date.getTime());
    $('#end_date').datetimepicker({
        defaultDate: cur_date,
        format: 'MM/DD/YYYY',
        maxDate: cur_date,
        minDate: set_start_date.setDate(cur_date.getDate() - 30),
    });
    var old_start_date = $('#start_date').val();
    var old_end_date = $('#end_date').val();

    $('#start_date').datetimepicker().on('dp.hide', function(ev) {
        var start_date = $('#start_date').val();
        $('#end_date').data("DateTimePicker").minDate(new Date(start_date));
    });
    $('#end_date').datetimepicker().on('dp.hide', function(ev) {
        var end_date = $('#end_date').val();
        $('#start_date').data("DateTimePicker").maxDate(new Date(end_date));
    });

    $('#get_network_state_activity').on('click', function() {
        getReport();
    });
    
    $('#network_id').on('change', function() {
        updateFilter();
    });

});


function getReport() {

    var states = [];
    $.each($(".state_list_container input[name='state_list']:checked"), function() {
        states.push($(this).val());
    });

    var formData = {
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        network_id: $('#network_id').val(),
        state_list: states,
    };

    var query = $.param(formData);
    window.location = '/report/network-state-activity/get_report_data?' + query;

}

function updateFilter() {
    var selected_network_state = $('#network_id :selected').attr('attr-state');

    var state_array = selected_network_state.split(';');

    var state_filters = '';
    
    $.each(state_array, function(index, value) {
        if(value !== '') {
            state_filters += '<span class="state_wraper"><input type="checkbox" value="' + value + '" name="state_list"> ' + value + ' </span>';
        }
    });

    $('.state_list_container').html(state_filters);
}