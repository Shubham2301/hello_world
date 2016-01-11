<div id="create_practice" class="modal fade" role="dialog">
    <div class="modal-dialog practice_form">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="margin-left:40%;">Create Practice</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(array('url' => '#', 'method' => 'GET', 'id' => 'form_select_provider')) !!}
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
                            <div class="col-md-6">
                                <p><strong>Location Details</strong></p>
                            </div>
                            <div class="col-md-1 change_location"><img src="" alt="0"></div>
                            <div class="col-md-2 add_location">
                                <button id="add_location">add+</button>
                            </div>
                            <div class="col-md-3 remove-location"><img src="" alt="remove-"></div>
                        </div>
                        <div class="row content-row-margin">
                            <div class="col-md-12" style="margin-left:0px;padding-left: 0px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Location Name</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_name" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Location Code</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_code" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>AddressLine1</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_address1" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>AddressLine2</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_address2" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>City</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_city" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>State</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_state" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Zip</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_zip" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Phone Number</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="location_phone" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div style="margin-right:40%">
                    <span id="savepractice"><button type="button"  class="btn btn-primary" >Save</button></span>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Don't Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
