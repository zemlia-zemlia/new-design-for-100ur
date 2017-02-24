<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Задать вопрос юристам и адвокатам. ". Yii::app()->name);

Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);


$this->breadcrumbs=array(
	'Вопросы и ответы'=>array('index'),
	'Новый вопрос',
);

?>

<h1 class="header-block header-block-light-grey">Задайте вопрос юристу</h1>


<h2 class="text-uppercase">Как это работает?</h2>

<div class="vert-margin30">
    <img src="/pics/2017/how_it_works.gif" alt="Задать вопрос юристу" class="center-block" />
</div>


<div class='flat-panel vert-margin30'>
    <div class='inside'>
        <?php echo $this->renderPartial('_form', array(
            'model'         =>  $model,
            'allDirections' =>  $allDirections,
            'categoryId'    =>  $categoryId,
            'pay'           =>  $pay,
        )); ?>
    </div>
</div>
		
<!-- 
<div class="row center-align">
    <div class="col-sm-4">
            <img src="/pics/2017/icon_green_fast.png" alt="Быстро" class="center-block" />
            <h3>Это быстро</h3>
            <p>
            Вы получите ответ через 15 минут</p>
    </div>
    <div class="col-sm-4">
            <img src="/pics/2017/icon_green_safe.png" alt="Безопасно" class="center-block" />
            <h3>Безопасно</h3>
            <p>
            Только аккредитованные юристы</p>
    </div>
    <div class="col-sm-4">
            <img src="/pics/2017/icon_green_nospam.png" alt="Без спама" class="center-block" />
            <h3>Без спама</h3>
            <p>
            Мы никогда не рассылаем рекламу</p>
    </div>
</div>
-->