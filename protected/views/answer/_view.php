<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<div class="vert-margin30">

    <?php if($data->author):?>
    <strong>
        <?php echo CHtml::encode($data->author->name); ?>
        <span class="muted"><?php echo CHtml::encode($data->author->position);?></span>
    </strong>
    <?php endif;?>
    
    <p>
	<?php echo CHtml::encode($data->answerText); ?>
    </p>
    <?php echo CHtml::link('Редактировать ответ', Yii::app()->createUrl('answer/update',array('id'=>$data->id)));?>
</div>