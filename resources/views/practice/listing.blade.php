<div class="row content-row-margin practice_list">

    <p id="search_results"><strong></strong></p>
    <div class="row search_header">
        <div class="col-md-1">
            <input type="checkbox">
        </div>
        <div class="col-md-2">
            <p style="color:black"><strong>Name</strong></p>
        </div>
        <div class="col-md-3">
            <p style="color:black"><strong>Address</strong></p>
        </div>
        <div class="col-md-3">
            <p style="color:black"><strong>Ocuapps</strong></p>
        </div>
        <div class="col-md-3">
            <input type="hidden" id="schedule_practice_img" value="{{asset('images/schedule.png')}}">
            <input type="hidden" id="delete_practice_img" value="{{asset('images/delete.png')}}">
            <p style="color:black"><strong>Pagination</strong></p>
        </div>
    </div>
    <div class="practice_search_content">
        <!--
        <div class="row search_list_item" data-id="">
            <div class="col-md-1">
                <input type="checkbox">
            </div>
            <div class="col-md-2">
                <p>Nishanth</p>
            </div>
            <div class="col-md-2">123,asd,gurgaon</div>
            <div class="col-md-1"><img src="ass.gpg"></div>
            <div class="col-md-3">
                <p>Calender Intregation</p>
            </div>
            <div class="col-md-1"><img class="schedule_practice_img" src="" ></div>
            <div class="col-md-1">
                <p>Edit</p>
            </div>
            <div class="col-md-1"><img class="delete_practice_img" src=""></div>


        </div>
        -->

    </div>

</div>
<div class="row content-row-margin practice_info" data-id="">
    <div class="col-xs-12">
        <div class="row practice_info_header">
            <div class="col-md-1">
                <button>back</button>
            </div>
            <div class="col-md-2">
                <p id="the_practice_name">Wichita Optometry</p>
            </div>
            <div class="col-md-2">
                <p style="padding-top:8px;font-size:12px;">Assign roles</p>
            </div>
            <div class="col-md-2">
                <p style="padding-top:8px;font-size:12px;">Assign User</p>
            </div>
            <div class="col-md-1">
                <button style="padding-top:8px;font-size:12px;" id ="editPractice" type="button"  data-toggle="modal" data-target="#create_practice" data-id="">Edit</button>
            </div>
            <div class="col-md-1"><img src="" alt="delete" style="padding-top:4px;float:left;"></div>
            <div class="col-md-3"></div>
        </div>
        <div class="row location_info_header">
            <div class="col-md-3">
                <p style="color:black;">Location</p>
            </div>
            <div class="col-md-2">
                <img src="" alt="add+">
            </div>
            <div class="col-md-7">
                <p style="margin-left:100px;color:black;">Users</p>
            </div>
        </div>
        <div class="practice_location_item_list">
            <div class="row practice_location_item">
                <div class="col-md-3">
                    <p>WichitaOptometry_3801</p>
                    <br>
                    <br>
                    <p>2330 N amidon</p>
                    <p>wichita,kanas</p>
                    <br>
                    <p>316-942-7496 Fax</p>
                </div>
                <div class="col-md-2">
                    <p>Assign roles </p>
                    <p>Assign users</p>
                    <p>edit</p>
                    <img src="" alt="x">
                </div>
                <div class="col-md-7">
                    <div class="practice_users">
                        <input type="checkbox"> <span><p class="user_name">practice user1</p></span><span><img src="" alt="0"></span>
                    </div>

                </div>
            </div>
        </div>










    </div>
</div>
