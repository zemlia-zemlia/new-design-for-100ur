<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>
<?php if($data->status!=Answer::STATUS_SPAM):?>
<div class='answer-item'>
    
        <div itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
            <?php if($data->author):?>
            <div itemprop="author" class='answer-item-author' itemscope itemtype="http://schema.org/Person">
            <span class="glyphicon glyphicon-comment"></span> Отвечает
            &nbsp;&nbsp;
            <span class="glyphicon glyphicon-user"></span>
            <strong>
                <span itemprop="name">
                    <?php if($data->author->settings && $data->author->settings->alias):?>
                        <?php echo CHtml::encode($data->author->settings->alias);?>
                    <?php else:?>
                        <?php echo CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->name2); ?>
                    <?php endif;?>
                </span>
            </strong>
            &nbsp;&nbsp;
            <?php if($data->datetime):?>
                <span class="glyphicon glyphicon-calendar"></span> <?php echo CustomFuncs::niceDate($data->datetime, false);?>
            <?php endif;?>
            </div>
            <?php endif;?>
            <div itemprop="text">
                <p>
                    <?php echo CHtml::encode($data->answerText); ?>
                </p>
            </div>
        </div>    

</div>
<?php endif;?>