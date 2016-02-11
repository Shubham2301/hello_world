'use strict';

$(document).ready(function () {

    loadAllNetworks()

    $('#search_network_button').on('click', function () {
        var searchvalue = $('#search_network_input').val();
        var formData = {
            'value': searchvalue
        };
        getNetworks(formData);
    });

    $('#dontsave_network').on('click',function(){
        $('.back-btn').trigger('click');
    });


});

function getNetworks(formData) {
    var tojson = JSON.stringify(formData);
    $.ajax({
        url: '/networks/search',
        type: 'GET',
        data: $.param({
            data: tojson
        }),
        contentType: 'text/html',
        async: false,
        success: function success(e) {
            var networks = $.parseJSON(e);
            var content = '';
            $('#search_results').text(networks[0]['total'] + ' Results found');
            if (networks.length > 0) {
                networks.forEach(function (network) {
                    content += '<div class="row search_item" data-id="' + network.id + '"><div class="col-xs-3 search_name"><input type="checkbox">&nbsp;&nbsp;<p>' + network.name + '</p></div><div class="col-xs-3">' + network.email + '<br>' + network.phone + '</div><div class="col-xs-1"></div><div class="col-xs-3"><p>' + network.addressline1 + '<br>' + network.addressline2 + '</p></div> <div class="col-xs-2 search_edit"><p ><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;"></span></p>&nbsp;&nbsp;<p class="editpractice_from_row" data-toggle="modal" data-target="#create_practice">Edit</p>&nbsp;&nbsp;<span class="glyphicon glyphicon-remove removepractice_from_row " area-hidden="true" style="background: maroon;color: white;padding: 3px;border-radius: 3px;font-size: 0.9em;"></span></div></div>';
                });
                $('.page_info').text(networks[0]['currentPage'] + ' of ' + networks[0]['lastpage']);
                $('.network_search_content').html(content);
            }

        },
        error: function error() {
            $('p.alert_message').text('Error searching');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function loadAllNetworks() {
    var formData = {
        'value': ''
    };
    getNetworks(formData);
}
