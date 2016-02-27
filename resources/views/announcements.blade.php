<div class="announcement_box">
    <span class="announcement_row arial_bold">
        <span class="left"></span>
        <span class="right announcement_title title">
            <span>Announcements</span>
            <span id="close_announcement">
                <img class="active" src="{{URL::asset('images/close-active.png')}}">
                <img class="natural" src="{{URL::asset('images/close-natural.png')}}">
            </span>
        </span>
    </span>
    <span class="announcement_row">
        <span class="left"></span>
        <span class="right announcement_navbar arial">
            <span class="announcement_navbar_left">
                <span class="announcement_tabs" id="show_announcements">View</span>
                <span class="announcement_tabs" id="make_announcements">Make</span>
            </span>
            <span class="announcement_navbar_right">
                <span class="sent_by_me" id="sent_by_me"><span>Sent to me</span><img src="{{URL::asset('images/toggle-icon.png')}}"></span>
                <button class="back-button" >Back</button>
                <button class="mark_as_read" >Mark as read</button>
                <span class="delete" data-toggle="tooltip" title="Delete" data-placement="bottom">
                    <img class="cancel_image" src="{{URL::asset('images/delete-natural.png')}}">
                    <img class="cancel_image-hover" src="{{URL::asset('images/delete-natural-hover.png')}}">
                </span>
            </span>
        </span>
    </span>
    <span class="announcement_content">
    </span>
</div>
