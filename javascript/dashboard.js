/* jshint esversion: 6 */
/* globals $:false */
/* global console*/
/* global confirm*/
/* global alert*/

$(document).on('click', '.selectallornone-userselection', function () {
    let checkboxes = this.parentElement.getElementsByClassName("userselectioncheckbox");
    if (checkboxes != undefined) {
        if (checkboxes[0].checked == true) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        } else {
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }
    }
});


// This is the hacky but simple solution to getting the button to where it belongs. The buttion is rendered using functions from moodle-core which use echo.
// This cannot be included nicely into mustache ==> after rendering, put the button to the correct position with javascript.
$("#createnewquestion_button").appendTo("#dashboard_create_questions_div");

// Clone it, to not remove it from other locations
const element = $("#createnewquestion_button");
const clone = element.clone(true, true);
clone.appendTo("#popup_create_questions_div");


$(document).on('click', '#popup_create_questions_div', function () {
    const requestsModal = document.getElementById("questionsForMeToCreateModal");
    requestsModal.removeAttribute("tabindex");
    // The clicked button creates a second popup. The 2 opened popups interefere with each other. By removing the tabinex attribute of the lower one, the problem is solved.
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

$(document).on('click', '.mark-question-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("request-comment");
        if (requests != undefined) {
            document.getElementById("requests").removeChild(document.getElementById("request-comment-li-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'question', this.attributes.courseid.value);
        }
    }
});


$(document).on('click', '.mark-exam-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("request-comment");
        if (requests != undefined) {
            document.getElementById("requests").removeChild(document.getElementById("request-comment-p-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'exam', this.attributes.courseid.value);
        }
    }
});

$(document).on('click', '.mark-fill-exam-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("fill-exam-request-comment");
        if (requests != undefined) {
            document.getElementById("fill-exam-request").removeChild(document.getElementById("fill-exam-request-comment-li-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'fill-exam', this.attributes.courseid.value);
        }
    }
});
$(document).on('click', '.mark-grade-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("grade-request-comment");
        if (requests != undefined) {
            debugger;
            document.getElementById("grade-request").removeChild(document.getElementById("grade-request-comment-li-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'grade', this.attributes.courseid.value);
        }
    }
});

$(document).on('click', '.mark-check-grading-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("check-grading-request-comment");
        if (requests != undefined) {
            debugger;
            document.getElementById("check-grading-requests").removeChild(document.getElementById("check-grading-request-comment-li-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'check-grading', this.attributes.courseid.value);
        }
    }
});
$(document).on('click', '.mark-change-grading-request-as-done-button', function () {
    if (confirm("Wirklich als erledigt markieren?")) {
        let requests = this.parentElement.parentElement.getElementsByClassName("change-grading-request-comment");
        if (requests != undefined) {
            debugger;
            document.getElementById("change-grading-requests").removeChild(document.getElementById("change-grading-request-comment-li-" + this.getAttribute("requestid")));
            // Remove that entry from the database with ajax
            mark_request_as_done(this.getAttribute("requestid"), 'change-grading', this.attributes.courseid.value);
        }
    }
});


$(document).ready(function () {
    $('#requestquestionsform').on('submit', function () {
        let $selecteduser = $('#id_selectedusers').val();
        let textarea_value = $('.requestquestionscomment').val();

        if ($selecteduser && $selecteduser.length == 0 || textarea_value == '') {
            alert("Es muss mindestens ein Fragenersteller ausgewählt sein und ein Kommentar eingegeben werden.");
            return false;
        } else {
            return true;
        }
    });

    // $('.form-autocomplete-selection').on('keypress', function () {
    //
    //     var textarea_value = $('.requestquestionscomment').val();
    //     if (textarea_value != '') {
    //         $('.requestquestionssubmit').attr('disabled', false);
    //     } else {
    //         $('.requestquestionssubmit').attr('disabled', true);
    //     }
    // });

    // $('.requestquestionscomment').on('keyup', function () {
    //     var textarea_value = $('.requestquestionscomment').val();
    //     if (textarea_value != '') {
    //         $('.requestquestionssubmit').attr('disabled', false);
    //     } else {
    //         $('.requestquestionssubmit').attr('disabled', true);
    //     }
    // });
});
