<?php
/* @var $this TownController */

use App\models\Town;

/* @var $data Town */
?>

<div class="view">

    <strong><?php echo CHtml::encode($data->name); ?></strong>
    <?php echo CHtml::encode($data->region->name); ?>
    
    <span class="glyphicon glyphicon-question-sign"></span> <?php echo CHtml::encode($data->questionsCount); ?>

</div>