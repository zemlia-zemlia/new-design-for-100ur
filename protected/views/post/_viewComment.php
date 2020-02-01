<div class="post-comment">
    <?php if ($data->status != Comment::STATUS_SPAM): ?>
        <div class="answer-comment" style="margin-left:<?php echo($data->level - 1) * 20; ?>px;">
            <p> <strong><span class="glyphicon glyphicon-comment"></span> 
 
                <?php echo CHtml::encode($data->author->name . ' ' . $data->author->name2 . ' ' . $data->author->lastName); ?>
     
                </strong>
            </p>
            <p>
            <?php echo CHtml::encode($data->text); ?>
            </p>
    <?php if (!is_null($commentModel) && $data->authorId != Yii::app()->user->id || (Yii::app()->user->checkAccess(User::ROLE_ROOT))): ?>
                <div class="right-align">
                    <small>
                    <a class="" role="button" data-toggle="collapse" href="#collapse-comment-<?php echo $data->id; ?>" aria-expanded="false">
                        Ответить
                    </a>
                    </small>
                </div>    
                <div class="collapse child-comment-container" id="collapse-comment-<?php echo $data->id; ?>">
                    <strong>Ваш ответ:</strong>
                    <?php
                    $this->renderPartial('application.views.comment._form', array(
                        'type' => Comment::TYPE_POST,
                        'objectId' => $data->objectId,
                        'model' => $commentModel,
                        'hideRating' => true,
                        'parentId' => $data->id,
                    ));
                    ?>
                </div>
        <?php endif; ?>
        </div>
<?php endif; ?>
</div>