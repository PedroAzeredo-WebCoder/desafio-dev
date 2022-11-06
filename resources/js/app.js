require('jquery');
require('bootstrap');
require('@fortawesome/fontawesome-free');
require('sweetalert/dist/sweetalert.min.js');
require('jquery-mask-plugin/dist/jquery.mask.min');

window._ = require('lodash');

try {
  window.Popper = require('@popperjs/core').default;
  window.$ = window.jQuery = require('jquery');
} catch (e) {}

jQuery(function () {
  mainHeight();
  themeColor();
  consultaCep();
  eyePass();

  $(window).scroll(function () {
    mainHeight();
  });

  $(window).on('resize', function () {
    mainHeight();
  });

  $(window).ready(function () {
    mainHeight();
  });

  $(window).on('load', function () {
    mainHeight();
  });

  // margin's header
  function mainHeight() {
    let heightHeader = $('header').outerHeight();
    let heightWindow = $(window).outerHeight();
    let heightFooter = $('footer').outerHeight();
    let wpAdminBar = $('#wpadminbar').outerHeight();

    // margin between header and WordPress admin bar if it exists
    if ($('#wpadminbar').hasClass('mobile')) {
      $('header').addClass('sticky-top');
      $('header').removeClass('fixed-top');
    } else {
      $('header').css('margin-top', wpAdminBar);
    }

    // margin between header and session, when header is fixed
    if ($('header').hasClass('fixed-top')) {
      $('header')
        .next()
        .not($('.behind'))
        .css('margin-top', heightHeader - (wpAdminBar ? wpAdminBar : null));
    }

    $('header')
      .next()
      .css('min-height', heightWindow - heightHeader - heightFooter);
  }

  // add theme-all-scripts main color in header meta tags
  function themeColor() {
    const themeColor = $('[name="theme-color"], [name="msapplication-navbutton-color"], [name="msapplication-TileColor"]');
    const value = getComputedStyle(document.documentElement).getPropertyValue('--bs-primary');
    themeColor.attr('content', value);
  }

  function consultaCep() {
    let cep = $('.mask-cep, [name="cep"], [id="cep"],[name="f_cep"]');
    cep.change(function () {
      $('[name="estado"],[name="f_estado"]').val('');
      $('[name="bairro"],[name="f_bairro"]').val('');
      $('[name="logradouro"],[name="f_logradouro"]').val('');
      $('[name="cidade"],[name="f_cidade"]').val('');

      let consultaCep = $.getJSON('https://viacep.com.br/ws/' + $(this).val() + '/json/', function () {
          console.log('success');
        })
        .done(function () {
          console.log('second success');
        })
        .fail(function () {
          console.log('error');
        })
        .always(function () {
          console.log('complete');
        });

      consultaCep.always(function () {
        let valor = consultaCep.responseJSON;
        $('[name="estado"],[name="f_estado"]').val(valor.uf);
        $('[name="bairro"],[name="f_bairro"]').val(valor.bairro);
        $('[name="logradouro"],[name="f_logradouro"]').val(valor.logradouro);
        $('[name="cidade"],[name="f_cidade"]').val(valor.localidade);
      });
    });
  }

  // button to view password, if field has an ID
  function eyePass() {
    element = $("[type='password']")
    if (element.is('[id]')) {
      element.after("<button class='showPass' type='button'><i class='fa-solid fa-eye'></i></button>")
      const buttons = element.after()
      buttons.each(function (i) {
        const id = $(this).after().attr('id')
        const button = $(this).siblings('.showPass').eq(i)
        button.attr('toggle', '#' + id)
        $(button).click(function () {
          $(button).children().toggleClass('fa-eye fa-eye-slash')
          var input = $($(this).attr('toggle'))
          if (input.attr('type') == 'password') {
            input.attr('type', 'text')
          } else {
            input.attr('type', 'password')
          }
        })
      })
    }
  }
});