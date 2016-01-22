"use strict";function getLocationData(){validateLocation()?locations.push({locationname:$("#locationname").val(),location_code:$("#location_code").val(),addressline1:$("#addressline1").val(),addressline2:$("#addressline2").val(),city:$("#city").val(),state:$("#state").val(),zip:$("#zip").val(),phone:$("#phone").val()}):alert("all fields are required")}function validateLocation(){return""==$("#locationname").val()?!1:""==$("#location_code").val()?!1:""==$("#addressline1").val()?!1:""==$("#addressline2").val()?!1:""==$("#city").val()?!1:""==$("#state").val()?!1:""==$("#zip").val()?!1:""==$("#phone").val()?!1:!0}function setNewLocationField(){$("#locationname").val(""),$("#location_code").val(""),$("#addressline1").val(""),$("#addressline2").val(""),$("#city").val(""),$("#state").val(""),$("#zip").val(""),$("#phone").val("")}function getPractices(a){var t=JSON.stringify(a);$("#schedule_practice_img").val(),$("#delete_practice_img").val();$(".practice_info").removeClass("active"),$(".practice_action").addClass("active"),$.ajax({url:"/practices/search",type:"GET",data:$.param({data:t}),contentType:"text/html",async:!1,success:function(a){var t=$.parseJSON(a),e="";$("#search_results").text(t.length+" Results found"),t.length>0&&(t.forEach(function(a){e+='<div class="row search_item" data-id="'+a.id+'"><div class="col-xs-3 search_name"><input type="checkbox">&nbsp;&nbsp;<p>'+a.name+'</p></div><div class="col-xs-3">'+a.address+'</div><div class="col-xs-1"></div><div class="col-xs-3"><p>'+a.ocuapps+'</p></div> <div class="col-xs-2 search_edit"><p ><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background: #e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;"></span></p>&nbsp;&nbsp;<p>Edit</p>&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" area-hidden="true" style="background: maroon;color: white;padding: 3px;border-radius: 3px;font-size: 0.9em;"></span></div></div>'}),$(".practice_list").addClass("active"),$(".practice_search_content").html(e))},error:function(){alert("Error searching")},cache:!1,processData:!1})}function createPractice(a){var t=JSON.stringify(a);$.ajax({url:"/practices/create",type:"GET",data:$.param({data:t}),contentType:"text/html",async:!1,success:function(a){var t=$.parseJSON(a);$("#dontsave").trigger("click");var e={practice_id:t};getPracticeInfo(e)},error:function(){alert("Error searching")},cache:!1,processData:!1})}function popupLocationFields(a){$("#locationname").val(a.locationname),$("#location_code").val(a.location_code),$("#addressline1").val(a.addressline1),$("#addressline2").val(a.addressline2),$("#city").val(a.city),$("#state").val(a.state),$("#zip").val(a.zip),$("#phone").val(a.phone)}function refreshAttributes(){locations=[],setNewLocationField(),$(".location_counter").text(0),$("#practice_name").val("")}function getPracticeInfo(a){$.ajax({url:"/practices/show",type:"GET",data:$.param(a),contentType:"text/html",async:!1,success:function(a){var t=$.parseJSON(a);showPracticeInfo(t)},error:function(){alert("Error getting practice information")},cache:!1,processData:!1})}function showPracticeInfo(a){currentPractice=a,$("#editPractice").attr("data-id",a.practice_id),$("#the_practice_name").text(a.practice_name);var t="";a.locations.forEach(function(e){t+='<div class="row practice_location_item"><div class="col-xs-3 practice_info"><p>'+e.locationname+"</p><p>"+e.addressline1+"<br>"+e.addressline2+"</p><p>"+e.phone+'</p></div><div class="col-xs-4 practice_assign"><p>Assign roles </p><p>Assign users</p><p>Edit</p><br><center><span class="glyphicon glyphicon-remove" area-hidden="true" style="background: maroon;color: white;padding: 3px;border-radius: 3px;font-size: 0.9em;"></span></center></div><div class="col-xs-5"><div class="row">',a.users.forEach(function(a){t+='<div class="col-xs-12 practice_users "><p style="width: 100%;"><input type="checkbox"><span>'+a.firstname+'</span><span class="glyphicon glyphicon-triangle-bottom" area-hidden="true" style="background:#e0e0e0;color: grey;padding: 3px;border-radius: 3px;opacity: 0.8;font-size: 0.9em;float: right;margin-bottom: 5px;"></span></p></div>'}),t+="</div></div></div>"}),$(".practice_location_item_list").html(t),$(".practice_list").removeClass("active"),$(".practice_info").addClass("active"),$(".practice_action").removeClass("active")}function setEditMode(){locations=currentPractice.locations,popupLocationFields(locations[0]),$(".location_counter").text(0),$("#practice_name").val(currentPractice.practice_name)}function updatePracticedata(a){var t=JSON.stringify(a);$.ajax({url:"/practices/edit",type:"GET",data:$.param({data:t}),contentType:"text/html",async:!1,success:function(a){var t=$.parseJSON(a);$("#dontsave").trigger("click");var e={practice_id:t};getPracticeInfo(e)},error:function(){alert("Error searching")},cache:!1,processData:!1})}$(document).ready(function(){$("#search_practice_button").on("click",function(){var a=$("#search_practice_input").val(),t={value:a};getPractices(t)}),$("#back").on("click",function(){$(".practice_info").removeClass("active"),$(".practice_action").addClass("active")}),$("#savepractice").on("click",function(){var a=[],t=$("#practice_name").val();if(""!=t){var e=parseInt($(".location_counter").text());!locations[e]&&validateLocation()&&getLocationData();var c=$("#editmode").val();"-1"==c?(a.push({practice_name:$("#practice_name").val(),locations:locations}),setNewLocationField(),locations=[],createPractice(a)):(a.push({practice_id:c,practice_name:$("#practice_name").val(),locations:locations}),updatePracticedata(a))}else alert("practice name is missing")}),$("#add_location").on("click",function(){var a=parseInt($(".location_counter").text());locations[a]||getLocationData(),setNewLocationField();var a=parseInt($(".location_counter").text());$(".location_counter").text(locations.length)}),$("#location_next").on("click",function(){var a=parseInt($(".location_counter").text());a<locations.length-1&&($(".location_counter").text(a+1),popupLocationFields(locations[a+1]))}),$("#location_previous").on("click",function(){var a=parseInt($(".location_counter").text());a>0&&($(".location_counter").text(a-1),popupLocationFields(locations[a-1]))}),$(".location_input").on("change",function(){var a=parseInt($(".location_counter").text()),t=$(this).attr("id"),e=$(this).val();locations[a]&&(locations[a][t]=e)}),$(".remove-location").on("click",function(){var a=parseInt($(".location_counter").text());locations.splice(a,1);var t=locations.length;if(t>0)a>0?(popupLocationFields(locations[a-1]),$(".location_counter").text(a-1)):(popupLocationFields(locations[a+1]),$(".location_counter").text(a+1));else{setNewLocationField();parseInt($(".location_counter").text());$(".location_counter").text(0)}}),$(".practice_list").on("click",".search_item",function(){var a=$(this).attr("data-id"),t={practice_id:a};getPracticeInfo(t)}),$("#openModel").on("click",function(){var a=-1;$("#editmode").val(a),refreshAttributes()}),$("#editPractice").on("click",function(){var a=$(this).attr("data-id");$("#editmode").val(a),setEditMode()})});var locations=[],currentPractice=[];
