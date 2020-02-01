<?php
$this->setPageTitle("Контакты юридических центров. " . Yii::app()->name);

Yii::app()->clientScript->registerScriptFile("https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU", CClientScript::POS_HEAD);
?>


<h1>Контакты</h1>
<br/>
<p>Портал "100 Юристов" предоставляет юридические консультации онлайн для всех жителей РФ, Беларуси и Украины.</p>
<h2>Адрес головного офиса:</h2>
<div itemscope itemtype="http://schema.org/Organization">
			<span itemprop="name">100 Юристов - юридический портал</span>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<span itemprop="addressLocality">Москва</span> 			<span itemprop="streetAddress">Шлюзовая набережная д.6 стр.4</span><br/>
				<span itemprop="telephone">8-800-500-61-85</span>
				</div>
</div>
<br/>
<iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=1540217792" width="100%" height="400" frameborder="0"></iframe>