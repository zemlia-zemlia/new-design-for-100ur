<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>
<?php if($data->status!=Answer::STATUS_SPAM):?>
<div class="flat-panel">
    <div class='inside'>
<div class='answer-item'>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <div class="answer-item-avatar">
                <img src="<?php echo $data->author->getAvatarUrl();?>" class="img-responsive" />
                </div>
                <div class="answer-item-karma">
                    <small>
                    Рейтинг: <?php echo $data->author->karma;?>
                    </small>
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
                            <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$data->authorId));?>">
                            <?php if($data->author->settings && $data->author->settings->alias):?>
                                <?php echo CHtml::encode($data->author->settings->alias);?>
                            <?php else:?>
                                <?php echo CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->name2); ?>
                            <?php endif;?>
                            </a>
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
                
                <?php if(!Yii::app()->user->isGuest && $data->authorId != Yii::app()->user->id):?>
                    <?php 
                        // проверим, не голосовал ли текущий пользователь за данный ответ
                        $showKarmaLink = true;
                        foreach($data->karmaChanges as $karmaChange) {
                            if($karmaChange->authorId == Yii::app()->user->id) {
                                $showKarmaLink = false;
                                break;
                            }
                        }
                    ?>
                    <?php if($showKarmaLink === true):?>
                        <div class="vert-margin20 answer-karma-string" id="answer-karma-<?php echo $data->id;?>">
                        Ответ оказался полезен? <?php echo CHtml::link("Да", Yii::app()->createUrl('user/karmaPlus'), array('class'=>'link-karma-plus btn btn-success btn-xs', 'data-id'=>$data->id));?>
                        </div>
                    <?php endif;?>
                <?php endif;?>
                
                <?php foreach($data->comments as $comment):?>
                    <div class="answer-comment">
                        <p>
                            <?php echo CustomFuncs::niceDate($comment->dateTime);?> 
                            <?php echo CHtml::encode($comment->author->name);?></p>
                        <p>
                            <?php echo CHtml::encode($comment->text);?>
                        </p>
                    </div>
                
                <?php endforeach;?>
                
                
                <?php if($data->question->authorId == Yii::app()->user->id):?>
                    <?php 
                        $this->renderPartial('application.views.comment._form', array(
                            'type'      => Comment::TYPE_ANSWER,
                            'objectId'  => $data->id,
                            'model'     => $commentModel,
                            'hideRating'    =>  true,
                        ));
                    
                    ?>
                <?php endif;?>
            </div>
        </div>

    </div>
    


    </div>
</div> 
</div>
<?php endif;?>