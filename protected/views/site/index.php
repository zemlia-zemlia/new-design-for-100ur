<?php
    $this->setPageTitle("Консультация юриста и адвоката. ". Yii::app()->name);
    Yii::app()->clientScript->registerMetaTag("Консультация юриста по всем отраслям права, только профессиональные юристы и адвокаты.", 'description');

?>
<!--
<div class="panel">
    <div class="panel-body">
        <p>
            This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. 
        </p>
    </div>
</div>
-->

<h1>Последние вопросы</h1>

<div class="panel">
    <div class="panel-body">
            
        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProvider,
                'itemView'      =>  'application.views.question._viewShort',
                'emptyText'     =>  'Не найдено ни одного вопроса',
                'summaryText'   =>  '',

        )); ?>
        
        <div class='right-align'>
            <?php echo CHtml::link('Посмотреть все вопросы &raquo;', Yii::app()->createUrl('question/index'), array('style'=>'color:#a2a2a2;'));?>
        </div>
    </div>
</div>

<h3>Наши гарантии</h3>

<div class='panel orange-panel'>
    <div class='row'>
        <div class='col-md-4 col-sm-4'>
            <img src='/pics/2015/thumb_up_orange.png' alt='ВЫСОКОЕ КАЧЕСТВО' class='center-block' />
            <h5>ВЫСОКОЕ КАЧЕСТВО</h5>
            <p>
                Все сертифицированные юристы проекта  проходят обязательную проверку образования и опыта работы.
            </p>
        </div>
        <div class='col-md-4 col-sm-4'>
            <h5>ЭКОНОМИЯ ВРЕМЕНИ</h5>
            <p>
                Вы получаете ответ  на свой
                вопрос в максимально 
                сжатые сроки.
            </p>
            <img src='/pics/2015/clock_orange.png' alt='ЭКОНОМИЯ ВРЕМЕНИ' class='center-block' />
        </div>
        <div class='col-md-4 col-sm-4'>
            <img src='/pics/2015/shield_orange.png' alt='КОНФИДЕНЦИАЛЬНОСТЬ' class='center-block' />
            <h5>КОНФИДЕНЦИАЛЬНОСТЬ</h5>
            <p>
                Ваши персональные данные нигде не публикуются.  Передача  любой информации защищена  сертификатом SSL.
            </p>
        </div>
    </div>
</div>

<h3>Все вопросы</h3>


            
<?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'      =>  'application.views.question._view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',

)); ?>


<h3>При поддержке</h3>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 center-align">
                <img class="img-responsive center-block" alt="При поддержке правительства РФ" src="/pics/pravitelstvo.png">
                <p class="center-align">Правительство РФ
                </p>
            </div>

            <div class="col-md-6 col-sm-6 center-align"> 
                <img class="img-responsive center-block" alt="При поддержке Министерства Юстиции" src="/pics/minyust.png"> 
                <p class="center-align">Министерство Юстиции</p>
            </div>
        </div>
    </div>
</div>

<!--
<div class="panel">
    <div class="panel-body">
        <p>
            This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. 
        </p>
    </div>
</div>
-->