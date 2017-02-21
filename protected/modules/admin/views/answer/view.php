<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->id) . ". Ответы. ". Yii::app()->name);

$this->breadcrumbs=array(
	'Ответы'    =>  array('index'),
	$model->id,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>
<div class="vert-margin30">
<h1>Ответ #<?php echo $model->id; ?></h1>
</div>

<div class="vert-margin30">
<p>
    <?php echo nl2br(CHtml::encode($model->answerText));?>
</p>
</div>

<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?> 
<div class="vert-margin30">
    <p><strong>Статус:</strong> <?php echo CHtml::encode($model->getAnswerStatusName()); ?>
        <span class="muted"><?php echo CustomFuncs::niceDate($model->datetime) . ' ' . CHtml::encode($model->author->name . ' ' .$model->author->lastName);?></span>
    </p>
    
    <p><strong>Автор:</strong> <?php echo CHtml::encode($model->author->lastName . ' ' . $model->author->name); ?></p>
</div>

<?php echo CHtml::link('Редактировать ответ', Yii::app()->createUrl('/admin/answer/update',array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>

<?php endif;?>