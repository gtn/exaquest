$(document).on('click', '.change-exam-status-button', function (event) {

    debugger;
    if (this.value == 'fachlich_release_exam') {
        if (this.getAttribute("missingquestionscount") > 0) {
            if (!confirm("Es fehlen noch " + this.getAttribute("missingquestionscount") + " Fragen. Wirklich freigeben? Zum Freigeben auf OK klicken.")) {
                event.preventDefault(); // this prevents the form from being submitted ==> status does not change
                return false;
            }
        }
    }

    let data = {
        action: this.value,
        quizid: this.attributes.quizid.value,
        courseid: this.attributes.courseid.value
    };

    $.ajax({
        method: "POST",
        url: "ajax_exams.php",
        data: data
    }).done(function () {
        // Console.log(data.action, 'ret', ret);
        // TODO: more elegant solution needed to ensure everything is updated. Instead of reload for example update the html directly
        setTimeout(() => {
            location.reload();
        }, 500);
        location.reload();
    }).fail(function (ret) {
        var errorMsg = '';
        if (ret.responseText[0] == '<') {
            // Html
            errorMsg = $(ret.responseText).find('.errormessage').text();
        }
        console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
    });
});


