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

<tr class="<?php echo $statusClass; ?>">
    <td>    
        <?php if($data->title):?>
            <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', array('id'=>$data->id))); ?></h4>
        <?php endif;?>
        
        <p>
        <?php echo CHtml::encode($data->questionText); ?>
        </p>
        
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), Yii::app()->createUrl('question/view', array('id'=>$data->id))); ?><br />
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('question/update', array('id'=>$data->id)), array('class'=>'btn btn-primary btn-xs')); ?>
        <?php echo CHtml::link('Удалить', Yii::app()->createUrl('question/delete', array('id'=>$data->id)), array('class'=>'btn btn-danger btn-xs')); ?>
    </td>
    <td>
        <?php if(!$hideCategory):?>
            <?php echo CHtml::link($data->category->name, Yii::app()->createUrl('questionCategory/view',array('id'=>$data->category->id)));?>
        
        <?php endif;?>
        <br />
    </td>
    <td>
        <?php if($data->authorName):?>
        <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->authorName);?><br />
        <? endif;?>
        
        <?php if($data->town):?>
            <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::encode($data->town->name);?>
        <?php endif;?>
    </td> 
    <td>
        <?php echo $data->getQuestionStatusName(); ?><br />
        <?php echo CHtml::link('Изменить', Yii::app()->createUrl('question/update', array('id'=>$data->id))); ?>
    </td>
        
</tr>