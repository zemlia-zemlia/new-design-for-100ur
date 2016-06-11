<?php
/* @var $this TownController */
/* @var $data Town */
?>

<div class="view">

    <strong><?php echo CHtml::encode($data->name);?></strong>
    <?php echo CHtml::encode($data->ocrug);?>
    
    <span class="glyphicon glyphicon-question-sign"></span> <?php echo CHtml::encode($data->questionsCount);?>

</div>