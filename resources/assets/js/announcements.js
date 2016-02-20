$(document).ready(function () {

    $('#open_announcement').on('click', function () {
        $('.announcement_box').addClass('visible');
        $('#show_announcements').addClass('active');
        showAnnouncements();
    });
    $('#close_announcement').on('click', function () {
        $('.announcement_box').removeClass('visible');
        $('.announcement_tabs').removeClass('active');
    });
    $('.announcement_tabs').on('click', function () {
        $($(this).parent('.announcement_navbar_left').find('.active')).removeClass('active');
        $(this).addClass('active');
        var nav_option = $(this).attr('id');
        if (nav_option == 'show_announcements')
            showAnnouncements();
        else
            makeAnnouncements();
    });
    $('.select_item').on('change', function () {
        var id = $(this).attr('data-id');
    });
});

function makeAnnouncements() {
    var content = '';
    $('.announcement_content').html(content);
}

function showAnnouncements() {
    var content = '';
    content = '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"><input type="checkbox" class="select_item" name="checkbox" data-id="0"></span><span class="item_right list_item_section"><span class="title">From: Office of Care Coordination</span><span class="date">10-Feb-2016</span></span></span><span class="item_subject list_item_section"><span class="glyphicon glyphicon-exclamation-sign item_left"></span><span class="title arial_bold item_right"><span class="item_link" data-id="0">Protocol Change - Referral Protocol</span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right">Effective January 4, all office will begin using.</span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
    $('.announcement_content').html(content);
}

function getAnnouncementDetail() {}
