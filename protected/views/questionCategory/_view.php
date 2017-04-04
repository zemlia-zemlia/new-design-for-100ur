<?php
/* @var $this QuestionCategoryController */
/* @var $data QuestionCategory */
?>

<li>
    <?php echo CHtml::link(CHtml::encode($data->name), array('alias', 'name'=>$data->alias)); ?>
    <?php if(/*sizeof($data->children)*/ false):?>
        <ul>
            <?php foreach($data->children as $child):?>
                <li><?php echo CHtml::link(CHtml::encode($child->name), array('questionCategory/view', 'id'=>$child->id)); ?></li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>
</li>