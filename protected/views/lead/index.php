<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Заявки. " . Yii::app()->name;        
?>

<div  class="vert-margin20">
    <h1>Заявки от клиентов</h1>
</div>

<ul class="nav nav-tabs vert-margin40">
    <li role="presentation" class="<?php echo ($showMy == true) ? '':'active';?>">
        <?php echo CHtml::link('Новые', Yii::app()->createUrl('/lead/index')); ?>
        
    </li>
    <li role="presentation" class="<?php echo ($showMy == true) ? 'active':'';?>">
        <?php echo CHtml::link('Мои заявки', Yii::app()->createUrl('/lead/index', ['my' => 1])); ?>
    </li>
</ul>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'viewData' => [
            'showMy'    =>  $showMy,
        ],
        'emptyText' =>  'Не найдено ни одной заявки',
        'summaryText'=>'',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
