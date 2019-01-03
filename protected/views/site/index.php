<?php
$this->setPageTitle("Бесплатная юридическая консультация онлайн и по телефону круглосуточно" . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("На нашем сайте вы можете получить бесплатную правовую помощь без регистрации.  Вы можете задать любой вопрос юристу или самостоятельно найти ответ в нашей правовой базе.", 'description');
Yii::app()->clientScript->registerMetaTag("Бесплатная юридическая консультация онлайн", 'keywords');
Yii::app()->clientScript->registerLinkTag("canonical", NULL, "https://" . $_SERVER['SERVER_NAME']);
?>
<h1>Бесплатная юридическая консультация</h1>
    <p>
        Наш интернет-портал сотрудничая с юристами и адвокатами высокого профессионального уровня предоставляет возможность получить профессиональную бесплатную юридическую консультацию онлайн, а также заказать нужный документ или найти юриста из вашего города или региона для представления интересов в судах и организациях. Получить бесплатную юридическую консультацию онлайн могут как жители Москвы и СПБ, так и других регионов России и СНГ. На все вопросы отвечают специалисты которые прошли проверку наличия профильного образования и знаний сотрудниками нашего портала.
    </p>
    <p>
        Консультации от профессионалов - вы можете не только поинтересоваться тем, как обстоят ваши дела в отношении спорной или ситуации, а также в случае судебного процесса понять есть ли у вас шансы выиграть дело в котором вы участвуете не только как истец, но и как ответчик. Вы можете получить настоящую бесплатную эффективную юридическую помощь и поддержку, ведь наши специалисты имеют огромный практический опыт взаимодействия в вопросах самых различных сфер и направлений.
    </p>
	<div class="inside flat-panel">		
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
<h3>Задайте свой вопрос юристам портала бесплатно</h3>
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
    Помощь специалиста в суде или его консультация может быть предоставлена в самых различных сферах правовой практики. Заметьте, что данная услуга оказывается бесплатно. Вам дается возможность получить юридическую консультацию по всем вопросам, с которыми вы можете столкнуться на работе, дома, на даче и т.д. Юрист нам требуется не менее часто чем например врач, ведь наши с вами права могут нарушаться где угодно, от незаконного сбора членских взносов в дачном кооперативе до залива квартиры соседями или обмана в магазине. При каждом таком случае необходимо понимать как поступить правильно чтобы не усугубить ситуацию или ее разрешить, в этом вам помогут адвокаты нашего портала. И для этого не обязательно регистрироваться или оставлять свой номер телефона, все можно сделать в режиме онлайн.
    <br/>
    Стоит отметить, что бесплатная юридическая консультация онлайн, во время которой можно задать вопросы, проводится в любое удобное для вас время. Это значит, что при необходимости вы можете получить нужную информацию даже в ночное время.
</p>


<h3 class="vert-margin20">Специалисты портала онлайн</h3>
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


<div>

    <div class="hidden-xs">
        <h2>Бесплатная юридическая консультация онлайн</h2>
		<p>Для этого у нас есть специальный функционал:</p>
		<ul>
        <li class="vert-margin30">
            <strong>Задать вопрос юристу онлайн бесплатно (консультация онлайн)</strong> - в специальной форме вы описываете свою ситуацию как можно подробнее, чтобы юрист или адвокат нашего проекта смог детально в ней разобраться и дать квалифицированный ответ на заданный вопрос. При этом вас не обязательно сидеть и ждать ответ, при добавлении каждого ответа на вопрос мы вас оповестим по email который вы укажете. Вы сможете получить помощь не только в режиме онлайн и круглосуточно, но и совершенно бесплатно!
        </li>
        <li class="vert-margin30">
            <strong>Консультация по телефону</strong> - Вы можете оставить свой запрос на консультацию по телефону со специалистом нашего портала и вам перезвонят, для вас консультация будет абсолютно бесплатной и без регистрации.
        </li>
        <li class="vert-margin30">
            <strong>Заказ документа</strong> - Предоставляет возможность заказать любой документ который будет составлен грамотным специалистом и в котором вы будете уверены на 100%, что он не будет вам возвращен или по нему будет вынесен отказ органом или организацией в которую вы его предоставите изза его неправильного составления.
        </li>
        <li class="vert-margin30">
            <strong>Заказ услуги</strong> - Если вам требуется защита в суде или в любой другой организации вы можете создать запрос на получение такой услуги и на него вам будут приходить отклики, останется только выбрать специалиста.
        </li>
		
		</ul>
        <p>
            Людям часто приходится сталкиваться с необходимостью отстаивания своих прав и интересов во многих областях, большая часть из них перечислена в левой части (колонке) нашего портала которая озаглавлена как "темы вопросов". Наш юридический портал предлагает вам услугу: бесплатный юрист онлайн без регистрации. При несоблюдении условий договоров, при наличии хозяйственного спора наши квалифицированные специалисты проведут для вас полноценную консультацию с возможностью предоставления помощи на практике.
        </p>
		<p>
            Предоставление бесплатной консультации в любой области права, также можно получить консультацию относительно трудового и гражданского права, при наличии проблем с кредитами или процесса банкротства, земельных споров и так далее. Собственно, если у вас имеются затруднения с решением вашего вопроса именно с точки зрения юриспруденции, наши специалисты всегда готова предоставить вам свои услуги для того, чтобы решить проблему с наибольшей выгодой для вас.
        </p>
        <p>
            На нашем сайте вы можете получить бесплатную правовую помощь без регистрации. Вы можете задать любой вопрос юристу или самостоятельно найти ответ в нашем архиве вопросов.
        </p>
        <p>
            <strong>Бесплатная юридическая консультация онлайн и по телефону, круглосуточно и без регистрации.</strong>
        </p>
    </div>
</div>
