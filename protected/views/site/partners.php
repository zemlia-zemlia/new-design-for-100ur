<?php
    $this->setPageTitle('Юридическая партнерская программа. CPA/CPL Юристы. ');
    Yii::app()->clientScript->registerMetaTag('Партнерская программа по юридическим услугам, если у вас есть сайт юридической тематики и вы хотите его монетизировать - вы по адресу.', 'description');
?>




<h1>Юридическая партнёрская программа для вебмастеров</h1>
<p class="text-center">Наша партнерская программа работает по двум моделям<br/> CPL (Cost Per Lead − оплата за лид) и <br/>CPA (Cost Per Action – оплата за действие). </p>

<strong>
	<p>Мы предоставляем возможность рекламодателям увеличить количество клиентов, а вебмастерам выгодно монетизировать интернет трафик.</p> 
</strong>


<h2 class="vert-margin20 header-block-light-grey">Юридическая партнерская программа - CPA</h2>
<p>
	Для владельцев источников трафика есть возможность его монетизации по следующему принципу: После регистрации вам будет доступна индивидуальная реферальная ссылка которую вы размещаете на своих сайтах, пабликах, группах и т.д. При переходе по вашей ссылке в нашу партнерскую программу передается информация о источнике конкретного пользователя.<br/> Начисление оплаты на ваш счет происходит за каждый опубликованный на сайте партнерской программы вопрос содержащий в себе вопрос юридического характера, оплачивается только один (первый) вопрос от каждого уникального пользователя. Сумма начисления фиксированная и не зависит от региона пользователя который задал свой вопрос.
	<blockquote>
		<h3>Сумма вознаграждения за каждый опубликованный вопрос <br/><strong> <?php echo MoneyFormat::rubles(Yii::app()->params['questionPrice']); ?> рублей</strong>
		</h3>
		<p class="small">Для владельцев крупных источников трафика возможны индивидуальные условия</p>
	</blockquote>
	<ul>
		<li>Оплачиваются вопросы пользователей только из РФ</li>
		<li>Сумма начисления фиксированная и не зависит от региона пользователя который задал свой вопрос.</li>
		<li>Опубликованным считается вопрос, автор которого <strong>подтвердил свой Email.</strong></li>
	</ul>
</p>

<!-- 
<div class="vert-margin30 text-center">
    <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('user/create', ['role' => User::ROLE_PARTNER]), ['class' => 'yellow-button']); ?>
</div>
--> 

<h2 class="vert-margin20 header-block-light-grey">Юридическая партнерская программа - CPL</h2>
<p>В отличии от первого варианта партнерки в этом случае оплачиваются только активные в данный момент времени регионы. Активные регионы могут добавляться и удаляться.</p>

<strong>
	<p>Вы можете работать с нами по обоим вариантам партнерской программы одновременно и пользуясь всеми привилегиями.</p>
</strong>

<h3>Ключевые моменты CPL</h3>
<ul>
<li>Обилие выкупаемых регионов и высокие цены на заявки.</li>
<li>Индивидуальные условия для арбитражников.</li>
<li><b>Высокий процент выкупаемых заявок</b>.</li>
</ul>

<h2>Оплачиваемые тематики</h2> 
<ul>
	<li>уголовное право;</li>
	<li>административное право (лишение прав, выезд на встречку, гибдд, дтп и т.п.);</li>
	<li>семейное законодательство (развод и раздел имущества, наследство);</li>
	<li>земельное законодательство;</li>
	<li>вопросы по пользованию жильём (порядок пользования, выселение, приватизация, сложные размены);</li>
	<li>кредиты и споры с банками;</li>
	<li>автоюрист;</li>
	<li>споры со страховыми компаниями;</li>
	<li>защита прав потребителей (возврат сложной техники и продукции дороже 5 тысяч рублей);</li>
	<li>трудовые споры (не платят или задерживают зарплату, насильственные увольнения и т.п.);</li>
	<li>вопросы по составлению жалоб и исковых заявлений - как изначальная потребность в оказании услуги;</li>
	<li>споры между юридическими лицами;</li>
	<li>возврат долгов, разбирательства с судебными приставами.</li>
	<li>Банкротство физлиц;</li>
</ul>

<!--
<div class="vert-margin30 text-center">
    <?php echo CHtml::link('Пройдите регистрацию чтобы узнать выкупаемые регионы и цены', Yii::app()->createUrl('user/create', ['role' => User::ROLE_PARTNER]), ['class' => 'yellow-button']); ?>
</div>	
-->

	<blockquote>
		<h3>Если вы хотели бы с нами сотрудничать напиште нам об этом admin@100yuristov.com</h3>
		<h4>Опишите свои источники трафика, примерные суточные объемы и ссылки на ваши ресурсы</h4>
	</blockquote>