$(document).on('click', '.change-exam-status-button', function () {
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
        // TODO: more elegant solution needed to ensure everything is updated. Instead of reload for example update the html directly
        setTimeout(() => {
            location.reload();
        }, 500);
        location.reload();
    }).fail(function (ret) {
        var errorMsg = '';
        if (ret.responseText[0] == '<') {
            // html
            errorMsg = $(ret.responseText).find('.errormessage').text();
        }
        console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
    });
});