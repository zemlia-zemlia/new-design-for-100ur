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
        <div itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
            <?php if($data->author):?>
            <div itemprop="author" itemscope itemtype="http://schema.org/Person">
            <strong>
                <span itemprop="name">
                    <?php echo CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->name2); ?>
                </span>
                <span class="muted"><?php echo CHtml::encode($data->author->position);?></span>
            </strong>
            </div>
            <?php endif;?>
            <div itemprop="text">
                <p>
                    <?php echo CHtml::encode($data->answerText); ?>
                </p>
            </div>
        </div>    
    </div>
</div>