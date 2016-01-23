"use strict";function changePatientInfo(){$(".change_selected_patient").text(""),$(".change_selected_patient").removeClass("view"),$(".change_selected_patient").addClass("remove"),$(".button_type_11").addClass("active"),$(".view_selected_patient").hasClass("remove")&&$(".view_selected_patient").addClass("view"),showPatientInfo()}function showPatientInfo(){$(".view_selected_patient").hasClass("view")?($(".patient_info").addClass("active"),$(".view_selected_patient").text("Hide"),$(".view_selected_patient").removeClass("view"),$(".view_selected_patient").addClass("remove")):$(".view_selected_patient").hasClass("remove")&&($(".patient_info").removeClass("active"),$(".view_selected_patient").text("View"),$(".view_selected_patient").removeClass("remove"),$(".view_selected_patient").addClass("view"),$(".change_selected_patient").text("Change"),$(".change_selected_patient").removeClass("remove"),$(".change_selected_patient").addClass("view"),$(".button_type_11").removeClass("active"))}function getPatientInfo(e){$.ajax({url:"/patients/show",type:"GET",data:$.param(e),contentType:"text/html",async:!1,success:function(e){var t=$.parseJSON(e);fillPatientInfo(t)},error:function(){alert("Error getting patient information")},cache:!1,processData:!1})}function fillPatientInfo(e){$("#patient_name").text(e.firstname),$("#patient_email").text(e.email);var t=new Date(e.birthdate),a=t.getFullYear()+"-"+t.getMonth()+"-"+t.getDate();$("#patient_dob").text(a),$("#patient_add1").text(e.addressline1+","),$("#patient_add2").text(e.addressline2+","),$("#patient_add3").text(e.city),$("#patient_phone").text(e.cellphone),$("#patient_ssn").text(e.lastfourssn),$(".selected_patient_name").text(e.firstname)}function showPreviousProvider(){var e=new Array("1","John Doe","Becker Eye","Gurgaon","Eyes"),t='<div class="col-xs-12 list_seperator" data-id="'+e[0]+'"><div class="row"><div class="col-xs-12">'+e[1]+"<br> "+e[2]+' </div><div class="col-xs-6"> </div><div class="col-xs-6"> </div></div></div>';$(".provider_near_patient_list").html(t),$(".provider_near").removeClass("glyphicon-chevron-right"),$(".provider_near").addClass("glyphicon-chevron-down")}function showProviderNear(){var e=new Array("1","John Doe","Becker Eye","Gurgaon","Eyes"),t='<div class="col-xs-12 list_seperator" data-id="'+e[0]+'"><div class="row"><div class="col-xs-12">'+e[1]+"<br> "+e[2]+' </div><div class="col-xs-6"></div><div class="col-xs-6"> </div></div></div>';$(".previous_provider_patient_list").html(t),$(".provider_previous").removeClass("glyphicon-chevron-right"),$(".provider_previous").addClass("glyphicon-chevron-down")}function showProviderInfo(e){$("#practice_name").text(e.practice_name),$("#provider_name").text(e.provider.name),$("#zipcode").text(e.provider.zip),$("#phone").text(e.provider.cellphone),$(".schedule_button").attr("data-id",e.provider.id),$(".schedule_button").attr("data-practice-id",e.practice_id);var t=e.locations,a="";t.length>0&&t.forEach(function(e){a+='<div class="practice_location"><span>'+e.addressline1+","+e.addressline1+" "+e.city+" "+e.phone+"</span></div>"}),$(".locations").html(a),$(".practice_list").removeClass("active"),$(".practice_info").addClass("active"),$(".patient_previous_information").removeClass("active"),$(".schedule_button").addClass("active")}function getProviderInfo(e){$.ajax({url:"/providers/show",type:"GET",data:$.param(e),contentType:"text/html",async:!1,success:function(e){var t=$.parseJSON(e);showProviderInfo(t)},error:function(){alert("Error getting practice information")},cache:!1,processData:!1})}function getProviders(e){$(".practice_list").addClass("active"),$(".practice_info").removeClass("active");var t=JSON.stringify(e);console.log(t),$.ajax({url:"/providers/search",type:"GET",data:$.param({data:t}),contentType:"text/html",async:!1,success:function(e){var t=$.parseJSON(e);console.log(t);var a="<p><bold>"+t.length+"<bold> results found</p><br>";t.length>0&&t.forEach(function(e){a+='<div class="col-xs-12 practice_list_item" data-id="'+e.provider_id+'"  practice-id="'+e.practice_id+'" ><div class="row content-row-margin"><div class="col-xs-6">'+e.provider_name+" <br> "+e.practice_name+' </div><div class="col-xs-6"><br>  </div></div></div>'}),$(".practice_list").html(a),$(".practice_list").addClass("active")},error:function(){alert("Error searching")},cache:!1,processData:!1})}function getOptionContent(e,t,a){var i='<div class="search_filter_item"><span class="item_type" data-stype="'+a+'">'+e+'</span>:<span class="item_value">'+t+'</span><span class="remove_option">x</span></div>';return i}function getSearchType(){var e=[];return $(".search_filter_item").each(function(){var t=$(this).children(".item_type").attr("data-stype"),a=$(this).children(".item_value").text();e.push({type:t,value:a})}),e}function scheduleAppointment(e,t){$("#form_provider_id").val(e),$("#form_practice_id").val(t),$("#form_select_provider").submit()}function getOpenSlots(){var e=991234567,t=3839,a=28632,i="1/25/2016 11:00:00 AM",n={provider_id:e,location_id:t,appointment_type:a,appointment_date:i};$.ajax({url:"/providers/openslots",type:"GET",data:$.param(n),contentType:"text/html",async:!1,success:function(e){$("#appointment-datetime").removeClass("hidden"),$("#appointment-datetime").append('<option value="0">Select Date and Time</option>')},error:function(){},cache:!1,processData:!1})}function getAppointmentTypes(){var e=991234567,t=3839,a={provider_id:e,location_id:t};$.ajax({url:"/providers/appointmenttypes",type:"GET",data:$.param(a),contentType:"text/html",async:!1,success:function(e){$("#appointment-type").removeClass("hidden"),$("#appointment-type").append('<option value="0">Select Appointment Type</option>'),$("#appointment-type").append('<option value="1">Annual Eye Exam</option>'),$("#appointment-type").append('<option value="2">Eye Exam</option>')},error:function(){},cache:!1,processData:!1})}$(document).ready(function(){var e=$("#form_patient_id").attr("value"),t={id:e};getPatientInfo(t),$(".view_selected_patient").on("click",showPatientInfo),$(".change_selected_patient").on("click",changePatientInfo),$("#change_patient_button").on("click",function(){$("#form_select_provider").attr("action","/patients"),$("#form_provider_id").prop("disabled",!0),$("#form_practice_id").prop("disabled",!0),$("#form_select_provider").submit()}),$("#search_practice_button").on("click",function(){$(".schedule_button").removeClass("active"),$(".schedule_button").attr("data-id",0),$(".schedule_button").attr("data-practice-id",0),$("#add_practice_search_option").trigger("click"),$("#search_practice_input").val("");var e=getSearchType();$(".view_selected_patient").hasClass("remove")&&showPatientInfo(),0!=e.length&&getProviders(e)}),$(".lastseenby_show").on("click",function(){$(".lastseen_content").toggleClass("active"),$(".lastseen_content").hasClass("active")?($(".lastseenby_icon").removeClass("glyphicon-chevron-right"),$(".lastseenby_icon").addClass("glyphicon-chevron-down")):($(".lastseenby_icon").removeClass("glyphicon-chevron-down"),$(".lastseenby_icon").addClass("glyphicon-chevron-right"))}),$(".referredby_show").on("click",function(){$(".referredby_content").toggleClass("active"),$(".referredby_content").hasClass("active")?($(".referredby_icon").removeClass("glyphicon-chevron-right"),$(".referredby_icon").addClass("glyphicon-chevron-down")):($(".referredby_icon").removeClass("glyphicon-chevron-down"),$(".referredby_icon").addClass("glyphicon-chevron-right"))}),$(".insurance_provider_show").on("click",function(){$(".insurance_provider_content").toggleClass("active"),$(".insurance_provider_content").hasClass("active")?($(".insurance_provider_icon").removeClass("glyphicon-chevron-right"),$(".insurance_provider_icon").addClass("glyphicon-chevron-down")):($(".insurance_provider_icon").removeClass("glyphicon-chevron-down"),$(".insurance_provider_icon").addClass("glyphicon-chevron-right"))}),$(".practice_list").on("click",".practice_list_item",function(){var e=$(this).attr("data-id"),t=$(this).attr("practice-id"),a={provider_id:e,practice_id:t};getProviderInfo(a)}),$("#change_practice_button").on("click",function(){$(".practice_list").addClass("active"),$(".practice_info").removeClass("active"),$(".schedule_button").removeClass("active"),$(".schedule_button").attr("data-id",0),$(".schedule_button").attr("data-practice-id",0),$(".patient_previous_information").addClass("active")}),$("#add_practice_search_option").on("click",function(){var e=$("#search_practice_input_type").val(),t=$("#search_practice_input_type").find(":selected").text(),a=$("#search_practice_input").val();if(""!=a){$(".view_selected_patient").hasClass("remove")&&showPatientInfo();var i=getOptionContent(t,a,e);$(".search_filter").append(i),$("#search_practice_input").val("")}}),$(".search_filter").on("click",".remove_option",function(){$(this).parent().remove()}),$(".schedule_button").on("click",function(){console.log($(this).attr("data-id"),$(this).attr("data-practice-id")),scheduleAppointment($(this).attr("data-id"),$(this).attr("data-practice-id"))}),$(".locations").on("click",".practice_location",function(){$(".practice_location").removeClass("active"),$(this).addClass("active"),getAppointmentTypes()}),$("#appointment-type").on("change",function(){getOpenSlots()}),$(".provider_near_patient").on("click",function(){$(".provider_near_patient_list").toggleClass("active"),$(".provider_near_patient_list").hasClass("active")?showPreviousProvider():($(".provider_near").removeClass("glyphicon-chevron-down"),$(".provider_near").addClass("glyphicon-chevron-right"))}),$(".previous_provider_patient").on("click",function(){$(".previous_provider_patient_list").toggleClass("active"),$(".previous_provider_patient_list").hasClass("active")?showProviderNear():($(".provider_previous").removeClass("glyphicon-chevron-down"),$(".provider_previous").addClass("glyphicon-chevron-right"))})});
