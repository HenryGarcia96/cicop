/**
 * jQuery Form Validator
 * ------------------------------------------
 *
 * Spanish language package
 *
 * @website http://formvalidator.net/
 * @license Dual licensed under the MIT or GPL Version 2 licenses
 * @version 2.2.8
 */
(function($, window) {

  "use strict";

  $(window).bind('validatorsLoaded', function() {

    $.formUtils.LANG = {
      errorTitle: 'El formulario no se ha podido enviar!',
      requiredFields: '*Requerido',
      badTime: 'No ha introducido la hora correcta',
      badEmail: 'No ha entrado en una dirección de e-mail válida',
      badTelephone: 'Usted no ha entrado en el número de teléfono correcto',
      badSecurityAnswer: 'Ha introducido la respuesta incorrecta a la pregunta de seguridad',
      badDate: 'Re-escribiendo una fecha incorrecta',
      lengthBadStart: 'Su respuesta debe incluir entre',
      lengthBadEnd: ' signo',
      lengthTooLongStart: 'Ha introducido una respuesta que es más largo que',
      lengthTooShortStart: 'Ha introducido una respuesta que es más corta que',
      notConfirmed: 'Las respuestas no pudieron confirmar entre sí',
      badDomain: 'Ha introducido un dominio incorrecto',
      badUrl: 'Usted no ha entrado en el URL correcto',
      badCustomVal: 'Re-escribiendo una respuesta incorrecta',
      andSpaces: ' y espacios',
      badInt: 'No ha introducido un número',
      badSecurityNumber: 'Ha introducido un número de seguro social incorrecto',
      badUKVatAnswer: 'No ha introducido un número de IVA del Reino Unido',
      badStrength: 'Ha introducido una contraseña que no es lo suficientemente seguro',
      badNumberOfSelectedOptionsStart: 'Debe seleccionar al menos',
      badNumberOfSelectedOptionsEnd: ' respuesta',
      badAlphaNumeric: 'Sólo se puede responder con alfanumersika caracteres (az y números)',
      badAlphaNumericExtra: ' y',
      wrongFileSize: 'El archivo que está tratando de subir es demasiado grande (máx %s)',
      wrongFileType: 'Sólo los archivos de tipo %s está permitido',
      groupCheckedRangeStart: 'Elegir entre',
      groupCheckedTooFewStart: 'Entonces usted debe hacer por lo menos',
      groupCheckedTooManyStart: 'Usted no puede hacer más de',
      groupCheckedEnd: ' selección',
      badCreditCard: 'Ha introducido un número de tarjeta de crédito válida',
      badCVV: 'Usted ha introducido una CVV incorrecta',
      wrongFileDim: 'Tamaño de la imagen Ilegal,',
      imageTooTall: 'el cuadro no puede ser superior a',
      imageTooWide: 'el cuadro no puede ser más amplio que',
      imageTooSmall: 'la imagen es demasiado pequeña',
      min: 'menos',
      max: 'max',
      imageRatioNotAccepted : 'Cociente de la imagen no es ser aceptado'
    };

  });

})(jQuery, window);
