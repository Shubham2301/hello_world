$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    countUnreadAnnouncements();

    $('#menu-announcements').on('click', function () {
        $('.announcement_box').addClass('visible');
        $('#show_announcements').addClass('active');
        $('.content-left').addClass('sidebar_border_color');
        resetDefaults();
        showAnnouncements();
    });
    $('#close_announcement').on('click', function () {
        $('.announcement_box').removeClass('visible');
        $('.announcement_tabs').removeClass('active');
        $('.content-left').removeClass('sidebar_border_color');
        resetDefaults();
    });
    $('.announcement_content').on('click', '#close_announcement', function () {
        $('.announcement_box').removeClass('visible');
        $('.announcement_tabs').removeClass('active');
        $('.content-left').removeClass('sidebar_border_color');
        resetDefaults();
    });
    $('.announcement_content').on('click', '#edit_preview', function () {
        var title = $('#title').val();
        var message = $('#message').val();
        var type = $('#type').val();
        var priority = $('#priority').val();
        var schedule = $('#schedule').val();
        var send_to = $('#send_to').val();
        var user_name = $('#user_name').val();
        makeAnnouncementForm();
        $('#title').val(title);
        $('#message').val(message);
        $('#schedule').val(schedule);
        $('#send_to').val(send_to);
        $('#user_name').val(user_name);
        $('#type').val(type);
        $('#priority').val(priority);
        $("input:checkbox[value=" + type + "]").attr("checked", true);
        $("input:checkbox[value=" + priority + "]").attr("checked", true);
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
            makeAnnouncementForm();
        }
    });
    $('.announcement_content').on('change', '.select_item', function () {
        if ($("input[name='checkbox']:checked").length > 0) {
            $('.delete').addClass('active');
            $('.mark_as_read').addClass('active');
            $('.sent_by_me').removeClass('active');

        } else {
            resetDefaults();
        }
    });
    $('.announcement_content').on('click', '.item_link', function () {
        var id = $(this).attr('data-id');
        if ($('#sent_by_me').hasClass('sent_to_me')) {
            $('.back-button').addClass('active');
            $('.sent_by_me').removeClass('active');
            getAnnouncementDetail(id);
        } else {
            resetDefaults();
            $('.delete').addClass('active');
            $('.back-button').addClass('active');
            $('.sent_by_me').removeClass('active');
            getAnnouncementDetail(id);
        }

    });
    $('.back-button').on('click', function () {
        if ($('#sent_by_me').hasClass('sent_to_me')) {
            announcementByUserList()
        } else {
            resetDefaults();
            showAnnouncements();
        }
    });
    $('.mark_as_read').on('click', function () {
        var id = [];
        $.each($("input[name='checkbox']:checked"), function () {
            id.push($(this).val());
        });
        resetDefaults();
        markAnnouncement(id);
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
        resetDefaults();
        archiveAnnouncement(id);
    });
    $('.announcement_content').on('click', '#publish', function () {
        makeAnnouncement();
    });
    $('.announcement_content').on('click', '#preview', function () {
        previewAnnouncement();
    });
    $('.announcement_content').on('change', '.type', function () {
        $('.type').prop('checked', false);
        $(this).prop('checked', true);
        $('#type').val($(this).val());
    });
    $('.announcement_content').on('change', '.priority', function () {
        $('.priority').prop('checked', false);
        $(this).prop('checked', true);
        $('#priority').val($(this).val());
    });
    $('#sent_by_me').on('click', function () {
        if ($('#sent_by_me').hasClass('sent_to_me')) {
            resetDefaults();
            showAnnouncements();
        } else {
            $('#sent_by_me>span').html('Sent by me');
            $('#sent_by_me').addClass('sent_to_me');
            announcementByUserList();
        }
    });
});

function countUnreadAnnouncements() {

    var count = 0;
    $.ajax({
        url: '/announcements/list',
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var announcements = $.parseJSON(e);
            if (announcements.length != 0) {
                announcements.forEach(function (announcement) {
                    if (announcement.read == 0) {
                        count++;
                    }
                });
                $('#menu-notification-announcements>.notification_text').html(count);
                $('#menu-notification-announcements').addClass('active');
            }
            if(count == 0) {
                $('#menu-notification-announcements').removeClass('active');
            }
        },
        error: function () {},
        cache: false,
        processData: false
    });

}

function resetDefaults() {
    $('.delete').removeClass('active');
    $('.mark_as_read').removeClass('active');
    $('.back-button').removeClass('active');
    $('.sent_by_me').addClass('active');
    $('.delete').removeAttr('id');
    $('#sent_by_me>span').html('Sent to me');
    $('#sent_by_me').removeClass('sent_to_me')
}

function makeAnnouncementForm() {
    $('.sent_by_me').removeClass('active');
    $('.announcement_content').html('');
    var content = '';
    $.ajax({
        url: '/announcements/create',
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var roles = $.parseJSON(e);
            content += '<span class="make_announcement"><span class="item_left"></span><span class="item_right"><span class="make_row"><span class="left arial_bold">Send To</span><span class="right"><select id="send_to">';
            for (var i = 0; i < roles.role_data.length; i++) {
                content += '<option value="' + roles.role_data[i][1] + '">' + roles.role_data[i][0] + '</option>';
            }
            content += '</select></span></span><span class="make_row"><span class="left arial_bold">Schedule</span><span class="right"><input type="text" id="schedule"></span></span><span class="make_row"><span class="left arial_bold">Title</span><span class="right"><input type="text" id="title"></span></span><span class="make_row"><span class="left arial_bold">Message</span><span class="right"><textarea name="textarea" id="message"></textarea></span></span><span class="make_row"><span class="left arial_bold">Type</span><span class="right"><span><input type="radio" class="type" value="General">&nbsp;General</span><span><input type="radio" class="type" value="News">&nbsp;News</span><span><input type="radio" class="type" value="Test">&nbsp;Test</span></span></span><span class="make_row"><span class="left arial_bold">Priority</span><span class="right"><span><input type="radio" class="priority" value="Normal">&nbsp;Normal</span><span><input type="radio" class="priority" value="Important">&nbsp;Important</span><span></span></span></span><span class="make_row arial_bold button_row"><button id="publish">Publish</button><button id="preview">Preview</button><button id="close_announcement">Cancel</button></span></span></span><input type="hidden" value="' + roles.user + '" id="user_name"><input type="hidden" value="" id="priority"><input type="hidden" value="" id="type">';
            $('.announcement_content').html(content);
            var date = new Date();
            $('#schedule').datetimepicker({
                format: 'YYYY/MM/DD',
                minDate: date,
                defaultDate: date
            });
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function showAnnouncements() {

    $('.announcement_content').html('');
    var content = '';
    $.ajax({
        url: '/announcements/list',
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var announcements = $.parseJSON(e);
            if (announcements.length == 0) {
                content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"></span><span class="item_right list_item_section"><span class="from">No Announcements</span><span class=" from date"></span></span></span><span class="item_subject list_item_section"><span class="item_left"></span><span class="title arial_bold item_right"><span class="item_link" data-id=""></span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right excerpt"></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
            } else {
                announcements.forEach(function (announcement) {
                    content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"><input type="checkbox" class="select_item" name="checkbox" value="' + announcement.id + '"></span><span class="item_right list_item_section"><span class="from">From: ' + announcement.from + '</span><span class=" from date">' + announcement.schedule + '</span></span></span><span class="item_subject list_item_section">';
                    if (announcement.read == 0) {
                        content += '<span class="glyphicon glyphicon-exclamation-sign item_left ' + announcement.priority + '"></span>';
                    } else {
                        content += '<span class="item_left"></span>';
                    }
                    content += '<span class="title arial_bold item_right"><span class="item_link" data-id="' + announcement.id + '">' + announcement.title + '</span></span></span><span class="item_text list_item_section"><span class="item_left"></span>';
                    if (announcement.message == announcement.excerpt)
                        content += '<span class="item_right excerpt">' + announcement.excerpt + '</span>';
                    else
                        content += '<span class="item_right excerpt">' + announcement.excerpt + '......</span>';
                    content += '</span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
                });
            }
            $('.announcement_content').html(content);
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function getAnnouncementDetail(id) {
    $('.announcement_content').html('');
    $('.delete').attr('id', id);
    if ($('#sent_by_me').hasClass('sent_to_me')) {
        $('.delete').removeClass('active');
        $('.sent_by_me').removeClass('active');
    }
    var content = '';
    var formData = {
        'id': id
    };
    $.ajax({
        url: '/announcements/show',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var announcement = $.parseJSON(e);
            content = '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"></span><span class="item_right list_item_section">';
            if ($('#sent_by_me').hasClass('sent_to_me'))
                content += '<span class="title">To: ' + announcement.to + '</span>';
            else
                content += '<span class="title">From: ' + announcement.from + '</span>';
            content += '<span class="date">' + announcement.schedule + '</span></span></span><span class="item_subject list_item_section"><span class="item_left"></span><span class="title arial_bold item_right"><span class="" data-id="' + announcement.id + '">' + announcement.title + '</span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right">' + announcement.message + '</span></span></span>';
            $('.announcement_content').html(content);
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    countUnreadAnnouncements();
}

function makeAnnouncement() {
    var title = $('#title').val();
    var message = $('#message').val();
    if (title == '' || message == '') {
        $('p.alert_message').text('Please fill all fields');
        $('#alert').modal('show');
        return;
    }
    var type = $('#type').val();
    var priority = $('#priority').val();
    var schedule = $('#schedule').val();
    var send_to = $('#send_to').val();
    var formData = {
        'title': title,
        'message': message,
        'type': type,
        'priority': priority,
        'schedule': schedule,
        'send_to': send_to
    };
    $.ajax({
        url: '/announcements/store',
        type: 'GET',
        data: $.param(formData),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var id = $.parseJSON(e);
            resetDefaults();
            //            $('.delete').removeClass('active');
            //            $('.back-button').removeClass('active');
            //            $('.sent_by_me').removeClass('active');
            $('.announcement_content').html('');
            $('.back-button').addClass('active');
            $('#sent_by_me').addClass('sent_to_me')
            $('.view').addClass('active');
            $('.make').removeClass('active');
            getAnnouncementDetail(id);
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}

function archiveAnnouncement(id) {
    var archiveId = {};
    var i = 0;
    id.forEach(function (item) {
        archiveId[i] = item;
        i++;
    });
    $.ajax({
        url: '/announcements/archive',
        type: 'GET',
        data: $.param(archiveId),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            showAnnouncements();
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    countUnreadAnnouncements();

}

function markAnnouncement(id) {
    var archiveId = {};
    var i = 0;
    id.forEach(function (item) {
        archiveId[i] = item;
        i++;
    });
    $.ajax({
        url: '/announcements/update',
        type: 'GET',
        data: $.param(archiveId),
        contentType: 'text/html',
        async: false,
        success: function (e) {
            showAnnouncements();
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
    countUnreadAnnouncements();
}

function previewAnnouncement() {
    var title = $('#title').val();
    var message = $('#message').val();
    var type = $('#type').val();
    if (title == '' || message == '') {
        $('p.alert_message').text('Please fill all fields');
        $('#alert').modal('show');
        return;
    }
    var priority = $('#priority').val();
    var schedule = $('#schedule').val();
    var send_to = $('#send_to').val();
    var user_name = $('#user_name').val();
    $('.announcement_content').html('');
    var content = '';
    content = '<span class="preview_announcement_list_item arial"><span class="item_left"></span><span class="item_right"><span class="item_header list_item_section"><span class="item_right list_item_section"><span class="title">From: ' + user_name + '</span><span class="date">' + schedule + '</span></span></span><span class="item_subject list_item_section"><span class="title arial_bold item_right"><span class="" data-id="">' + title + '</span></span></span><span class="item_text list_item_section"><span class="item_right">' + message + '</span></span><span class="make_row arial_bold button_row"><button id="publish">Publish</button><button id="edit_preview">Cancel</button></span></span><input type="hidden" value="' + title + '" id="title"><input type="hidden" value="' + message + '" id="message"><input type="hidden" value="' + schedule + '" id="schedule"><input type="hidden" value="' + priority + '" id="priority"><input type="hidden" value="' + type + '" id="type"><input type="hidden" value="' + send_to + '" id="send_to"><input type="hidden" value="' + user_name + '" id="user_name">';
    $('.announcement_content').html(content);
}

function announcementByUserList() {

    $('.announcement_content').html('');
    $('.back-button').removeClass('active');
    $('#sent_by_me').addClass('active');
    var content = '';
    $.ajax({
        url: '/announcements/announcementbyuserlist',
        type: 'GET',
        data: '',
        contentType: 'text/html',
        async: false,
        success: function (e) {
            var announcements = $.parseJSON(e);
            if (announcements.length == 0) {
                content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"></span><span class="item_right list_item_section"><span class="from">No Announcements</span><span class=" from date"></span></span></span><span class="item_subject list_item_section"><span class="item_left"></span><span class="title arial_bold item_right"><span class="item_link" data-id=""></span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right excerpt"></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
            } else {
                announcements.forEach(function (announcement) {
                    content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"></span><span class="item_right list_item_section"><span class="from">To: ' + announcement.from + '</span><span class=" from date">' + announcement.schedule + '</span></span></span><span class="item_subject list_item_section">';
                    content += '<span class="item_left"></span>';
                    content += '<span class="title arial_bold item_right"><span class="item_link" data-id="' + announcement.id + '">' + announcement.title + '</span></span></span><span class="item_text list_item_section"><span class="item_left"></span>';
                    if (announcement.message == announcement.excerpt)
                        content += '<span class="item_right excerpt">' + announcement.excerpt + '</span>';
                    else
                        content += '<span class="item_right excerpt">' + announcement.excerpt + '.....</span>';
                    content += '</span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
                });
            }
            $('.announcement_content').html(content);
        },
        error: function () {
            $('p.alert_message').text('Error');
            $('#alert').modal('show');
        },
        cache: false,
        processData: false
    });
}
