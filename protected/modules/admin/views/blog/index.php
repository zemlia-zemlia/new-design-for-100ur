<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Новости" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Новости',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('CRM',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>


<h1>Новости</h1>

<div class="right-align">
    <?php if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
        <?php echo CHtml::link('Добавить новость', Yii::app()->createUrl('/admin/post/create'), array('class'=>'btn btn-primary')); ?>
    <?php endif;?>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.modules.admin.views.post._view',
        'summaryText'   =>  '',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>

