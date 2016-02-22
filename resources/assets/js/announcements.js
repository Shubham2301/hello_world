$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $('#open_announcement').on('click', function () {
        $('.announcement_box').addClass('visible');
        $('#show_announcements').addClass('active');
        resetDefaults();
        showAnnouncements();
    });
    $('#close_announcement').on('click', function () {
        $('.announcement_box').removeClass('visible');
        $('.announcement_tabs').removeClass('active');
        resetDefaults();
    });
    $('.announcement_tabs').on('click', function () {
        $($(this).parent('.announcement_navbar_left').find('.active')).removeClass('active');
        $(this).addClass('active');
        var nav_option = $(this).attr('id');
        if (nav_option == 'show_announcements') {
            resetDefaults();
            showAnnouncements();
        } else {
            resetDefaults();
            makeAnnouncements();
        }
    });
    $('.announcement_content').on('change', '.select_item', function () {
        if ($("input[name='checkbox']:checked").length > 0) {
            $('.delete').addClass('active');
            $('.mark_as_read').addClass('active');

        } else {
            resetDefaults();
        }
    });
    $('.announcement_content').on('click', '.item_link', function () {
        var id = $(this).attr('data-id');
        resetDefaults();
        $('.delete').addClass('active');
        $('.back-button').addClass('active');
        getAnnouncementDetail(id);
    });
    $('.back-button').on('click', function () {
        resetDefaults();
        showAnnouncements();
    });
    $('.mark_as_read').on('click', function () {
        var id = [];
        $.each($("input[name='checkbox']:checked"), function () {
            id.push($(this).val());
        });
    });
    $('.delete').on('click', function () {
        var id = [];
        if ($('.delete').attr('id')) {
            id.push($('.delete').attr('id'));
        } else {
            $.each($("input[name='checkbox']:checked"), function () {
                id.push($(this).val());
            });
        }
    });
});

function resetDefaults() {
    $('.delete').removeClass('active');
    $('.mark_as_read').removeClass('active');
    $('.back-button').removeClass('active');
    $('.delete').removeAttr('id');
}

function makeAnnouncements() {
    $('.announcement_content').html('');
    var content = '';
    content +='<span class="make_announcement"><span class="make_row"><span class="left arial_bold">Send To</span><span class="right"><input type="text" id="send_to"></span></span><span class="make_row"><span class="left arial_bold">Title</span><span class="right"><input type="text" id="title"></span></span><span class="make_row"><span class="left arial_bold">Message</span><span class="right"><textarea name="textarea" id="message"></textarea></span></span><span class="make_row"><span class="left arial_bold">Type</span><span class="right"><span><input type="checkbox">&nbsp;General</span><span><input type="checkbox">&nbsp;News</span><span><input type="checkbox">&nbsp;Test</span></span></span><span class="make_row"><span class="left arial_bold">Priority</span><span class="right"><span><input type="checkbox">&nbsp;Normal</span><span><input type="checkbox">&nbsp;Important</span><span></span></span></span><span class="make_row"><span class="left arial_bold">Schedule</span><span class="right"><input type="text" id="schedule"></span></span><span class="make_row arial_bold"><button id="publish">Publish</button><button id="preview">Preview</button><button id="cancel">Cancel</button></span></span>';
    $('.announcement_content').html(content);
}

function showAnnouncements() {
    $('.announcement_content').html('');
    var content = '';
    content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"><input type="checkbox" class="select_item" name="checkbox" value="0"></span><span class="item_right list_item_section"><span class="title">From: Office of Care Coordination</span><span class="date">10-Feb-2016</span></span></span><span class="item_subject list_item_section"><span class="glyphicon glyphicon-exclamation-sign item_left"></span><span class="title arial_bold item_right"><span class="item_link" data-id="0">Protocol Change - Referral Protocol</span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right">Effective January 4, all office will begin using.</span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
    content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"><input type="checkbox" class="select_item" name="checkbox" value="1"></span><span class="item_right list_item_section"><span class="title">From: Office of Care Coordination</span><span class="date">10-Feb-2016</span></span></span><span class="item_subject list_item_section"><span class="glyphicon glyphicon-exclamation-sign item_left"></span><span class="title arial_bold item_right"><span class="item_link" data-id="1">Protocol Change - Referral Protocol</span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right">Effective January 4, all office will begin using.</span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
    $('.announcement_content').html(content);
}

function getAnnouncementDetail(id) {
    $('.announcement_content').html('');
    $('.delete').attr('id', id);
    var content = '';
    content = '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_right list_item_section"><span class="title">From: Office of Care Coordination</span><span class="date">10-Feb-2016</span></span></span><span class="item_subject list_item_section"><span class="title arial_bold item_right"><span class="" data-id="0">Protocol Change - Referral Protocol</span></span></span><span class="item_text list_item_section"><span class="item_right">Effective January 4, all office will begin using Referral Protocol 1.5. All offices must stop using and discard all copies of Referral Protocol 1.4 prior to that data. All question must be directed to the care coordinators office at 470-237-4512.<br><br>Thank you,<br>Office of Care Coordination</span></span></span>';
    $('.announcement_content').html(content);
}
