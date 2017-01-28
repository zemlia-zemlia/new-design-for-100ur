<?php
    $this->setPageTitle("Вопрос юристу и адвокату онлайн, юридическая помощь адвоката в Москве и СПБ ". Yii::app()->name);
    Yii::app()->clientScript->registerMetaTag("Задать вопрос адвокату без телефона и регистрации круглосуточно по всей России. Бесплатная помощь юриста онлайн и по телефону в Москве и Санкт-Петербурге.", 'description');

?>

        <div class="flat-panel">

            <h3 class="header-block-light-grey">Последние вопросы юристам и адвокатам портала</h3>   
        
            <div class="inside">
                <?php foreach($questions as $question):?>
                    <div class="row question-list-item <?php if($question['payed'] == 1):?> vip-question<?endif;?>">
                        <div class="col-sm-9">
                            <p style="font-size:1.1em;">
                                <?php if($question['payed'] == 1){
                                    echo "<span class='label label-primary'><abbr title='Вопрос с гарантией получения ответов'>VIP</abbr></span>";
                                }
                                ?>
                                <?php echo CHtml::link($question['title'], Yii::app()->createUrl('question/view', array('id'=>$question['id'])));?>
                            </p>
                        </div>

                        <div class="col-sm-3">

                        <?php if($question['counter'] == 1) {
                            echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>Есть ответ</span>";
                        } elseif($question['counter']>1) {
                            echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>" . $question['counter'] . ' ' . CustomFuncs::numForms($question['counter'], 'ответ', 'ответа', 'ответов') . "</span>";
                        } elseif($question['counter'] == 0) {
                            echo "<span class='label label-default'>Нет ответа</span>";
                        }
                        ?>
                        </span>
                    </div>
                    </div>
                <?php endforeach;?>
        
                <div class='right-align'>
                    <?php echo CHtml::link('Посмотреть все вопросы &raquo;', Yii::app()->createUrl('/question'), array('style'=>'color:#a2a2a2;'));?>
                </div>
            </div>
        </div>



<h1>Профессиональная помощь юриста по любым вопросам на всей территории РФ</h1>
<p style="text-align: justify;">
	 «100 Юристов» - уникальный сервисный портал, на котором каждый может получить квалифицированную помощь юриста онлайн режиме. Также наши специалисты всегда готовы проконсультировать по телефону, ответив на вопросы, касающиеся всех отраслей права РФ и других государств.
</p>
<p style="text-align: justify;">
	 Мы высоко ценим доверие и время наших клиентов, посетителей сайта, поэтому предоставляем грамотную юридическую поддержку на следующих условиях:
</p>

<ul>
	<li>Бесплатно;</li>
	<li>Профессионально;</li>
	<li>Оперативно;</li>
	<li>Всесторонне, доступно и понятно для людей, не имеющих отношения к юриспруденции.</li>
</ul>

<p style="text-align: justify;">
	 На нашем портале юридическая помощь оказывается по всем направлениям, отраслям и сферам права. Многолетний опыт сотрудников и консультантов, высокий уровень ответственности и тотальная ориентированность на пользователей сервиса позволяют эффективно разрешать даже наиболее сложные и специфические юрвопросы.
</p>
<h2 style="text-align: center;">Насколько эффективна онлайн помощь адвоката?</h2>
<p>
	 Бесплатная онлайн помощь юриста, в первую очередь, обеспечивает своевременную поддержку, предоставляет возможность сориентироваться в возникшей проблеме, способах и методах ее решения. Наша работа направлена на повышение юридической грамотности граждан, обратившихся в правовой портал «100 Юристов», оказание содействия при отстаивании личных интересов, прав, свобод, чести и достоинства.
</p>
<p style="text-align: justify;">
	 Задавая вопрос юристу по телефону или на сайте, Вы получаете подробный и грамотный ответ, который поможет разобраться во многих тонкостях и «подводных камнях» определенной отрасли отечественного или зарубежного права, подскажет, как корректно вести себя в той или иной ситуации, какие шаги необходимо делать, а какие действия совершать ни в коем случае нельзя.
</p>

<h3 class="header-block header-block-green">По каким вопросам можно получить юридическую помощь?</h3>
<div class="header-block-green-arrow"></div>

<p style="text-align: justify;">
	 Как указано выше, портал «100 Юристов» - это многофункциональный сервис, который охватывает все отрасли и сферы права. Но, судя по статистике, чаще всего услуги адвоката требуются людям, которые столкнулись с проблемами при разделе имущества, разводе, определении прав наследования, обжаловании исков или решений в судах, разрешении конфликтов с банками и прочими финансовыми, кредитными организациями.
</p>
<p style="text-align: justify;">
	 Регистрация предприятия, его законодательно грамотная реорганизация или ликвидация, вопросы, касающиеся ведения, восстановления налогового и бухгалтерского учета – еще одни популярные направления консультирования наших специалистов, по которым также предоставляется оперативная и высококвалифицированная юридическая помощь в СПб и других регионах РФ. И оказывают ее высококлассные специалисты, имеющие солидный опыт работы в профильной сфере.
</p>


<h3 class="header-block header-block-green">Как воспользоваться помощью адвоката и юриста?</h3>
<div class="header-block-green-arrow"></div>

<p style="text-align: justify;">
	 Получить грамотную помощь адвоката или индивидуальную консультацию юриста на нашем портале легко, просто и доступно для каждого:
</p>

<ul>
	<li>По телефону – в Москве и МО, в Санкт-Петербурге и ЛО;</li>
	<li>Онлайн без телефона и регистрации – для граждан РФ вне зависимости от их местонахождения.</li>
</ul>

<p style="text-align: justify;">
	 Сплоченная команда опытных профессионалов всегда готова оперативно отреагировать на Ваши обращения, подробно, обстоятельно и главное – понятно, объяснив, каким образом Вас следует вести себя в определенной ситуации, чтобы защитить собственные интересы и права. Мы подбираем только эффективные и рациональные решения, в полной мере отвечающие действующим законам и регламентам, помогая найти выход даже из самых запутанных и сложных ситуаций.
</p>
<p style="text-align: justify;">
 Задавайте актуальные для Вас вопросы, получайте круглосуточную квалифицированную правовую поддержку бесплатно прямо сейчас. 
</p>