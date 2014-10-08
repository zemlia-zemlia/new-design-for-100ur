<?php
/* @var $this QuestionCategoryController */
/* @var $data QuestionCategory */
?>

<li>
    <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
    <?php if(sizeof($data->children)):?>
        <ul>
            <?php foreach($data->children as $child):?>
                <li><?php echo CHtml::link(CHtml::encode($child->name), array('view', 'id'=>$child->id)); ?></li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>
</li>