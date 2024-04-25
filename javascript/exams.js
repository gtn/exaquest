
$(document).ready(function () {
    $('.assign_change_exam_grading_form').on('submit', function (event) {
        debugger
              // Access the form that triggered the event
        var $form = $(this);

        // Find a textarea within this form
        var $textarea_value = $form.find('textarea').val();

        if ($textarea_value == '') {
            alert("Es muss ein Kommentar eingegeben werden.");
            event.preventDefault(); // Optional: prevents the actual form submission
            return false;
        } else {
            return true;
        }
    });
});
