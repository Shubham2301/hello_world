$(document).ready(function() {
    startTimer();
    $(document).on('click', function() {
		if (!showCounter){
        clickAction();
		}
    });
});


// JavaScript Document

var sessionReset;
var warnTimeInterval;
var timeLeft;
var showCounter = false;
var sessionWarningID;
var sessionMax = 30;

// timer for session
function startTimer() {
	showCounter = false;
    sessionReset = ((sessionMax) * 60) * 1000;
    warningTimer(sessionReset);

}

//start warning timer
function warningTimer(sessionReset) {
    // calculate two minutes from the session max length
    var warningTime = sessionReset - 170000;
    timeLeft = 120;
    sessionWarningID = setInterval("warnSession()", warningTime);
}

// action when session has reached its length
function expireSession() {
	window.location = "auth/logout";
}

//action for warning
function warnSession() {
    $('#timeout').modal({
        backdrop: 'static',
        keyboard: false
    });
	showCounter = true;
    warnTimeInterval = setInterval("countDown()", 900);
}

//what to do on click which is clear initial timer and then restart.
function clickAction() {
    clearInterval(sessionWarningID);
    clearInterval(warnTimeInterval);
    startTimer();
}

// two minutes counter
function countDown() {
	showCounter = true;
    if (timeLeft == 1)
        expireSession();
    var result = parseInt(timeLeft / 60) + ':' + timeLeft % 60; //formart seconds into 00:00
	document.getElementById('warning_counter').innerHTML = result;
    timeLeft--;
}
