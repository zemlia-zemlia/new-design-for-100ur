<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<?php $videoCode = $data->getVideoCode(); ?>
<?php if ($data->status != Answer::STATUS_SPAM && !is_null($data->author) && $data->author->role == User::ROLE_JURIST): ?>

    <div class='answer-item'>

        <div class="">
            <div class="row">
                <div class="col-sm-2 col-xs-4">
                    <?php if ($videoCode): ?>
                        <img src='/pics/2017/logo_white.png' class='img-responsive' alt='100 Юристов'/>
                    <?php else: ?>

                        <?php if ($data->author): ?>
                            <div class="answer-item-avatar vert-margin10">
                                <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $data->author->id]); ?>">
                                    <img src="<?php echo $data->author->getAvatarUrl(); ?>"
                                         alt="<?php echo CHtml::encode($data->author->name . ' ' . $data->author->lastName); ?>"
                                         class="img-responsive"/>
                                </a>
                            </div>
                            <?php if ($data->authorId != Yii::app()->user->id): ?>
                                <div class="center-align vert-margin10">
                                    <a href="<?php echo Yii::app()->createUrl('user/view', array('id' => $data->authorId)); ?>"
                                       rel="nofollow" class='btn btn-block btn-xs btn-default'>В профиль</a>
                                </div>
                            <?php endif; ?>

                            <?php if (floor((time() - strtotime($data->author->lastActivity)) / 60) < 60): ?>
                                <div class="center-align vert-margin10">
                                    <small><span class="label label-success">Сейчас на сайте</span></small>
                                </div>
                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>
                <div class="col-sm-10 col-xs-8">

                    <?php if ($videoCode): ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item"
                                    src="https://www.youtube.com/embed/<?php echo $videoCode; ?>"
                                    allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                    <div class="answer-item-author-block vert-margin20">
                        <div class="">

                            <div itemprop="author" class='answer-item-author' itemscope
                                 itemtype="http://schema.org/Person">

                                <span class="glyphicon glyphicon-user"></span>
                                <strong>
                            <span itemprop="name">
                                <a href="<?php echo Yii::app()->createUrl('user/view', array('id' => $data->authorId)); ?>"
                                   rel="nofollow">
                                    <?php echo CHtml::encode($data->author->name . " " . $data->author->name2 . " " . $data->author->lastName); ?>
                                </a>
                            </span>
                                </strong>

                                <?php if ($data->author->settings->isVerified): ?>
                                    <em class="text-muted"><?php echo $data->author->settings->getStatusName(); ?></em>
                                <?php endif; ?>

                                &nbsp;|&nbsp;

                                <span class="glyphicon glyphicon-signal"></span>&nbsp;<?php echo $data->author->answersCount . ' ' . CustomFuncs::numForms($data->author->answersCount, 'ответ', "ответа", "ответов"); ?>
                                &nbsp;|&nbsp;
                                <span class='glyphicon glyphicon-thumbs-up'></span>&nbsp;<?php echo $data->author->karma; ?>

                            </div>


                        </div>
                    </div>

                    <div class="hidden-xs">
                        <div itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="http://schema.org/Answer">

                            <div itemprop="text">
                                <p>
                                    <?php echo CHtml::encode($data->answerText); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 col-sm-10 col-sm-offset-2">
                    <div class="visible-xs-block">
                        <div itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                            <div itemprop="text">
                                <p>
                                    <?php echo CHtml::encode($data->answerText); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-xs-12  col-sm-offset-2">


                    <div class="vert-margin20 answer-karma-string">

                        <?php if ($data->authorId == Yii::app()->user->id && time() - strtotime($data->datetime) < Answer::EDIT_TIMEOUT): ?>
                            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('question/updateAnswer', array('id' => $data->id)), array('class' => 'btn btn-default btn-xs')); ?>
                        <?php endif; ?>

                        <?php if (!Yii::app()->user->isGuest && $data->authorId != Yii::app()->user->id): ?>
                            <?php
                            // проверим, не голосовал ли текущий пользователь за данный ответ
                            $showKarmaLink = true;
                            foreach ($data->karmaChanges as $karmaChange) {
                                if ($karmaChange->authorId == Yii::app()->user->id) {
                                    $showKarmaLink = false;
                                    break;
                                }
                            }
                            ?>

                            <?php if ($data->datetime): ?>
                                <span class="text-muted">
                                <?php echo CustomFuncs::niceDate($data->datetime, false); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($showKarmaLink === true): ?>
                                <span id="answer-karma-<?php echo $data->id; ?>">
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-thumbs-up'></span> Ответ полезен", Yii::app()->createUrl('user/karmaPlus'), array('class' => 'link-karma-plus btn-default btn btn-xs', 'data-id' => $data->id)); ?>
                            </span>
                            <?php endif; ?>

                            <?php echo CHtml::link('Оставьте отзыв о консультации', Yii::app()->createUrl('user/testimonial', ['id' => $data->authorId, 'questionId' => $data->questionId]), ['class' => 'btn btn-xs btn-default']); ?></a>

                        <?php endif; ?>
                    </div>


                    <?php endif; ?>

                    <?php foreach ($data->comments as $comment): ?>
                        <?php if ($comment->status != Comment::STATUS_SPAM): ?>

                            <?php $commentBlockClass = ($data->question->authorId == $comment->authorId) ? 'comment-author' : 'comment-yurist'; ?>

                            <div class="answer-comment <?php echo $commentBlockClass; ?>">
                                <p><strong><span class="glyphicon glyphicon-comment"></span>
                                        <?php if ($data->question->authorId == $comment->authorId) {
                                            echo CHtml::encode($data->question->authorName);
                                        } elseif ($data->authorId == $comment->authorId) {
                                            ?>
                                            <?php echo CHtml::encode($data->author->getShortName()); ?>
                                            <?php
                                        }
                                        ?>
                                    </strong>
                                </p>
                                <p>
                                    <?php echo CHtml::encode($comment->text); ?>
                                </p>
                                <?php if (!is_null($commentModel) && $comment->authorId != Yii::app()->user->id && ($data->authorId == Yii::app()->user->id || $data->question->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_ROOT))): ?>
                                    <div class="right-align">
                                        <a class="btn btn-xs btn-default" role="button" data-toggle="collapse"
                                           href="#collapse-comment-<?php echo $comment->id; ?>" aria-expanded="false">
                                            Ответить
                                        </a>
                                    </div>
                                    <div class="collapse child-comment-container"
                                         id="collapse-comment-<?php echo $comment->id; ?>">
                                        <strong>Ваш ответ:</strong>
                                        <?php
                                        $this->renderPartial('application.views.comment._form', array(
                                            'type' => Comment::TYPE_ANSWER,
                                            'objectId' => $data->id,
                                            'model' => $commentModel,
                                            'hideRating' => true,
                                            'parentId' => $comment->id,
                                        ));
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>


                    <?php if (!is_null($commentModel) && ($data->question->authorId == Yii::app()->user->id)): ?>
                        <strong>Ваш комментарий:</strong>
                        <?php
                        $this->renderPartial('application.views.comment._form', array(
                            'type' => Comment::TYPE_ANSWER,
                            'objectId' => $data->id,
                            'model' => $commentModel,
                            'hideRating' => true,
                            'parentId' => 0,
                        ));

                        ?>
                    <?php endif; ?>

                    <?php if (Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->role == User::ROLE_ROOT): ?>
                        <div class='donate-block'>
                            <h3>Поблагодарите
                                юриста <?php echo CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->lastName); ?>
                                за консультацию</h3>
                            <?php $this->renderPartial("application.views.question._donateForm", array(
                                'target' => 'Благодарность юристу ' . CHtml::encode($data->author->name) . " " . CHtml::encode($data->author->lastName),
                                'successUrl' => Yii::app()->createUrl('question/view', array('id' => $data->questionId, 'answer_payed_id' => $data->id)),
                                'answer' => $data,
                            )); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>


    </div>
<?php endif; ?>