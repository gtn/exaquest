$(document).on('click', '.selectallornone-userselection', function () {
    let checkboxes = this.parentElement.getElementsByClassName("userselectioncheckbox");
    if(checkboxes != undefined){
        if(checkboxes[0].checked == true){
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }else{
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }
    }
});

// This is the hacky but simple solution to getting the button to where it belongs. The buttion is rendered using functions from moodle-core which use echo.
// This cannot be included nicely into mustache ==> after rendering, put the button to the correct position with javascript.
$("#createnewquestion_button").appendTo("#dashboard_create_questions_div");

// clone it, to not remove it from other locations
// const element = $("#createnewquestion_button");
// const clone = element.clone(true, true);
// clone.appendTo("#popup_create_questions_div");


$(document).on('click', '.mark-request-as-done-button', function () {
    let requests = this.parentElement.parentElement.getElementsByClassName("request-comment");
    if(requests != undefined){
        debugger
        document.getElementById("modal-body-requests").removeChild(document.getElementById("request-comment-p-" + this.getAttribute("requestid")));
        // remove that entry from the database with ajax
        mark_request_as_done(this.getAttribute("requestid"));
    }
});

function mark_request_as_done(requestid) {
    console.log('mark_request_as_done', requestid);

    let data = {
        requestid: requestid,
        action: 'mark_request_as_done'
    };

    $.ajax({
        method: "POST",
        url: "ajax_dashboard.php",
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
}