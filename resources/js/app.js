require('jquery');
require('bootstrap');
require('@fortawesome/fontawesome-free');
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
    element = $("[type='password']");
    if (element.is('[id]')) {
      element.after(
        `<button type='button' class='showPass'><svg viewBox="0 0 24 24" width="24" height="24" stroke="#000" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>`
      );
      const buttons = element.after();
      buttons.each(function (i) {
        const id = $(this).after().attr('id');
        const button = $(this).siblings('.showPass').eq(i);
        button.attr('toggle', '#' + id);
        $(button).click(function () {
          const input = $($(this).attr('toggle'));
          if (input.attr('type') == 'password') {
            input.attr('type', 'text');
            $(this)
              .children('svg')
              .html(
                '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>'
              );
          } else {
            input.attr('type', 'password');
            $(this).children('svg').html('<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>');
          }
        });
      });
    }
  }
});