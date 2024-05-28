(function () {
  var lastModalQuestionData;

  function reloadTable() {
    // this function is defined in local/table_sql
    window.table_sql_reload();

    // hide all modals
    $('.modal:visible').modal('hide');
  }

  function showError(ret, data) {
    var errorMsg = '';
    if (ret.responseText[0] == '<') {
      // html
      errorMsg = $(ret.responseText).find('.errormessage').text();
    }
    console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
  }

  function show_modal(modalSelector) {
    var $modal = $('.table-sql-container').find(modalSelector);

    // if the modal exists inside the table, move it to the body, so it gets displayed correctly
    if ($modal.length) {
      if (!$('#tmp-modal-container').length) {
        $('<div id="tmp-modal-container"></div>').appendTo('body');
      } else {
        $('#tmp-modal-container').find(modalSelector).remove();
      }

      $modal.appendTo('#tmp-modal-container');

      // init modal
      // TODO: dem user-select eine eigene class geben
      let $user_select = $modal.find('select');

      require(['core/form-autocomplete'], function (amd) {
        amd.enhance($user_select[0], false, "", "Suchen", false, true, "Keine Auswahl");
      });
    }

    $(modalSelector).modal('show');
  }

  $(function () {
    document.querySelector('.table-sql-container').addEventListener('click', function (e) {
      if ($(e.target).is('[data-toggle="modal"]')) {

        // hack: save questiondata from last modal button
        lastModalQuestionData = JSON.parse($(e.target).closest('[data-question]').attr('data-question'));

        // move modal to body container (and remove old modal, if exists)
        show_modal($(e.target).attr('data-target'));

        e.preventDefault();
      }
    });
  });

  window.show_change_owner_popup = function (e, tableRow) {
    lastModalQuestionData = tableRow.original;
    show_modal('#changeOwnerModal' + tableRow.original.id);
  };

  $(document).on('click', '.comment-count-popup', function (e) {
    e.preventDefault();
    e.stopPropagation();

    require(['qbank_comment/comment'], function (amd) {
      var id = Math.random();
      var $clone = $(e.target).clone();
      $clone.attr('data-tmp', id);
      $clone.attr('onclick', '');
      $clone.appendTo('body');

      amd.init('[data-tmp="' + id + '"]');

      var event = new Event('click');
      $clone[0].dispatchEvent(event);

      $clone.remove();
    });
  });

  $(document).on('change', 'select.searchoptions', function () {
    document.location.href = document.location.href.replace(/([?&])filterstatus=[^&]*/, '$1').replace(/&+$/, '') + '&filterstatus=' + $(this).val();
  });

  $(document).on('click', '.exaquest-changequestionstatus', function (e) {
    e.preventDefault();

    let changestatus_value = e.currentTarget.value;
    var $modal = $(this).closest('.modal');

    var questionData = $(e.target).closest('[data-question]').length
      ? JSON.parse($(e.target).closest('[data-question]').attr('data-question'))
      : lastModalQuestionData;

    if (changestatus_value == 'open_question_for_review' || changestatus_value == 'revise_question') {
      // TODO: dem user-select eine eigene class geben
      let selecteduser = $modal.find('select').val();
      if (selecteduser == "" || selecteduser && selecteduser.length == 0) {
        alert("Es muss mindestens eine Person ausgewählt sein!");
        return false;
      }
    }

    if (changestatus_value == 'revise_question') {
      let textarea_value = $modal.find('.commenttext').val();
      if (textarea_value == '') {
        alert("Es muss ein Kommentar eingegeben werden!");
        return false;
      }
    }

    var users = $modal.find('.form-autocomplete-selection').children().map(function () {
      return $(this).attr("data-value");
    }).get();

    var data = {
      courseid: M.cfg.courseId,
      sesskey: M.cfg.sesskey,
      action: $(this).val(),
      questionbankentryid: questionData.questionbankentryid,
      questionid: questionData.questionid,
      users: users,
      commenttext: $modal.find('.commenttext').val(),
    };

    $.ajax({
      method: "POST",
      url: "ajax.php",
      data: data
    }).done(function () {
      reloadTable();
    }).fail(function (ret) {
      showError(ret, data);
    });
  });

  $(document).on('click', 'button[value="change_owner"]', function (e) {
    e.preventDefault();

    var questionData = lastModalQuestionData;
    var $modal = $(this).closest('.modal');

    var users = $modal.find('.form-autocomplete-selection').children().map(function () {
      return $(this).attr("data-value");
    }).get();

    var data = {
      courseid: M.cfg.courseId,
      sesskey: M.cfg.sesskey,
      action: $(this).val(),
      questionbankentryid: questionData.questionbankentryid,
      questionid: questionData.questionid || questionData.id,
      users: users
    };

    $.ajax({
      method: "POST",
      url: "ajax.php",
      data: data
    }).done(function () {
      reloadTable();
    }).fail(function (ret) {
      showError(ret, data);
    });
  });
})();
