<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<?php $videoCode = $data->getVideoCode();?>

<?php if($data->status!=Answer::STATUS_SPAM):?>
<div class="flat-panel">
    <div class=''>
<div class='answer-item'>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2 col-xs-4">
                <?php if($videoCode):?>
                <img src='/pics/2017/logo_white.png' class='img-responsive' alt='100 Юристов' />
                <?php else:?>
                    <div class="answer-item-avatar">
                        <img src="<?php echo $data->author->getAvatarUrl();?>" alt="<?php echo CHtml::encode($data->author->name . ' ' . $data->author->lastName);?>" class="img-responsive img-bordered" />
                    </div>
                    <?php if(floor((time() - strtotime($data->author->lastActivity))/60)<60):?>
                        <div class="center-align"><small><span class="label label-success">онлайн</span></small></div>
                    <?php endif;?>
                    
                <?php endif;?>
            </div>
            <div class="col-sm-10 col-xs-8">
                
                <?php if($videoCode):?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $videoCode;?>" allowfullscreen></iframe>
                    </div>
                <?php else:?>
                <div class="answer-item-author-block">
                    <small>
                    <div itemprop="author" class='answer-item-author' itemscope itemtype="http://schema.org/Person">
                        
                    <span class="glyphicon glyphicon-user"></span>
                    <strong>
                        <span itemprop="name">
                            <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$data->authorId));?>">
                                <?php echo CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->lastName); ?>
                            </a>
                        </span>
                    </strong>
                    
                    <?php if($data->author->settings->isVerified):?>
                            <span class="label label-success"><?php echo $data->author->settings->getStatusName();?></span>
                        <?php endif;?>
                    &nbsp;&nbsp;
                    <br />

                    <span class="glyphicon glyphicon-signal"></span>    
                    <?php echo $data->author->answersCount . ' ' . CustomFuncs::numForms($data->author->answersCount, 'ответ', "ответа", "ответов");?>     

                    &nbsp;&nbsp;
                    
                    <span class='glyphicon glyphicon-thumbs-up'></span> <?php echo $data->author->karma;?>
                        <br />
                    <?php if($data->datetime):?>
                        <span class="glyphicon glyphicon-calendar"></span> <?php echo CustomFuncs::niceDate($data->datetime, false);?>
                    <?php endif;?>

                    </div>
                    </small>
                </div>
                
            </div>
            
            <div class="col-xs-12">
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
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-thumbs-up'></span> Отметить как полезный!", Yii::app()->createUrl('user/karmaPlus'), array('class'=>'link-karma-plus btn btn-warning btn-xs', 'data-id'=>$data->id));?>
                            </span>
                            <?php endif;?>
							
                            <? /*
                            <?php if(Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->role == User::ROLE_ROOT):?>
                                <!--<a href="#" class='btn btn-xs btn-default donate-yurist-link'><span class="glyphicon glyphicon-ruble"></span> Отблагодарить</a>-->
                            <?php endif;?>
                               
                            <?php if(Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->role == User::ROLE_ROOT):?>
                            <div class='donate-block'>
                                <?php $this->renderPartial("application.views.question._donateForm", array(
                                    'target'        =>  'Благодарность юристу ' . CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->lastName),
                                    'successUrl'    =>  Yii::app()->createUrl('question/view', array('id'=>$data->questionId, 'answer_payed_id'=>$data->id)),
                                ));?>
                            </div>
                            <?php endif;?> */ 
							?>
							
                        </div>
                    
                    <?php endif;?>
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
                                            <?php echo CHtml::encode($data->author->name . ' ' . $data->author->lastName);?>
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
                
                
                <?php if(!is_null($commentModel) && ($data->question->authorId == Yii::app()->user->id)):?>
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