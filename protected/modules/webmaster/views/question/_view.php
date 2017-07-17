<?php
/* @var $this QuestionController */
/* @var $data Question */
?>

<?php
    switch ($data->status){
        case Question::STATUS_NEW:
            $statusClass = '';
            break;
        case Question::STATUS_MODERATED:
            $statusClass = 'info';
            break;
        case Question::STATUS_PUBLISHED:
            $statusClass = 'success';
            break;
        case Question::STATUS_SPAM:
            $statusClass = 'danger';
            break;
        default :
            $statusClass = '';
    }
?>

<tr class="<?php echo $statusClass; ?>" id="question-<?php echo $data->id;?>">
    <td>        
        <?php if($data->title):?>
            <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('/webmaster/question/view', array('id'=>$data->id))); ?></h4>
        <?php endif;?>
        
        <p>
        <?php echo CHtml::encode($data->questionText); ?>
        </p>
        
        <small>
                <?php if($data->createDate) {echo CustomFuncs::niceDate($data->createDate, false, false);}?>
            &nbsp;
             
            <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
            <?php echo CHtml::link(CHtml::encode($data->id), Yii::app()->createUrl('/webmaster/question/view', array('id'=>$data->id))); ?>
            &nbsp;
            <?php if($data->town):?>
                <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::encode($data->town->name . ' (' . $data->town->region->name . ')');?>
            <?php endif;?>
            &nbsp; 
            
            <?php if($data->authorName):?>
            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->authorName);?>
            <? endif;?>

            &nbsp;
            <?php echo $data->getQuestionStatusName(); ?>
            &nbsp;
            <?php if(in_array($data->status, array(Question::STATUS_CHECK, Question::STATUS_PUBLISHED))):?>
                <strong>Ваш заработок: </strong> <?php echo $data->buyPrice;?> руб.
            <?php endif;?>
        </small>
    </td>         
</tr>

<?php //endif;?> 