jQuery(document).ready(function($) {
  "use strict";

  function showToast(type, message) {
    var stack = document.getElementById('toastStack');
    if (!stack) return;
    var el = document.createElement('div');
    el.className = 'toast toast-' + type;
    var icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
    el.innerHTML =
      '<span class="toast-icon"><i class="fa ' + icon + '" aria-hidden="true"></i></span>' +
      '<span class="toast-body"></span>' +
      '<button type="button" class="toast-close" aria-label="Close">&times;</button>';
    el.querySelector('.toast-body').textContent = message;
    stack.appendChild(el);
    requestAnimationFrame(function() { el.classList.add('show'); });

    var hide = function() {
      if (!el.classList.contains('hiding')) {
        el.classList.add('hiding');
        setTimeout(function() { el.remove(); }, 300);
      }
    };
    el.querySelector('.toast-close').addEventListener('click', hide);
    setTimeout(hide, 4000);
  }

  //Contact
  $('form.contactForm').submit(function() {
    var f = $(this).find('.form-group'),
      ferror = false,
      emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

    f.children('input').each(function() { // run all inputs

      var i = $(this); // current input
      var rule = i.attr('data-rule');

      if (rule !== undefined) {
        var ierror = false; // error flag for current input
        var pos = rule.indexOf(':', 0);
        if (pos >= 0) {
          var exp = rule.substr(pos + 1, rule.length);
          rule = rule.substr(0, pos);
        } else {
          rule = rule.substr(pos + 1, rule.length);
        }

        switch (rule) {
          case 'required':
            if (i.val() === '') {
              ferror = ierror = true;
            }
            break;

          case 'minlen':
            if (i.val().length < parseInt(exp)) {
              ferror = ierror = true;
            }
            break;

          case 'email':
            if (!emailExp.test(i.val())) {
              ferror = ierror = true;
            }
            break;

          case 'checked':
            if (! i.is(':checked')) {
              ferror = ierror = true;
            }
            break;

          case 'regexp':
            exp = new RegExp(exp);
            if (!exp.test(i.val())) {
              ferror = ierror = true;
            }
            break;
        }
        i.next('.validation').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
      }
    });
    f.children('textarea').each(function() { // run all inputs

      var i = $(this); // current input
      var rule = i.attr('data-rule');

      if (rule !== undefined) {
        var ierror = false; // error flag for current input
        var pos = rule.indexOf(':', 0);
        if (pos >= 0) {
          var exp = rule.substr(pos + 1, rule.length);
          rule = rule.substr(0, pos);
        } else {
          rule = rule.substr(pos + 1, rule.length);
        }

        switch (rule) {
          case 'required':
            if (i.val() === '') {
              ferror = ierror = true;
            }
            break;

          case 'minlen':
            if (i.val().length < parseInt(exp)) {
              ferror = ierror = true;
            }
            break;
        }
        i.next('.validation').html((ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
      }
    });
    if (ferror) {
      showToast('error', 'Please check the highlighted fields and try again.');
      return false;
    }
    var str = $(this).serialize();
    var action = $(this).attr('action');
    if( ! action ) {
      action = 'contactform/contactform.php';
    }
    $.ajax({
      type: "POST",
      url: action,
      data: str,
      success: function(msg) {
        if (msg == 'OK') {
          var $form = $('.contactForm');
          $form.find("input, textarea").val("");
          var $banner = $('#registerSuccessBanner');
          if ($banner.length) {
            $form.hide();
            $banner.show();
            if ($banner[0].scrollIntoView) {
              $banner[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
          } else {
            showToast('success', 'Your message has been sent. Thank you!');
          }
        } else {
          showToast('error', msg);
        }

      },
      error: function(xhr) {
        var msg = (xhr.responseText && xhr.responseText !== '') ? xhr.responseText : 'Something went wrong. Please try again.';
        showToast('error', msg);
      }
    });
    return false;
  });

  if ($('form.contactForm').length) {
    $('#registerSuccessClose').on('click', function() {
      $('#registerSuccessBanner').hide();
      $('form.contactForm').show();
    });
  }

});
