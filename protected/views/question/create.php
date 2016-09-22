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
<div class='panel gray-panel'>
    <div class='panel-body'>
		<h1>Задайте вопрос юристу</h1>
	</div>
</div>	
<div class='panel gray-panel'>
    <div class='panel-body'>
			<h5>Как это работает?</h5>
		<ul>
			<li>Вы оставляете свой вопрос</li>
			<li>Вам перезванивают в течении 15 минут</li>
			<li>Или отвечают письменно на сайте</li>
		</ul>
    </div>
</div>
<div class='panel gray-panel'>
    <div class='panel-body'>
        <?php echo $this->renderPartial('_form', array(
            'model'         =>  $model,
            'allCategories' =>  $allCategories,
            'categoryId'    =>  $categoryId,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>
<div class='panel gray-panel'>
    <div class='panel-body'>
			<div class="col-md-4 form-info-item">
				<p><span class="form-icon" style="background-position: 0 0;"></span><strong>Это быстро</strong><br />
				Вы получите ответ через 15 минут</p>
			</div>
			<div class="col-md-4 form-info-item">
				<p><span class="form-icon" style="background-position: -32px 0;"></span><strong>Безопасно</strong><br />
				Только аккредитованные юристы</p>
			</div>
			<div class="col-md-4 form-info-item">
				<p><span class="form-icon" style="background-position: -67px 0;"></span><strong>Без спама</strong><br />
				Мы никогда не рассылаем рекламу</p>
			</div>
    </div>
</div>			
			