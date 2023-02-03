$(document).on('click', '.change-exam-status-button', function () {
    debugger
    let data = {
        action: this.value,
        quizid: this.attributes.quizid.value
    };

    $.ajax({
        method: "POST",
        url: "ajax_exams.php",
        data: data
    }).done(function () {
        //console.log(data.action, 'ret', ret);
        // location.reload();
    }).fail(function (ret) {
        var errorMsg = '';
        if (ret.responseText[0] == '<') {
            // html
            errorMsg = $(ret.responseText).find('.errormessage').text();
        }
        console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
    });
    location.reload();
});