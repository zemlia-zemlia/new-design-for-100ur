<?php
$this->setPageTitle("Бесплатная юридическая консультация онлайн и по телефону круглосуточно" . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("На нашем сайте вы можете получить бесплатную правовую помощь без регистрации.  Вы можете задать любой вопрос юристу или самостоятельно найти ответ в нашей правовой базе.", 'description');
Yii::app()->clientScript->registerMetaTag("Бесплатная юридическая консультация онлайн", 'keywords');
Yii::app()->clientScript->registerLinkTag("canonical", NULL, "https://" . $_SERVER['SERVER_NAME']);
?>
<h1>Бесплатная юридическая консультация</h1>
    <p>
        Воспользовавшись нашим специализированным интернет-порталом, каждый желающий имеет возможность получить профессиональную бесплатную юридическую консультация онлайн. Получить бесплатную юридическую консультацию могут как жители Москвы и СПБ, так и других регионов нашей страны.
    </p>
            <p>
            Обращаясь к нашим профессионалам, вы можете не только поинтересоваться тем, как обстоят ваши дела в отношении спорной или конфликтной ситуации и есть ли у вас шансы выиграть дело. У нас вы можете получить настоящую бесплатную эффективную юридическую помощь, ведь наши специалисты имеют огромный практический опыт решения вопросов в самых различных сферах права.
        </p>
	<div class="inside">		
            <div class="center-align">
            <?php
                // выводим виджет с номером 8800
                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                    'showAlways'    =>  true,
                ));
            ?>		
            </div>
	</div>
<br/>
<h3 class="">Последние ответы юристов на вопросы</h3>
<div class="vert-margin30">
    <?php
    // выводим виджет с последними ответами
    $this->widget('application.widgets.RecentAnswers.RecentAnswers', array(
        'template' => 'page',
        'limit' => 4,
        'cacheTime' => 3600,
    ));
    ?>
</div>

        <h2>Онлайн консультация юриста</h2>
        <p>
            Помощь юриста и его консультация может быть предоставлена в самых различных сферах правовой практики. Заметьте, что данная услуга оказывается бесплатно. Собственно, вам дается возможность получить юридическую консультацию по вопросам, которые касаются:
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
            И это еще далеко не полный перечень сфер, касаемо которых вы можете задать интересующий вас вопрос и получить реальную помощь. Ведь на нашем портале бесплатные юридические консультации граждан по правовым вопросам могут касаться абсолютно любых инцидентов.
        </p>


<h3 class="vert-margin20">Юристы портала онлайн</h3>
<div class='vert-margin30'>

    <?php
    // выводим виджет с топовыми юристами
    $this->widget('application.widgets.TopYurists.TopYurists', array(
        'cacheTime' => 300,
        'limit' => 6,
    ));
    ?>

    <p class="right-align">
        <?php echo CHtml::link('Все юристы', Yii::app()->createUrl('region/country', ['countryAlias' => 'russia'])); ?>
    </p>
</div>


<div style="text-align: justify;">

    <div class="hidden-xs">

        <h2>Как получить бесплатную юридическую консультацию</h2>
        <p>
            Стоит отметить, что бесплатная юридическая консультация онлайн, во время которой можно задать вопросы, проводится в любое удобное для вас время. Это значит, что при необходимости вы можете получить нужную информацию даже в ночное время. В целом, мы работаем с такими областями права, как:
        </p>
        <ul>
            <li>семейное законодательство;</li>
            <li>административные и гражданские вопросы;</li>
            <li>уголовные дела;</li>
            <li>земельное и налоговое право;</li>
            <li>иные темы, касающиеся законодательства и юриспруденции. </li>
        </ul>
        <h2>Бесплатная юридическая консультация онлайн:</h2>
        <p>
            Для этого на сервисе организован специальный функционал. Вы сможете получить ответ от наших юристов не только в режиме онлайн и круглосуточно но и совершенно бесплатно! Для этого вам нужно только заполнить специальную форму, описав свою ситуацию как можно подробнее, чтобы юрист или адвокат нашего проекта смог детально в ней разобраться и дать квалифицированный ответ на ваш вопрос.
        </p>
        <p>
            Если у вас возникли проблемы в кругу семьи, к примеру, существует угроза развода или же дело касается раздела совместного имущества, то помочь вам может только квалифицированный юрист, который сделает все для того, чтобы решить конфликт мирным путем.
        </p>
        <p>
            В современном обществе многим людям приходится сталкиваться с потребностью отстаивания своих прав и интересов в области хозяйствования. Поэтому мы предлагаем вам услугу: бесплатный юрист онлайн без регистрации. При несоблюдении условий договоров, при наличии хозяйственного спора наши квалифицированные специалисты проведут для вас полноценную  юидическую консультацию с возможностью предоставления помощи на практике.
        </p>
        <p>
            Предоставление бесплатной юридической консультации специалиста в области права, также можно получить пконсультацию относительно трудового и гражданского права, во время расследования и судебного рассмотрения уголовных дел, страховых споров и так далее. Собственно, если у вас появляются затруднения с решением проблем именно с правовой точки зрения, наша команда всегда готова предоставить вам свои высококлассные услуги для того, чтобы закрыть проблему с наибольшей выгодой для вас.
        </p>
        <p>
            На нашем сайте вы можете получить бесплатную правовую помощь без регистрации.  Вы можете задать любой вопрос юристу или самостоятельно найти ответ в нашей правовой базе.
        </p>
        <p>
            <strong>Бесплатная юридическая консультация онлайн и по телефону круглосуточно.</strong>
        </p>



    </div>
</div>
