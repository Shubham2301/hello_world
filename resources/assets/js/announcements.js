$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $('#menu-announcements').on('click', function () {
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
            makeAnnouncementForm();
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
    $('.announcement_content').on('change', '.type', function () {
        $('.type').prop('checked', false);
        $(this).prop('checked', true);
    });
    $('.announcement_content').on('change', '.priority', function () {
        $('.priority').prop('checked', false);
        $(this).prop('checked', true);
    });
});

function resetDefaults() {
    $('.delete').removeClass('active');
    $('.mark_as_read').removeClass('active');
    $('.back-button').removeClass('active');
    $('.delete').removeAttr('id');
}

function makeAnnouncementForm() {
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
            content += '<span class="make_announcement"><span class="make_row"><span class="left arial_bold">Send To</span><span class="right"><select id="send_to">';
            for (var i = 0; i < roles.length; i++) {
                content += '<option value="' + roles[i][1] + '">' + roles[i][0] + '</option>';
            }
            content += '</select></span></span><span class="make_row"><span class="left arial_bold">Title</span><span class="right"><input type="text" id="title"></span></span><span class="make_row"><span class="left arial_bold">Message</span><span class="right"><textarea name="textarea" id="message"></textarea></span></span><span class="make_row"><span class="left arial_bold">Type</span><span class="right"><span><input type="checkbox" class="type" value="General">&nbsp;General</span><span><input type="checkbox" class="type" value="News">&nbsp;News</span><span><input type="checkbox" class="type" value="Test">&nbsp;Test</span></span></span><span class="make_row"><span class="left arial_bold">Priority</span><span class="right"><span><input type="checkbox" class="priority" value="Normal">&nbsp;Normal</span><span><input type="checkbox" class="priority" value="Important">&nbsp;Important</span><span></span></span></span><span class="make_row"><span class="left arial_bold">Schedule</span><span class="right"><input type="text" id="schedule"></span></span><span class="make_row arial_bold"><button id="publish">Publish</button><button id="preview">Preview</button><button id="cancel">Cancel</button></span></span>';
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
            announcements.forEach(function (announcement) {
                content += '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_left"><input type="checkbox" class="select_item" name="checkbox" value="' + announcement.id + '"></span><span class="item_right list_item_section"><span class="title">From: ' + announcement.from + '</span><span class="date">' + announcement.schedule + '</span></span></span><span class="item_subject list_item_section">';
                if (announcement.read == 0) {
                    content += '<span class="glyphicon glyphicon-exclamation-sign item_left"></span>';
                } else {
                    content += '<span class="item_left"></span>';
                }
                content += '<span class="title arial_bold item_right"><span class="item_link" data-id="' + announcement.id + '">' + announcement.title + '</span></span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right">' + announcement.excerpt + '</span></span><span class="item_text list_item_section"><span class="item_left"></span><span class="item_right"><span class="section_separator"></span></span></span></span>';
            });
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
            content = '<span class="announcement_list_item arial"><span class="item_header list_item_section"><span class="item_right list_item_section"><span class="title">From: ' + announcement.from + '</span><span class="date">' + announcement.schedule + '</span></span></span><span class="item_subject list_item_section"><span class="title arial_bold item_right"><span class="" data-id="' + announcement.id + '">' + announcement.title + '</span></span></span><span class="item_text list_item_section"><span class="item_right">' + announcement.message + '</span></span></span>';
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

function makeAnnouncement() {
    var title = $('#title').val();
    var message = $('#message').val();
    var type = $('.type:checked').val();
    var priority = $('.priority:checked').val();
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
            $('.delete').addClass('active');
            $('.back-button').addClass('active');
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

}
