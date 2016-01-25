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

    $('#search_do').on('click', searchc3);

    $('p.c3_overview_link').on('click', function(){
        $('p.c3_overview_link').removeClass('active');
        $('.before_drilldown').show();
        $('.drilldown').removeClass('active');
        $('.stage').removeClass('sidebar_items_active');
    });

    $('.info_section').on('click', function () {
        $('p.c3_overview_link').addClass('active');
        $('.before_drilldown').hide();
        $('.drilldown').addClass('active');
        var stage_id = $($(this).closest('.info_box')).attr('id');
        var stage_name = $($(this).closest('.info_box')).attr('data-name');
        var kpi_id = $(this).attr('id');
        var kpi_name = $(this).attr('data-name');
        clearHTML();
        showKPIData(stage_id, kpi_id, stage_name, kpi_name);
    });
    $('.stage').on('click', function () {
        $('p.c3_overview_link').addClass('active');
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
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span></div></div>';
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span></div></div>';
    $('.drilldown_content').html(content);
}

function showStageData(stage_id, stage_name) {
    var content = '';
    $('#' + stage_id).addClass('sidebar_items_active');
    $('.drilldown>.section-header').html(stage_name);
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span></div></div>';
    content += '<div class="row drilldown_item" data-id="0"><div class="col-xs-2"><p>Allen Rovenstine</p></div><div class="col-xs-2"><p>888-227-3365</p></div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">12:00 PM<br>December 08, 2015</div><div class="col-xs-2">Atlanta Eye Associates</div><div class="col-xs-2"><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em; text-align:center"></span></div></div>';
    $('.drilldown_content').html(content);
}

function clearHTML() {
    $('.drilldown>.section-header').html('');
    $('.drilldown>.subsection-header>p').html('');
    $('.drilldown_content').html('');
}
