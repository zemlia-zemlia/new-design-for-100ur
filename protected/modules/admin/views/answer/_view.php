<?php
/* @var $this QuestionController */
/* @var $data Question */
?>

<?php
    switch ($data->status) {
        case Question::STATUS_NEW:
            $statusClass = '';
            break;
        case Question::STATUS_PUBLISHED:
            $statusClass = 'success';
            break;
        case Question::STATUS_SPAM:
            $statusClass = 'danger';
            break;
        default:
            $statusClass = '';
    }
?>

<tr class="<?php echo $statusClass; ?>" id="answer-<?php echo $data->id;?>">
    <td>        

        <p>
            <strong>Вопрос:</strong>
            <?php echo CHtml::encode(CustomFuncs::cutString($data->question->questionText, 1000));?>
        </p>
        <p>
            <strong>Ответ
                <?php if($data->isFast()):?>
                    <span class="text-success"><span class="glyphicon glyphicon-flash"></span> Быстрый</span>
                <?php endif;?>
                :</strong>
        <?php echo CHtml::encode($data->answerText); ?>
        </p>
        
        <small>
            <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
                <?php if ($data->datetime) {
    echo CustomFuncs::niceDate($data->datetime, false, false);
}?>
            &nbsp;
            <?php endif;?>
             
            <strong>ID вопроса:</strong> 
            <?php echo CHtml::link(CHtml::encode($data->questionId), Yii::app()->createUrl('/admin/question/view', array('id'=>$data->questionId))); ?>
            &nbsp;
            
            <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
            <?php echo CHtml::link(CHtml::encode($data->id), Yii::app()->createUrl('/admin/answer/view', array('id'=>$data->id))); ?>
            
            &nbsp;  
            <?php if ($data->author):?>
            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->author->lastName . ' ' . $data->author->name);?>
            <?php endif;?>

            &nbsp;

            <?php echo $data->getAnswerStatusName(); ?>
        </small>
    </td>
    
    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>  
    <td>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>   
                      
            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/answer/update', array('id'=>$data->id)), array('class'=>'btn btn-primary btn-xs btn-block')); ?>
            
            <?php if ($data->status!=Answer::STATUS_PUBLISHED):?>
            <?php echo CHtml::ajaxLink('Одобрить', Yii::app()->createUrl('/admin/answer/publish'), array('data'=>"id=".$data->id, 'type'=>'POST', 'success'=>'onPublishAnswer'), array('class'=>'btn btn-success btn-xs btn-block')); ?>
            <?php endif;?>

            <?php if ($data->status!=Answer::STATUS_PUBLISHED && !$data->transactionId):?>
                <?php echo CHtml::ajaxLink('Одобрить и оплатить', Yii::app()->createUrl('/admin/answer/payBonus'), array('data'=>"id=".$data->id, 'type'=>'POST', 'success'=>'onPayBonus'), array('class'=>'btn btn-success btn-xs btn-block')); ?>
            <?php endif;?>

            <?php if ($data->status!=Answer::STATUS_SPAM):?>   
            <?php echo CHtml::ajaxLink('В спам', Yii::app()->createUrl('/admin/answer/toSpam'), array('data'=>"id=".$data->id, 'type'=>'POST', 'success'=>'onSpamAnswer'), array('class'=>'btn btn-warning btn-xs btn-block')); ?>
            <?php endif;?>
        
            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                <?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/answer/delete', array('id'=>$data->id)), array('class'=>'btn btn-danger btn-xs btn-block')); ?>
            <?php endif;?>

        <?php if($data->transaction instanceof TransactionCampaign):?>
            Бонус <?php echo MoneyFormat::rubles($data->transaction->sum);?>
        <?php endif;?>
         
        <?php endif;?>
    </td> 
    
    <?php endif;?>    
</tr>
