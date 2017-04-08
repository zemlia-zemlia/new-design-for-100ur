<?php
/* @var $this QuestionCategoryController */
/* @var $data QuestionCategory */
?>
<?php //echo $index;?>
<?php //echo $itemsCount;?> 

<?php if($index == 0 || $index == floor($itemsCount/2)):?>
    <div class="col-md-6">
<?php endif;?>
<p>
    <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span>&nbsp; ' . CHtml::encode($data->name), array('alias', 'name'=>$data->alias)); ?>
</p>

<?php if($index == $itemsCount-1 || $index == floor($itemsCount/2)-1):?>
    </div>
<?php endif;?>