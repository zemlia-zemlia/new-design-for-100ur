<?php
Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('yurist'));

$pageTitle = "Юристы и адвокаты ";

$this->setPageTitle($pageTitle . Yii::app()->name);
?>

<h1 class="vert-margin30">Юристы и Адвокаты</h1>

<div class="alert alert-success">
    <h4>Вы юрист?</h4>
    <p>
        Хотите получать клиентов, отвечая на вопросы на нашем сайте?
        <br />
        Напишите нам: admin@100yuristov.com
    </p>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $yuristsDataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного юриста',
        'summaryText'   =>  'Показаны юристы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>