<?php
Yii::app()->clientScript->registerLinkTag("canonical",NULL,"https://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('yurist'));

$pageTitle = "Юристы и адвокаты ";

$this->setPageTitle($pageTitle . Yii::app()->name);
?>

<h1 class="vert-margin30">Юристы и адвокаты</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $yuristsDataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного юриста',
        'summaryText'   =>  'Показаны юристы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>