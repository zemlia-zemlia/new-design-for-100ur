<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<div class='answer-item'>
    
        <div itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
            <?php if($data->author):?>
            <div itemprop="author" class='answer-item-author' itemscope itemtype="http://schema.org/Person">
            <img src='/pics/2015/icon_comment.png' alt='' /> Отвечает
            &nbsp;&nbsp;
            <img src='/pics/2015/icon_user.png' alt='' />
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