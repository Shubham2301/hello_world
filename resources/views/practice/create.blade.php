<div id="create_practice" class="modal fade" role="dialog">
    <div class="modal-dialog practice_form">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title center-align">Create Practice</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                         <input id="editmode" type="hidden" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row" style="margin-left:3em">
                            <div class="col-md-2">
                                <p><strong>Name</strong></p>
                            </div>
                            <div class="col-md-8">
                                <input id="practice_name" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 ocuapps_options">
                                <p><strong>OcuApps</strong></p>
                                <span class="ocuapps_input"><input type="checkbox"> <p>Calendar Intregation</p></span>
                                <br>
                                <span class="ocuapps_input"><input type="checkbox"> <p>Data Interpretation</p></span>
                                <br>
                                <span class="ocuapps_input"><input type="checkbox"> <p>Demographics</p></span>
                                <br>
                                <span class="ocuapps_input"><input type="checkbox"> <p>Patient Notification</p></span>
                                <br>
                                <span class="ocuapps_input"><input type="checkbox"> <p>Telephony</p></span>
                                <br>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Location Details</strong></p>
                            </div>
                            <div class="col-md-2 change_location">
                            <span class="glyphicon glyphicon-chevron-up glyph_design" id="location_next"></span>
                            <span><p class="location_counter">0</p></span>
                            <span class="glyphicon glyphicon-chevron-down glyph_design" id="location_previous"></span>

                            </div>
                            <div class="col-md-2 add_location">
                                <button id="add_location" class="remove_location_btn">add +</button>
                            </div>
                            <div class="col-md-3 remove-location"><button id="add_location" class="add_location_btn">remove -</button></div>
                        </div>
                        <div class="row content-row-margin">
                            <div class="col-md-12" style="margin-left:0px;padding-left: 0px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Location Name</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="locationname" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Location Code</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="location_code" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>AddressLine1</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="addressline1" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>AddressLine2</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="addressline2" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>City</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="city" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>State</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="state" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Zip</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="zip" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Phone Number</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input class = "location_input" id="phone" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <span id="savepractice"><button type="button"  class="btn modal-action-btn" >Save</button></span>
                    <button type="button" id="dontsave" class="btn modal-action-btn" data-dismiss="modal">Don't Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
