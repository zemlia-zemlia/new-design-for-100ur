<?php
    $this->setPageTitle("Контакты юридических центров. ". Yii::app()->name);
    
    Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD);
?>




	<h1 class="header-block header-block-light-grey">Адреса филиалов</h1>
	<br/>
	<p>Портал "100 Юристов" предоставляет юридические консультации онлайн для всех жителей РФ, Беларуси и Украины. Нас егодняшний день вы можете получить очную консультацию, консультацию по телефону в следующих филиалах:</p>
	<br/>
		<div itemscope itemtype="http://schema.org/Organization"> 
			<span itemprop="name">100 Юристов - Филиалы</span>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<span itemprop="addressLocality">Москва</span> 			<span itemprop="streetAddress">Шлюзовая набережная д.6 стр.4</span><br/>
				<span itemprop="addressLocality">Санкт-Петербург</span> <span itemprop="streetAddress">Ул. Достоевского д.25</span><br/>
				<span itemprop="addressLocality">Нижний Новгород</span> <span itemprop="streetAddress">Ул. Новая, д. 28</span><br/>
				<span itemprop="addressLocality">Екатеринбург</span>	<span itemprop="streetAddress">Ул. 8 Марта, д. 142</span><br/>
				<span itemprop="addressLocality">Красноярск</span> 		<span itemprop="streetAddress">Просп. Мира, 30, корп.1</span><br/>
				<span itemprop="addressLocality">Волгоград</span> 		<span itemprop="streetAddress">Ул. Невская, 16А</span><br/>
				<span itemprop="addressLocality">Чебоксары</span> 		<span itemprop="streetAddress">Ул. Петрова, 6</span><br/>
				<span itemprop="addressLocality">Новосибирск</span>		<span itemprop="streetAddress">Ул. Октябрьская, д. 43</span><br/>
				<span itemprop="addressLocality">Самара</span> 			<span itemprop="streetAddress">Ул. Мичурина, д. 48</span><br/><br/>
				<span itemprop="addressLocality">Омск</span> 			<span itemprop="streetAddress">Ул. Полковая, д. 28</span><br/>
				<span itemprop="addressLocality">Челябинск</span> 		<span itemprop="streetAddress">Ул. Труда, д. 84</span><br/>
				<span itemprop="addressLocality">Ростов-на-Дону</span> 	<span itemprop="streetAddress">Ул. Красноармейская, д. 142/50</span><br/>
				<span itemprop="addressLocality">Уфа</span> 			<span itemprop="streetAddress">Ул. Менделеева, д. 21</span><br/>
				<span itemprop="addressLocality">Пермь</span> 			<span itemprop="streetAddress">Ул. Монастырская, д. 12</span><br/>
				<span itemprop="addressLocality">Воронеж</span> 		<span itemprop="streetAddress">Ул. Средне-Московская, д. 31</span><br/>
				<span itemprop="addressLocality">Саратов</span> 		<span itemprop="streetAddress">Ул. Лунная, д. 44А</span><br/>
				<span itemprop="addressLocality">Краснодар</span> 		<span itemprop="streetAddress">Ул. Московская, 148</span><br/>
				<span itemprop="addressLocality">Тольятти</span> 		<span itemprop="streetAddress">Ул. Ленинградская, д. 56</span><br/>
				<span itemprop="addressLocality">Ижевск</span> 			<span itemprop="streetAddress">Ул. 10 лет Октября, 53</span><br/>
				<span itemprop="addressLocality">Ульяновск</span> 		<span itemprop="streetAddress">Ул. Железнодорожная, д. 14а</span><br/>
				<span itemprop="addressLocality">Барнаул</span> 		<span itemprop="streetAddress">Ул. Профинтерна, д. 24</span><br/>
				<span itemprop="addressLocality">Владивосток</span> 	<span itemprop="streetAddress">Ул. Иртышская, д. 4</span><br/>
				<span itemprop="addressLocality">Ярославль</span> 		<span itemprop="streetAddress">Ул. Собинова, 32/8</span><br/>
				<span itemprop="addressLocality">Иркутск</span> 		<span itemprop="streetAddress">Ул. Марата, д. 26</span><br/>
				<span itemprop="addressLocality">Тюмень</span> 			<span itemprop="streetAddress">Ул. Семакова, д. 5</span><br/>
				<span itemprop="addressLocality">Махачкала</span> 		<span itemprop="streetAddress">Ул. Ярагского, 76</span><br/>
				<span itemprop="addressLocality">Хабаровск</span> 		<span itemprop="streetAddress">Ул. Блюхера, д. 1</span><br/>
				<span itemprop="addressLocality">Новокузнецк</span> 	<span itemprop="streetAddress">Ул. Энтузиастов, д. 9</span><br/>
				<span itemprop="addressLocality">Оренбург</span> 		<span itemprop="streetAddress">Ул. Пролетарская, д. 17</span><br/>
				<span itemprop="addressLocality">Кемерово</span> 		<span itemprop="streetAddress">Ул. Ленина, д. 33/3</span><br/>
				<span itemprop="addressLocality">Рязань</span> 			<span itemprop="streetAddress">Ул. Есенина, д.116, корп. 1</span><br/>
				<span itemprop="addressLocality">Томск</span> 			<span itemprop="streetAddress">Ул. Пушкина, д. 63г</span><br/>
				<span itemprop="addressLocality">Астрахань</span> 		<span itemprop="streetAddress">Ул. Красная Набережная, 30</span><br/>
				<span itemprop="addressLocality">Пенза</span> 			<span itemprop="streetAddress">Ул. Коммунистическая, 28</span><br/>
				<span itemprop="addressLocality">Набережные Челны</span><span itemprop="streetAddress">Набережночелнинский пр-т, д. 56</span><br/>
				<span itemprop="addressLocality">Липецк</span> 			<span itemprop="streetAddress">Проспект Мира, д. 19</span><br/>
				<span itemprop="addressLocality">Тула</span> 			<span itemprop="streetAddress">Ул. Дмитрия Ульянова, д. 5</span><br/>

				<span itemprop="telephone">8-800-500-61-85</span>
				</div>
		</div>

