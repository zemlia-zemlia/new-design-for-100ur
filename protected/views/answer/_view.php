<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<div class="vert-margin30 row">
    <div class="col-md-2 col-sm-3">
<?php if($data->author->avatar):?>
    
    <?php echo CHtml::image($data->author->getAvatarUrl('thumb'),'', array('class'=>'img-responsive'));?>

<?php endif;?>
    </div>
    <div class="col-md-10 col-sm-9">
    
        <?php if($data->author):?>
        <strong>
            <?php echo CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->name2); ?>
            <span class="muted"><?php echo CHtml::encode($data->author->position);?></span>
        </strong>
        <?php endif;?>

        <p>
            <?php echo CHtml::encode($data->answerText); ?>
        </p>
    </div>
</div>