// Make one element to get all the available height
function spreadHeight($el2spread) {
  var el2spread_height = $el2spread.height(),
    $parent = $el2spread.parent(),
    parent_height = $parent.height(),
    $siblings = $el2spread.siblings().not('.modal'),
    siblings_height = 0,
    available_height = 0;

  if ($siblings.length > 0 ) {
    $siblings.each(function() {
      siblings_height += $(this).outerHeight(true);
    });
  }
  available_height = parent_height - siblings_height;
  if(available_height > el2spread_height ){
    $el2spread.outerHeight(available_height);
  }
};

// Apply spreadHeight in html of discopower and consent
function resizeAll() {
  // If there is an element with the particular selector, do the resizing
  if($('.js-spread').length > 0) {
    var $spread_els = $('.js-spread');
    $spread_els.each(function() {
      spreadHeight($(this));
    });
  }
};

// Toggle error class to input parent div if empty
function handleMandatory(el) {
  if ($(el).siblings('span.mandatory').length >0) {
    if (el.val().trim().length === 0) {
      el.parent('div').addClass('error-mandatory');
    } else {
      el.parent('div').removeClass('error-mandatory');
    }
  }
}

// Toggle error class to input parent div if mail invalid
function handleMail(el) {
  var regex = new RegExp(/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i);
  var res = regex.test(el.val().trim());
  if (!res) {
    el.parent('div').addClass('error-mail');
  } else {
    el.parent('div').removeClass('error-mail');
  }
}

function handleTerms(el) {
  if (el.is(':checked')) {
    el.parent('div').removeClass('error-mandatory');
  } else {
    el.parent('div').addClass('error-mandatory');
  }
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function deleteCookie(cname) {
  document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
};

// Hide Top Bar notification
function closeNoty(element) {
  $(element).parent().hide()
}

$(document).ready(function() {
  if (!Cookies.get('cookies_accepted')) {
    $('#cookies').show();
  };

  $('#dropDownMenuLanguages').click(function() {
    $('.dropdown-menu').toggle();
  });

  var has_form = $('input.form-control').length > 0;
  if (!has_form) {
    $('h2 small').hide();
  }

  $('[data-toggle="tooltip"]').tooltip();

  // resizeAll();
  // loader for discopower view
  $('.loader-container').delay(300).fadeOut('slow', function() {
    $('.loader-container').siblings().not($('#cookies')).show();
    var setLang = getCookie('setLang');
    console.log('setlang:', setLang)
    if (setLang) {
      $('#edugain-modal').modal({
        focus: false
      }).modal('show');
      deleteCookie('setLang');
    } else {
      $('#favourite-modal').modal({
        focus: false
      }).modal('show');
    }
  });

  // hide modal smoothly
  $('.js-close-custom').click(function() {
    $modal = $(this).closest('.modal.fade');
    $modal.slideUp(450, function() {
      $modal.modal('hide');
    });
  });

  $(window).resize(function() {
    resizeAll();
  });

    $('button[name="yes"]').click(function(e){
    var inputs = $('input.form-control');
    // Check if mandatory input field is empty
    inputs.each(function(key, input) {
      handleMandatory($(input));
    });
    // Check if mail field is properly formatted
    var mailInputs = $('input[name="mail"]');
    mailInputs.each(function(key, input) {
      handleMail($(input));
    });
    // If termsAccepted checkbox exists, check if the user has accepted terms
    var termsInput = $('input[name="termsAccepted"]')[0];
    if (termsInput) {
      handleTerms($(termsInput));
    }

    // Do not submit form if there are any errors
    if (parseInt($('.error-mandatory').length) + parseInt($('.error-mail').length)>0) {
      return false;
    }

    // If the user has filled in inputs, show loader and fill hiden
    // `userData` input with user data
    if (inputs.length > 0) {
      var data = {};
      inputs.each(function(key, input) {
        var name = $(input).attr('name');
        var value = $(input).val().trim();
        data[name] = value;
      });
      var mailRadio = $('input[type="radio"][name="mail"]:checked')
      var hasMultiple = mailRadio.length > 0;
      if (hasMultiple) {
        data['mail'] = mailRadio.val();
      }
      $('input[name="userData"]').val(JSON.stringify(data));
      $('.loader-container').show();

      $('.loader-container').siblings().hide();
    }

  })

  $('input.form-control').bind("keyup change", function(e) {
      handleMandatory($(this));
  })

  $('input[name="mail"]').bind("keyup change", function(e) {
      handleMail($(this));
  })

  $('input[name="termsAccepted"]').bind("keyup change", function(e) {
      handleTerms($(this));
  })

  $('#edugain-modal, #favourite-modal').on('shown.bs.modal', function(e) {
    $('.row.ssp-content-group').addClass('hidden')
    $('#query_edugain').trigger('focus')
    $('#query_edugain').liveUpdate('#list_edugain');
    $('h1.disco').hide();
  });

  $('#edugain-modal, #favourite-modal').on('hidden.bs.modal', function(e) {
    $('.row.ssp-content-group').removeClass('hidden')
    $('h1.disco').show();
  });

  $('.js-pick-language').click(function(e) {
    e.preventDefault();
    setCookie('setLang', true);
    window.location = $(this).attr('href');
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

  $('#js-accept-cookies').click(function(e){
    e.preventDefault();
    $('#cookies').hide();
    Cookies.set('cookies_accepted', true);
  })

  $('#js-open-help').click(function(e) {
    e.preventDefault;
    $('#login-help-modal').modal('show');
  })

});
