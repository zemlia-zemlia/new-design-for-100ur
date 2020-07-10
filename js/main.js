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

	
});