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
  if (el.val().trim().length === 0) {
    el.parent('div').addClass('error-mandatory');
  } else {
    el.parent('div').removeClass('error-mandatory');
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



$(document).ready(function() {

  var has_form = $('input.form-control').length > 0;
  if (!has_form) {
    $('h1 small').hide();
  }



  $('[data-toggle="tooltip"]').tooltip();

  resizeAll();
  // loader for discopower view
  $('#loader').delay(300).fadeOut('slow', function() {
    $('#favourite-modal').modal('show');
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

    // Do not submit form if there are any errors
    if (parseInt($('.error-mandatory').length) + parseInt($('.error-mail').length)>0) { 
      return false;
    }

    // If the user has filled in inputs, show loader and fill hiden 
    // `userData` input with user data
    if (inputs.length > 0) {
      var data = {};
      var url = '../themeopenminted/dummy.php';
      inputs.each(function(key, input) {
        var name = $(input).attr('name');
        var value = $(input).val().trim();
        if (name === 'eduPersonScopedAffiliation') {
          value = 'member@'+value;
        }
        data[name] = value;
      });
      $('input[name="userData"]').val(JSON.stringify(data));
      $('#loader').show();
    }

  })

  $('input.form-control').bind("keyup change", function(e) {
      handleMandatory($(this));
  })

  $('input[name="mail"]').bind("keyup change", function(e) {
      handleMail($(this));
  })


});
