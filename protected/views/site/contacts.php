<?php
    $this->setPageTitle("Контакты. ". Yii::app()->name);
    
    Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD);
?>

<h1>Контактная информация</h1>

<div class='panel'>
    <div class='panel-body'>
        <p> Портал "100 ЮРИСТОВ" работает на всей территории России</p>
<p> Офис администрации портала "100 ЮРИСТОВ" находится по адресу: г. Москва ул. Кожевническая д.10 стр.1
</p>

<div id="map" style="width:100%; height: 450px" class="vert-margin30"></div>

<hr />
	<script type="text/javascript">
		ymaps.ready(init);
		var myMap, myPlacemark;

		function init(){     
			myMap = new ymaps.Map ("map", {
				center: [55.729209, 37.645339],
				zoom: 14,
			});
		  myMap.controls.add(
			 new ymaps.control.ZoomControl()
		  );
		 myPlacemark = new ymaps.Placemark([55.729209, 37.645339], 
	   { content: '100 юристов', 
		balloonContent: 'г. Москва ул. Кожевническая д.10 стр.1' 
	   });
		 myMap.geoObjects.add(myPlacemark);
		 myPlacemark.balloon.open();
		}
	</script>
        
<p>Ваши вопросы по работе портала и предложения о сотрудничестве просьба направлять на ящик 100yuristov@mail.ru </p>
    </div>
</div>
