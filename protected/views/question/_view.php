<?php
/* @var $this QuestionController */
/* @var $data Question */
?>



<div class="vert-margin30">
    <td>    
        <?php if($data->title):?>
            <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', array('id'=>$data->id))); ?></h4>
        <?php endif;?>
        
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($data->questionText),0,240,'utf-8'));?>
            <?php if(strlen(CHtml::encode($data->questionText))>240) echo "..."; ?>
        </p>
        
        

        <p>
        <?php if($data->authorName):?>
        <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->authorName);?>
        &nbsp;&nbsp;
        <? endif;?>
        
        <?php if($data->town):?>
            <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::encode($data->town->name);?>
            &nbsp;&nbsp;
        <?php endif;?>
            
        <?php if(!$hideCategory && $data->category):?>
            <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link($data->category->name, Yii::app()->createUrl('questionCategory/view',array('id'=>$data->category->id)));?>
            &nbsp;&nbsp;
        <?php endif;?>    
        
        <?php if($answersCount = sizeof($data->answers)):?>
            <span class="glyphicon glyphicon-comment"></span>&nbsp;<?php echo $answersCount . "&nbsp;" .  CustomFuncs::numForms($answersCount, 'ответ', 'ответа', 'ответов');?>
        <?php endif;?>
        </p>
</div>