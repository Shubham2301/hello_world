$(document).ready(function () {
    $('#search_bar_open').on('click', function () {
        if (($('#search_bar_open').hasClass('active'))) {
            $('#search_bar_open').removeClass('active');
            $('#search_bar_open').removeClass('glyphicon-chevron-left');
            $('#search_bar_open').addClass('glyphicon-chevron-right');
            $('#search_do').addClass('active');
            $('.search').addClass('active');
            $('#search_data').addClass('active');
        } else {
            $('#search_bar_open').addClass('active');
            $('#search_bar_open').removeClass('glyphicon-chevron-right');
            $('#search_bar_open').addClass('glyphicon-chevron-left');
            $('#search_do').removeClass('active');
            $('.search').removeClass('active');
            $('#search_data').removeClass('active');
            $('.search_result').removeClass('active');
        }
    });
    $('.C3_day_box').on('click', function () {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
        }
        else{
            $('.C3_day_box').removeClass('active');
            $(this).addClass('active');
        }
    });

    $('.day_box.active').on('click', function () {
        $(this).removeClass('active');
    });

    $('#search_do').on('click', searchc3);

    $('.c3_overview_link').on('click', function () {
        $('.c3_overview_link').removeClass('active');
        $('.control_section').removeClass('active');
        $('ul.c3_sidebar_list').removeClass('active');
        $('.before_drilldown').show();
        $('.drilldown').removeClass('active');
        $('.stage').removeClass('sidebar_items_active');
    });

    $('.info_section').on('click', function () {
        $('.c3_overview_link').addClass('active');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        var stage_id = $($(this).closest('.info_box')).attr('id');
        var stage_name = $($(this).closest('.info_box')).attr('data-name');
        var kpi_id = $(this).attr('id');
        var kpi_name = $(this).attr('data-name');
        clearHTML();
        showKPIData(stage_id, kpi_id, stage_name, kpi_name);
    });
    $('.stage').on('click', function () {
        $('.c3_overview_link').addClass('active');
        $('.control_section').addClass('active');
        $('ul.c3_sidebar_list').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        $('.stage').removeClass('sidebar_items_active');
        $('.subsection-header').removeClass('active');
        var stage_id = '';
        var stage_name = '';
        if ($(this).hasClass('bottom')) {
            stage_id = $($(this).closest('.info_box')).attr('id');
            stage_name = $($(this).closest('.info_box')).attr('data-name');
        } else {
            stage_id = $(this).attr('id');
            stage_name = $(this).attr('data-name');
        }
        clearHTML();
        showStageData(stage_id, stage_name);
    });
});

function searchc3() {
    if (!($('#search_bar_open').hasClass('active'))) {
        $('.search_result').addClass('active');
    }
}

function showKPIData(stage_id, kpi_id, stage_name, kpi_name) {
    var content = '';
    $('#' + stage_id).addClass('sidebar_items_active');
    $('.subsection-header').addClass('active');
    $('.drilldown>.section-header').html(stage_name);
    $('.drilldown>.subsection-header>p').html(kpi_name);
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span><ul class="dropdown-menu" aria-labelledby="dropdownMenu2" style="width: 150%;border-radius: 3px;margin-left: -135%;text-align: right;"><li><a href="#">Schedule Appointment</a></li><li><a href="#">Archive Patient</a></li></ul></div></div></div>';
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span><ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="width: 150%;border-radius: 3px;margin-left: -135%;text-align: right;"><li><a href="#">Schedule Appointment</a></li><li><a href="#">Archive Patient</a></li></ul></div></div></div>';
    $('.drilldown_content').html(content);
}

function showStageData(stage_id, stage_name) {
    var content = '';
    $('#' + stage_id).addClass('sidebar_items_active');
    $('.drilldown>.section-header').html(stage_name);
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span><ul class="dropdown-menu" aria-labelledby="dropdownMenu2" style="width: 150%;border-radius: 3px;margin-left: -135%;text-align: right;"><li><a href="#">Schedule Appointment</a></li><li><a href="#">Archive Patient</a></li></ul></div></div></div>';
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><div class="dropdown"><span class="glyphicon glyphicon-triangle-bottom dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span><ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="width: 150%;border-radius: 3px;margin-left: -135%;text-align: right;"><li><a href="#">Schedule Appointment</a></li><li><a href="#">Archive Patient</a></li></ul></div></div></div>';
    $('.drilldown_content').html(content);
}

function clearHTML() {
    $('.drilldown>.section-header').html('');
    $('.drilldown>.subsection-header>p').html('');
    $('.drilldown_content').html('');
}
