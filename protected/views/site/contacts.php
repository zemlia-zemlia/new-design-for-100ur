<?php
    $this->setPageTitle("Контакты юридических центров. ". Yii::app()->name);
    
    Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD);
?>




	<h1 class="header-block header-block-light-grey">Адреса филиалов</h1>
	<br/>
	<p>Портал "100 Юристов" предоставляет юридические консультации онлайн для всех жителей РФ, Беларуси и Украины. На сегодняшний день вы можете получить очную консультацию, консультацию по телефону в следующих филиалах:</p>
	<br/>
		<div itemscope itemtype="http://schema.org/Organization"> 
			<span itemprop="name">"100 Юристов" - Филиалы</span>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<span itemprop="addressLocality">Москва</span> 			<span itemprop="streetAddress">Шлюзовая набережная д.6 стр.4</span><br/>
				<span itemprop="addressLocality">Санкт-Петербург</span> <span itemprop="streetAddress">Ул. Достоевского д.25</span><br/>
				<span itemprop="addressLocality">Нижний Новгород</span> <span itemprop="streetAddress">Ул. Новая, д. 28</span><br/>
				<span itemprop="addressLocality">Екатеринбург</span>	<span itemprop="streetAddress">Ул. 8 Марта, д. 142</span><br/>
				<span itemprop="addressLocality">Ростов-на-Дону</span> 	<span itemprop="streetAddress">Ул. Красноармейская, д. 142/50</span><br/>
				<span itemprop="addressLocality">Краснодар</span> 		<span itemprop="streetAddress">Ул. Московская, 148</span><br/>
				<span itemprop="telephone">8-800-500-61-85</span>
				</div>
		</div>

