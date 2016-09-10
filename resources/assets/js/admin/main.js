$(function() {
  var $adminExamType = $('#admin-exam-type');
  var $adminExamPassword = $('#admin-exam-password');
  $adminExamType.change(function(e) {
    if ($adminExamType.val() === 'password') {
      $adminExamPassword.show();
    } else {
      $adminExamPassword.hide();
    }
  });

  var $adminExamOrders = $('#admin-exam-orders');
  var $adminExamOptions = $('#admin-exam-options');
  var $adminExamOptionExample = $('#admin-exam-option-example').attr('id', '');
  $('#admin-exam-add-option').click(function(e) {
    var options = $adminExamOptions.children().length;
    if (options >= 26) return false;

    var id = 'option-' + options;
    $option = $adminExamOptionExample.clone(true, true).removeClass('hidden');
    $option.find('label').attr('for', id).text('选项 ' + String.fromCharCode(65 + options));
    $option.find('input[type="checkbox"]').attr('name', 'answers[' + options + ']');
    $option.find('input[type="text"]').attr('id', id).attr('name', 'options[' + options + ']');
    $option.appendTo($adminExamOptions);

    $adminExamOrders.val(options + 1);

    e.preventDefault();
  });

  var $adminExamAnswers = $('#admin-exam-answers');
  var $adminExamAnswerExample = $('#admin-exam-answer-example').attr('id', '');
  $('#admin-exam-add-answer').click(function(e) {
    var answers = $adminExamAnswers.children().length;

    var id = 'answer-' + answers;
    $answer = $adminExamAnswerExample.clone(true, true).removeClass('hidden');
    $answer.find('label').attr('for', id).text('答案 ' + (answers + 1));
    $answer.find('input').attr('id', id).attr('name', 'answers[' + answers + ']');
    $answer.appendTo($adminExamAnswers);

    e.preventDefault();
  });

  var $adminExamLimits = $('#admin-exam-limits');
  var $adminExamLimitExample = $('#admin-exam-limit-example').attr('id', '');
  $('#admin-exam-add-limit').click(function(e) {
    var $limit = $adminExamLimitExample.clone(true, true).removeClass('hidden');
    var $selects = $(this).parents('.form-group').find('select');
    var type = $selects.eq(0).val();
    var language = $selects.eq(1).val();
    var name = 'limits[' + type + '][' + language + ']';
    var id = 'limit-' + type + '-' + language;

    var $input = $adminExamLimits.find('input[name="' + name + '"]');
    if ($input.length > 0) {
      $input.focus();
    } else {
      var labelText = ' 时间限制（毫秒）';
      if (type === 'memory') labelText = ' 内存限制（字节）';
      $limit.find('label').attr('for', id).text(language.charAt(0).toUpperCase() + language.slice(1) + labelText);
      $limit.find('input').attr('id', id).attr('name', name);
      $limit.appendTo($adminExamLimits);
    }

    e.preventDefault();
  });

  $('.admin-exam-remove').click(function(e) {
    e.preventDefault();
    $(this).parents('.form-group').remove();
  });
});
