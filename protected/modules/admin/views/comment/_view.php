<?php
/* @var $this QuestionController */
/* @var $data Question */
?>

<?php
    switch ($data->status) {
        case Comment::STATUS_NEW:
            $statusClass = '';
            break;
        case Comment::STATUS_CHECKED:
            $statusClass = 'success';
            break;
        case Comment::STATUS_SPAM:
            $statusClass = 'danger';
            break;
        default:
            $statusClass = '';
    }
?>

<tr class="<?php echo $statusClass; ?>" id="comment-<?php echo $data->id; ?>">
    <td>        
    
        <p>
        <?php echo CHtml::encode($data->text); ?>
        </p>

         <?php
            switch ($data->type) {
                case Comment::TYPE_ANSWER:
                    echo 'Ответ на вопрос ';
                    $answer = Answer::model()->with('question')->findByPk($data->objectId);
                    echo CHtml::link(CHtml::encode($answer->question->title), Yii::app()->createUrl('question/view', ['id' => $answer->questionId]), ['target' => '_blank']);
                    break;
            }
        ?> 
        <br/>
        <small>
            <?php if ($data->dateTime) {
            echo DateHelper::niceDate($data->dateTime, false, false);
        }?>
            &nbsp;
            <?php if ($data->author):?>
            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->author->lastName . ' ' . $data->author->name); ?>
            <?php elseif ($data->authorName):?>
                <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->authorName); ?>
            <?php endif; ?>

        </small>
        
        <p><strong>Оценка:</strong> 
            <?php echo (int) $data->rating; ?>/5
        </p>
    </td>

    <td>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>   
                      
            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/comment/update', ['id' => $data->id]), ['class' => 'btn btn-primary btn-xs btn-block']); ?>
            
            <?php if (Comment::STATUS_CHECKED != $data->status):?>
            <?php echo CHtml::ajaxLink('Одобрить', Yii::app()->createUrl('/admin/comment/publish'), ['data' => 'id=' . $data->id, 'type' => 'POST', 'success' => 'onPublishComment'], ['class' => 'btn btn-success btn-xs btn-block']); ?>
            <?php endif; ?>
            
            <?php if (Comment::STATUS_SPAM != $data->status):?>   
            <?php echo CHtml::ajaxLink('В спам', Yii::app()->createUrl('/admin/comment/toSpam'), ['data' => 'id=' . $data->id, 'type' => 'POST', 'success' => 'onSpamComment'], ['class' => 'btn btn-warning btn-xs btn-block']); ?>
            <?php endif; ?>
        
            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                <?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/comment/delete', ['id' => $data->id]), ['class' => 'btn btn-danger btn-xs btn-block']); ?>
            <?php endif; ?>
         
        <?php endif; ?>
    </td> 
    
</tr>
