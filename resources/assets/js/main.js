$(function() {
  // remove 'no-js' class on html tag
  $('html').removeClass('no-js');
  // bind navicon click handler
  var $pageWrapper = $('.page-wrapper');
  $('.navicon').click(function(e) {
    e.preventDefault();
    $this = $(this);
    $this.toggleClass('is-open');
    if ($this.hasClass('is-open')) {
      $pageWrapper.addClass('is-open');
    } else {
      $pageWrapper.removeClass('is-open');
    }
  });
  // render LaTex with  KaTex if the boswer not IE 8 / 7 / 6
  if (!$('html').hasClass('lt-ie9')) {
    renderMathInElement(document.body, {
      delimiters: [{ left: '\\[', right: '\\]', display: false }, { left: '$', right: '$', display: false }, { left: '\\(', right: '\\)', display: true }, { left: '$$', right: '$$', display: true }]
    });
  }
  // render markdown with marked.js
  $('.markdown').each(function(index, md) {
    var $this = $(this);
    $this.html(marked($this.html().trim())).addClass('marked');
    if (typeof hljs === 'object') {
      $this.find('[class*="lang-"]').each(function () {
        hljs.highlightBlock(this);
      });
    }
  });
  // inline markdown
  var inlineRenderer = new marked.Renderer();
  inlineRenderer.paragraph = function(text) { return text; }
  $('.markdown-inline').each(function(index, md) {
    var $this = $(this);
    $this.html(marked($this.html().trim(), { renderer: inlineRenderer })).addClass('marked');
    if (typeof hljs === 'object') {
      $this.find('[class*="lang-"]').each(function () {
        hljs.highlightBlock(this);
      });
    }
  });
  // countdowns
  $('.countdown').each(function() {
    var $this = $(this);
    var seconds = parseInt($this.attr('data-seconds'));
    if (seconds >= 0) {
      $this.append($('<span class="hour">' + Math.floor(seconds / 3600) + '</span>'));
      $this.append(' : ');
      seconds %= 3600;
      $this.append($('<span class="minute">' + Math.floor(seconds / 60) + '</span>'));
      $this.append(' : ');
      seconds %= 60;
      $this.append($('<span class="second">' + seconds + '</span>'));
      $this.addClass('countdownable');
    }
  });
  function digit2FixedLength(number, length) {
    var fixedLengthNumber = number.toString(),
    needPad = length - fixedLengthNumber.length;
    while (needPad > 0) {
      needPad--;
      fixedLengthNumber = '0' + fixedLengthNumber;
    }
    return fixedLengthNumber;
  }
  function countdown() {
    $('.countdownable').each(function() {
      var $this = $(this),
  			$hour = $this.find('.hour'),
  			hour = parseInt($hour.text(), 10),
  			$minute = $this.find('.minute'),
  			minute = parseInt($minute.text(), 10),
  			$second = $this.find('.second'),
  			second = parseInt($second.text(), 10);
  		if (hour || minute || second) {
  			second--;
  			if (second < 0) {
  				second += 60;
  				minute--;
  			}
  			$second.text(digit2FixedLength(second, 2));
  			if (minute < 0) {
  				minute += 60;
  				hour--;
  			}
  			$minute.text(digit2FixedLength(minute, 2));
  			$hour.text(digit2FixedLength(hour, 2));
  		} else {
  			setTimeout(function () {
  				document.location.reload();
  			}, 3000);
  		}
    });
    setTimeout(function () {
      countdown();
    }, 1000);
  }
  countdown();
  // input file
  $('.exam-questions__file > input').each(function() {
    var $input = $(this);
    var $label = $input.siblings('label');
    var labelText = $label.text();
    $input.on('change', function(e) {
      var fileName = e.target.value.split('\\').pop();
      if (fileName) {
        $label.text(fileName);
      } else {
        $label.text(labelText);
      }
    });
  });
  // tooltips
  $('[data-toggle="tooltip"]').tooltip()
  // warnings
  $('.warning').click(function(e) {
    var warning = $(this).attr('data-warning');
    if (warning && !confirm(warning)) {
      e.preventDefault();
    }
  });
});
