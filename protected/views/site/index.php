<?php
    $this->setPageTitle("Юридическая Консультация онлайн - Бесплатно. Круглосуточно. ". Yii::app()->name);
    Yii::app()->clientScript->registerMetaTag("Задавайте свои вопросы. Круглосуточная юридическая консультация онлайн по любому вопросу. Заказ юридических документов и услуг, каталог юристов", 'description');
	Yii::app()->clientScript->registerLinkTag("canonical",NULL,"https://".$_SERVER['SERVER_NAME']);

?>


    <h3 class="">Последние ответы юристов на вопросы</h3>   
    <div class="vert-margin30">
    <?php
        // выводим виджет с последними ответами
        $this->widget('application.widgets.RecentAnswers.RecentAnswers', array(
            'template'  => 'page',
            'limit'     => 6,
        ));
    ?>
    </div>
<h3 class="header-block-light-grey"><strong> На ваши вопросы отвечают: </strong></h3>
<div class='flat-panel inside vert-margin30'>

    <?php
        // выводим виджет с топовыми юристами
        $this->widget('application.widgets.TopYurists.TopYurists', array(
            'cacheTime' =>  0,
            'limit'     =>  6,
        ));
    ?>
    
    <p class="right-align">
        <?php echo CHtml::link('Все юристы', Yii::app()->createUrl('yurist'));?>
    </p>
</div>



<h1 class="header-block-light-grey">Юридическая консультация</h1>
<div style="text-align: justify;">
<p>
	 Воспользовавшись нашим специализированным интернет-порталом, каждый желающий имеет возможность получить профессиональную юридическую консультацию граждан в онлайн-режиме. Причем сделать это могут жители как столицы, так&nbsp;и других регионов нашей страны.
</p>
<h2>Онлайн консультация юриста</h2>
<p>
	 Помощь юриста и его консультация может быть предоставлена в самых различных сферах правовой практики. Заметьте, что данная услуга оказывается бесплатно. Собственно, вам дается возможность получить консультацию юриста по вопросам, которые касаются:
</p>
<ul>
	<li>споров с компаниями-страховщиками относительно выплаты законного возмещения при случаях дорожно-транспортных происшествий с застрахованным автомобилем;</li>
	<li>прав авторства;</li>
	<li>незаконной банковской деятельности;</li>
	<li>ведения нечестной бухгалтерии;</li>
	<li>проблем с жилыми помещениями (выселение, дарение, вступление в наследство);</li>
	<li>защиты прав потребителей;</li>
	<li>мошенничества, вымогательства;</li>
	<li>составления завещания;</li>
	<li>кредитных историй;</li>
	<li>ликвидации предприятий;</li>
	<li>гражданства, материнского капитала;</li>
	<li>выплаты алиментов;</li>
	<li>приватизации, уголовных дел.</li>
</ul>
<p>
	 И это еще далеко не полный перечень сфер, касаемо которых вы можете задать интересующий вас вопрос и получить реальную помощь. Ведь на нашем портале консультации граждан по юридическим вопросам могут касаться абсолютно любых инцидентов.
</p>
<h2>Как получить юридическую консультацию</h2>
<p>
	 Стоит отметить, что юридическая консультация онлайн, во время которой можно задать вопросы, проводится в любое удобное для вас время. Это значит, что при необходимости вы можете получить нужную информацию даже в ночное время. В целом, мы работаем с такими областями права, как:
</p>
<ul>
	<li>семейное законодательство;</li>
	<li>административные и гражданские вопросы;</li>
	<li>уголовные дела;</li>
	<li>земельное и налоговое право;</li>
	<li>иные темы, касающиеся законодательства и юриспруденции. </li>
</ul>
<p>
	 На сайте вы можете воспользоваться специальной формой, в которой можете описать суть проблемы. Для этого на сервисе организован специальный чат. Но если вам неудобно говорить в конкретный момент, вы все равно получите ответ на свой вопрос, так как наши сотрудники могут связаться с вами по телефону или электронной почте.
</p>
<p>
	 Если у вас возникли проблемы в кругу семьи, к примеру, существует угроза развода или же дело касается раздела совместного имущества, то помочь вам может только квалифицированный юрист, который сделает все для того, чтобы решить конфликт мирным путем.
</p>
<p>
	 В современном обществе многим людям приходится сталкиваться с потребностью отстаивания своих прав и интересов в области хозяйствования. Поэтому мы предлагаем вам интернет-консультацию юриста. При несоблюдении условий договоров, при наличии хозяйственного спора наши квалифицированные специалисты проведут для вас полноценную консультацию с возможностью предоставления помощи на практике.
</p>
<p>
	 Предоставление юридической консультации специалиста в области права также можно получить по вопросам относительно трудового и гражданского права, во время расследования и судебного рассмотрения уголовных дел, страховых споров и так далее. Собственно, если у вас появляются затруднения с решением проблем именно с правовой точки зрения, наша команда всегда готова предоставить вам свои высококлассные услуги для того, чтобы закрыть вопрос с наибольшей выгодой для вас.
</p>
<p>
	 Обращаясь к нашим профессионалам, вы можете не только поинтересоваться тем, как обстоят ваши дела в отношении спорной или конфликтной ситуации и есть ли у вас шансы выиграть дело. У нас вы можете получить настоящую эффективную правовую помощь, ведь наши специалисты имеют огромный практический опыт решения вопросов в самых различных сферах права.
</p>
</div>