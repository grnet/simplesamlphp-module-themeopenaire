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

  // Do not submit form if any mandatory input field is empty 
  $('button[name="yes"]').click(function(e){
    var inputs = $('input.form-control');
    inputs.each(function(key, input) {
      handleMandatory($(input));
    });
    var mailInputs = $('input[name="mail"]');
    mailInputs.each(function(key, input) {
      handleMail($(input));
    });

    var errors = parseInt($('.error-mandatory').length);
    if (parseInt($('.error-mandatory').length) + parseInt($('.error-mail').length)>0) { 
      return false;
    }
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
      $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(data){
          console.log(data);
        },
        dataType: 'json'
      });
    }
  })

  $('input.form-control').bind("keyup change", function(e) {
      handleMandatory($(this));
  })

  $('input[name="mail"]').bind("keyup change", function(e) {
      handleMail($(this));
  })


});
