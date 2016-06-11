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
        'homeLink'=>CHtml::link('CRM',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>


<h1>Блог</h1>

<div class="right-align">
    <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
        <?php echo CHtml::link('Создать пост', Yii::app()->createUrl('/admin/post/create'), array('class'=>'btn btn-primary')); ?>
    <?php endif;?>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.modules.admin.views.post._view',
        'summaryText'   =>  '',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>

