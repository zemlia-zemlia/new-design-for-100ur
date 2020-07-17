$(document).ready(function() {


   $('.nav-mob-wrap').on('click touch', function(){
       $('.nav-mob').addClass('active');
    });

   $('.nav-mob-close').on('click touch', function(){
       $('.nav-mob').removeClass('active');
    });

   $('.search-btn').on('click touch', function(){
       $('.search-form-mob').slideToggle();
       return false;
    });




  var swiper = new Swiper('.consultations-swiper-container', {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
       breakpoints: {
        540: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        920: {
          slidesPerView: 3,
          spaceBetween: 10,
        }
      }
    });

  var swiper = new Swiper('.reviewes-swiper-container', {
      slidesPerView: 4,
      spaceBetween: 30,
      
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.reviewes-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.reviewes-button-next',
        prevEl: '.reviewes-button-prev',
      },
       breakpoints: {
        576: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        840: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1080: {
          slidesPerView: 3,
          spaceBetween: 10,
        },
        1120: {
          slidesPerView: 4,
          spaceBetween: 10,
        }
      }
    });

  $('#questionsContainer').tabs({
    beforeActivate : function(evt) {
      location.hash=$(evt.currentTarget).attr('href');
    },
    show: 'fadeIn',
    hide: 'fadeOut'
  });

   

  var swiper = new Swiper('.question-free-swiper-container', {
      slidesPerView: 3,
      spaceBetween: 30,
      observer: true,
      observeParents: true,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.question-free-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.question-free-button-next',
        prevEl: '.question-free-button-prev',
      },
       breakpoints: {
        576: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        920: {
          slidesPerView: 3,
          spaceBetween: 20,
        }
      }
    });

  var swiper = new Swiper('.question-paid-swiper-container', {
      slidesPerView: 3,
      spaceBetween: 30,
      observer: true,
      observeParents: true,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.question-paid-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.question-paid-button-next',
        prevEl: '.question-paid-button-prev',
      },
       breakpoints: {
        576: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        920: {
          slidesPerView: 3,
          spaceBetween: 20,
        }
      }
    });

  var swiper = new Swiper('.workers-swiper-container', {
      slidesPerView: 4,
      spaceBetween: 30,
      loop: true,
      pagination: {
        el: '.workers-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.workers-button-next',
        prevEl: '.workers-button-prev',
      },
       breakpoints: {
        518: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        920: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1050: {
          slidesPerView: 4,
          spaceBetween: 20,
        }
      }
    });

  var swiper = new Swiper('.news-swiper-container', {
      slidesPerView: 4,
      spaceBetween: 30,
      loop: true,
      pagination: {
        el: '.news-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.news-button-next',
        prevEl: '.news-button-prev',
      },
       breakpoints: {
        576: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
      }
    });

  var swiper = new Swiper('.materials-swiper-container', {
      slidesPerView: 4,
      spaceBetween: 30,
      loop: true,
      pagination: {
        el: '.materials-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.materials-button-next',
        prevEl: '.materials-button-prev',
      },
       breakpoints: {
        576: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
      }
    });

  $('a[data-modal]').click(function(event) {
    $(this).modal();
    return false;
  });

$('#phone').mask('+7 (999) 999-99-99');


$('#doc-type-1').on('click touch', function(){
    $('.form-input-select select').addClass('select-hidden');
    $('.select-1').removeClass('select-hidden');
  });

$('#doc-type-2').on('click touch', function(){
    $('.form-input-select select').addClass('select-hidden');
    $('.select-2').removeClass('select-hidden');
  });

$('#doc-type-3').on('click touch', function(){
    $('.form-input-select select').addClass('select-hidden');
    $('.select-3').removeClass('select-hidden');
  });

$('#doc-type-4').on('click touch', function(){
    $('.form-input-select select').addClass('select-hidden');
    $('.select-4').removeClass('select-hidden');
  });

$('#doc-type-5').on('click touch', function(){
    $('.form-input-select select').addClass('select-hidden');
    $('.select-5').removeClass('select-hidden');
  });

$('#doc-type-6').on('click touch', function(){
    $('.form-input-select select').addClass('select-hidden');
    $('.select-6').removeClass('select-hidden');
  });

$('#reg-type-1').on('click touch', function(){
    $('.form-input-wrap-name, .form-input-wrap-phone').removeClass('reg-hidden');
    $('.registration__form-layer, .registration__form-company').addClass('reg-hidden');
  });

$('#reg-type-2').on('click touch', function(){
    $('.form-input-wrap-name, .registration__form-company').addClass('reg-hidden');
    $('.registration__form-layer, .form-input-wrap-phone').removeClass('reg-hidden');
  });

$('#reg-type-3').on('click touch', function(){
     $('.registration__form-layer, .form-input-wrap-name').addClass('reg-hidden');
     $('.registration__form-company, .form-input-wrap-phone').removeClass('reg-hidden');
  });

  $('#search-container').tabs({
    beforeActivate : function(evt) {
      location.hash=$(evt.currentTarget).attr('href');
    },
    show: 'fadeIn',
    hide: 'fadeOut'
  });

   var hash = location.hash; 
  if (hash) { 
    $('#search-container').tabs("load", hash) 
  } 

});