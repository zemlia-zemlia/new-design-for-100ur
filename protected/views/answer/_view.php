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
                <img src="<?php echo $data->author->getAvatarUrl();?>" class="img-responsive img-bordered" />
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
                
                <?php if($data->authorId == Yii::app()->user->id && time()-strtotime($data->datetime)<Answer::EDIT_TIMEOUT):?>
                <div class="right-align">
                    <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('question/updateAnswer', array('id'=>$data->id)), array('class'=>'btn btn-default btn-xs'));?>
                </div>
                <?php endif;?>
                
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
                    
                        <div class="vert-margin20 answer-karma-string" >
                            
                                
                            <?php if($showKarmaLink === true):?>   
                            <span id="answer-karma-<?php echo $data->id;?>">    
                                <?php echo CHtml::link("Отметить как полезный!", Yii::app()->createUrl('user/karmaPlus'), array('class'=>'link-karma-plus btn btn-warning btn-xs', 'data-id'=>$data->id));?>
                            </span>
                            <?php endif;?>
                            
                            <?php if(Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->role == User::ROLE_ROOT):?>
                                <a href="#" class='btn btn-xs btn-default donate-yurist-link'><span class="glyphicon glyphicon-ruble"></span> Отблагодарить</a>
                            <?php endif;?>
                                
                            <?php if(Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->role == User::ROLE_ROOT):?>
                            <div class='donate-block'>
                                <?php $this->renderPartial("application.views.question._donateForm", array(
                                    'target'        =>  'Благодарность юристу #' . $data->authorId,
                                    'successUrl'    =>  Yii::app()->createUrl('question/view', array('id'=>$data->questionId, 'answer_payed_id'=>$data->id)),
                                ));?>
                            </div>
                            <?php endif;?>
                        </div>
                    
                <?php endif;?>
                              
                <?php foreach($data->comments as $comment):?>
                    <?php //CustomFuncs::printr($comment->attributes); continue;?>
                    <?php if($comment->status != Comment::STATUS_SPAM):?>
                        <div class="answer-comment" style="margin-left:<?php echo ($comment->level - 1)*20;?>px;">
                            <p> <strong><span class="glyphicon glyphicon-comment"></span> 
                                <?php if($data->question->authorId == $comment->authorId) {
                                          echo "Автор вопроса:";
                                      } elseif($data->authorId == $comment->authorId) {
                                      ?>    
                                            <?php echo $data->author->getShortName();?>
                                            <?php if($data->author->settings->isVerified):?>
                                            <small>
                                                <span class="label label-default"><?php echo $data->author->settings->getStatusName();?></span>
                                            </small>
                                            <?php endif;?>
                                      <?php    
                                      }
                                ?>
                                </strong>
                            </p>
                            <p>
                                <?php echo CHtml::encode($comment->text);?>
                            </p>
                            <?php if(!is_null($commentModel) && ($data->authorId == Yii::app()->user->id ||  $data->question->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_ROOT))):?>
                            <div class="right-align">
                            <a class="btn btn-xs btn-default" role="button" data-toggle="collapse" href="#collapse-comment-<?php echo $comment->id;?>" aria-expanded="false">
                                Ответить
                              </a>
                            </div>    
                            <div class="collapse child-comment-container" id="collapse-comment-<?php echo $comment->id;?>">
                                <strong>Ваш ответ:</strong>
                                <?php 
                                    $this->renderPartial('application.views.comment._form', array(
                                        'type'      => Comment::TYPE_ANSWER,
                                        'objectId'  => $data->id,
                                        'model'     => $commentModel,
                                        'hideRating'=>  true,
                                        'parentId'  =>  $comment->id,
                                    ));
                                ?>
                            </div>
                            <?php endif;?>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>
                
                
                <?php if(!is_null($commentModel) && ($data->question->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_ROOT))):?>
                    <strong>Ваш комментарий:</strong>
                    <?php 
                        $this->renderPartial('application.views.comment._form', array(
                            'type'      => Comment::TYPE_ANSWER,
                            'objectId'  => $data->id,
                            'model'     => $commentModel,
                            'hideRating'    =>  true,
                            'parentId'  =>  0,
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