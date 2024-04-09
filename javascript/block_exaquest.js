$(document).on('click', '.change-exam-status-button', function (event) {

    debugger;
    if (this.value == 'fachlich_release_exam') {
        if (this.getAttribute("missingquestionscount") > 0) {
            if (!confirm("Es fehlen noch " + this.getAttribute("missingquestionscount") + " Fragen. Wirklich freigeben? Zum Freigeben auf OK klicken.")) {
                event.preventDefault(); // This prevents the form from being submitted ==> status does not change
                return false;
            }
        }
    } else if (this.value == 'force_send_exam_to_review') {
        if (this.getAttribute("missingquestionscount") > 0) {
            if (!confirm("Es fehlen noch " + this.getAttribute("missingquestionscount") + " Fragen. Wirklich zur Fachlichen überprüfung schicken? Zum schicken auf OK klicken.")) {
                event.preventDefault(); // This prevents the form from being submitted ==> status does not change
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

$(document).on('click', '.mark-kommissionell-check-grading-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("kommissionell-check-grading-request-comment");
        if (requests != undefined) {
            // Debugger;
            document.getElementById("kommissionell-check-grading-requests").removeChild(document.getElementById("kommissionell-check-grading-request-comment-li-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'kommissionell-check-grading', this.attributes.courseid.value);
        }
    }
});

function mark_request_as_done(requestid, requesttype, courseid) {
    console.log('mark_request_as_done', requestid);
    debugger;
    let action = '';
    if (requesttype == 'exam') {
        action = 'mark_exam_request_as_done';
    } else if (requesttype == 'fill-exam') {
        action = 'mark_fill_exam_request_as_done';
    } else if (requesttype == 'check-grading') {
        action = 'mark_check_exam_grading_request_as_done';
    } else if (requesttype == 'change-grading') {
        action = 'mark_change_exam_grading_request_as_done';
    } else if (requesttype == 'grade') {
        action = 'mark_grade_request_as_done';
    } else if (requesttype == 'kommissionell-check-grading') {
        action = 'mark_kommissionell_check_grading_as_done';
    } else {
        action = 'mark_question_request_as_done';
    }

    let data = {
        requestid: requestid,
        action: action,
        courseid: courseid
    };

    $.ajax({
        method: "POST",
        url: "ajax_dashboard.php",
        data: data
    }).done(function () {
        // Console.log(data.action, 'ret', ret);
        // location.reload();
    }).fail(function (ret) {
        var errorMsg = '';
        if (ret.responseText[0] == '<') {
            // Html
            errorMsg = $(ret.responseText).find('.errormessage').text();
        }
        console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
    });
}

