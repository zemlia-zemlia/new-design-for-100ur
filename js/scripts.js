
$(function(){
	
	$('.carousel').carousel({
	  interval: 5000
	})

	$("a.ancLinks").on('click', function () {
		elementClick = $(this).attr("href");
		destination = $(elementClick).offset().top;
		
		$("html, body").animate( { scrollTop: destination }, 1100 );
		return false;
	}); 
	
	$("#toggle-reviews").click(function(){
		$("#hidden-reviews").show('slow');
		$(this).remove();
	});
});


    ymaps.ready(init);
    var myMap, myPlacemark;

    function init(){     
        myMap = new ymaps.Map ("map", {
            center: [55.735654,37.591472],
            zoom: 15,
        });
      myMap.controls.add(
         new ymaps.control.ZoomControl()
      );
     myPlacemark = new ymaps.Placemark([55.735654,37.591472], 
   { content: 'Партнер недвижимость', 
    balloonContent: '<p>Телефон: <span class="blue-text">+7 (495) 968 44 38</span><br />C 9:00 до 22:00 без выходных<br />Эл. почта: <span class="blue-text">info@партнер-недвижимость.рф</span><br />Москва, м. Парк культуры,<br /><span class="blue-text">Зубовский бульвар, 15/2</span></p>' 
   });
     myMap.geoObjects.add(myPlacemark);
     //myPlacemark.balloon.open();
    }



  


		
hs.graphicsDir = '/pics/graphics/';

    hs.outlineType = 'rounded-white';

    hs.showCredits = false;

    hs.align = 'center';

    hs.lang = {

		loadingText :     'loading',

		fullExpandTitle : '2321',

		restoreTitle :    '231',

		focusTitle :      '1231',

		loadingTitle :    '13123'

	};

hs.addSlideshow({

	// slideshowGroup: 'group1',

	interval: 5000,

	repeat: false,

	useControls: true,

	fixedControls: true,

	overlayOptions: {

		opacity: .6,

		position: 'top center',

		hideOnMouseOut: true

	}

});



// Optional: a crossfade transition looks good with the slideshow

hs.transitions = ['expand', 'crossfade'];