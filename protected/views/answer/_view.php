<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>
<?php if($data->status!=Answer::STATUS_SPAM):?>
<div class="panel gray-panel">
    <div class='panel-body'>
<div class='answer-item'>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <div class="answer-item-avatar">
                <img src="<?php echo $data->author->getAvatarUrl();?>" class="img-responsive" />
                </div>
            </div>
            <div class="col-sm-10">
                <div class="answer-item-author-block">
                    <small>
                    <div itemprop="author" class='answer-item-author' itemscope itemtype="http://schema.org/Person">
                        <span class="glyphicon glyphicon-comment"></span> Отвечает 
                        <?php if($data->author->settings->isVerified):?>
                            <span class="label label-success"><?php echo $data->author->settings->getStatusName();?></span>
                        <?php endif;?>
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

                    <span class="glyphicon glyphicon-signal"></span>    
                    <?php echo $data->author->answersCount . ' ' . CustomFuncs::numForms($data->author->answersCount, 'ответ', "ответа", "ответов");?>     

                    
                        &nbsp;&nbsp;
                    <?php if(isset($data->author->town)):?>
                        <span class="glyphicon glyphicon-map-marker"></span> <?php echo $data->author->town->name;?>
                    <?php endif;?>
                        
                    
                        <br />
                    <?php if($data->datetime):?>
                        <span class="glyphicon glyphicon-calendar"></span> <?php echo CustomFuncs::niceDate($data->datetime, false);?>
                    <?php endif;?>

                    </div>
                    </small>
                </div>
                
                <div itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="http://schema.org/Answer">

                    <div itemprop="text">
                        <p>
                            <?php echo CHtml::encode($data->answerText); ?>
                        </p>
                    </div>
                </div> 
                
            </div>
        </div>

    </div>
    


    </div>
</div> 
</div>
<?php endif;?>