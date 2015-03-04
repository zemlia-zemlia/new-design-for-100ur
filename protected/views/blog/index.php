<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Блог" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Блог',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Блог</h1>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'summaryText'   =>  '',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
