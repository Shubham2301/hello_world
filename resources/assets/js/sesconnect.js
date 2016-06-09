$(document).ajaxError(function(e, xhr, settings) {
  logError(settings.url + ':' + xhr.status + '\n' + xhr.responseText);
});

$(document).ready(function () {
    window.onerror = function(message, file, line) {
      logError(file + ':' + line + '\n' + message);
    };
})

function logError(report) {
    $.ajax({
    type: 'POST',
    url: '/errors/directmail',
    data: JSON.stringify({context: navigator.userAgent, report: report}),
    contentType: 'application/json; charset=utf-8'
    });
}