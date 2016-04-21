<?php
/* @var $this QuestionController */
/* @var $data Question */
?>



<div class="panel"> 
    <div class="panel-body">
        <?php if($data->title):?>
            <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', array('id'=>$data->id))); ?></h4>
        <?php endif;?>
		
       
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($data->questionText),0,240,'utf-8'));?>
            <?php if(strlen(CHtml::encode($data->questionText))>240) echo "..."; ?>
        </p>
        
		
        <hr/>
<small>
        <p>
        <?php if(!is_null($data->publishDate)) echo CustomFuncs::invertDate($data->publishDate);?>&nbsp;&nbsp;    
            
        <?php if($data->authorName):?>
        <img src='/pics/2015/icon_user.png' alt='' />&nbsp;<?php echo CHtml::encode($data->authorName);?>
        &nbsp;&nbsp;
        <? endif;?>
        
        <?php if($data->town):?>
            <img src='/pics/2015/icon_marker.png' alt='' />&nbsp;<?php echo CHtml::link(CHtml::encode($data->town->name),Yii::app()->createUrl('town/alias',array('name'=>$data->town->alias)), array('title'=>'Все вопросы юристам в городе ' . CHtml::encode($data->town->name)));?>
            &nbsp;&nbsp;
        <?php endif;?>
            
        <?php if(!$hideCategory && $data->categories):?>
            <?php foreach($data->categories as $category):?>
            <img src='/pics/2015/icon_folder.png' alt='' />&nbsp;&nbsp;<?php echo CHtml::link($category->name, Yii::app()->createUrl('questionCategory/alias',array('name'=>CHtml::encode($category->alias))));?>
            &nbsp;&nbsp;
            <?php endforeach;?>
        <?php endif;?>    
        
        <?php if($answersCount = $data->answersCount):?>
            <img src='/pics/2015/icon_comment.png' alt='' />&nbsp;<?php echo $answersCount . "&nbsp;" .  CustomFuncs::numForms($answersCount, 'ответ', 'ответа', 'ответов');?>
        <?php endif;?>
        </p>
</small>
    </div>    
</div>