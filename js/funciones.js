AOS.init({
    once: true 
});

// isMail: devuelve verdadero si value es una direccion de correo valida
function isMail(value) {
    try
  {
    var pattern=new RegExp("\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*");
    return value.match(pattern);
  }
  catch(e)
  {
    return false;
  }
  }
  
  
  $(document).ready(function() {
  
  $().ajaxStart(function() {
    $('.resultado_form').hide();
    }).ajaxStop(function() {
    $('.resultado_form').fadeIn('slow');
  });
  
  $( "#formulario" ).submit(function( event ) { 
    // Validar nombre
    let $nombreInput = $('input[name$="nombre"]');
    if ($nombreInput.val().trim() === '') {
        let $formGroup = $nombreInput.parent('.form-group');
        $formGroup.addClass('obligatorio');
        $formGroup.one('keypress', () => $formGroup.removeClass('obligatorio'));
        return false;
    }

    // Validar email
    let $emailInput = $('input[name$="email"]');
    if (!isMail($emailInput.val())) {
        let $formGroup = $emailInput.parent('.form-group');
        $formGroup.addClass('obligatorio');
        $formGroup.one('keypress', () => $formGroup.removeClass('obligatorio'));
        return false;
    }

    // Validar teléfono
    let $telefonoInput = $('input[name$="telefono"]');
    if ($telefonoInput.val().trim() === '') {
        let $formGroup = $telefonoInput.parent('.form-group');
        $formGroup.addClass('obligatorio');
        $formGroup.one('keypress', () => $formGroup.removeClass('obligatorio'));
        return false;
    }

    // Validar mensaje
    let $mensajeTextarea = $('textarea[name$="mensaje"]');
    if ($mensajeTextarea.val().trim().length < 10 ) {
        let $formGroup = $mensajeTextarea.parent('.form-group');
        $formGroup.addClass('obligatorio');
        $formGroup.one('keypress', () => $formGroup.removeClass('obligatorio'));
        return false;
    }
  
    $.ajax({
    type: 'POST',
    url: $(this).attr('action'),
    data: $(this).serialize(),
    success: function(data) {
      $('.resultado_form').show().html(data);
      $('#formulario').hide();
  
    }
    
    });
    return false;
    })
  });