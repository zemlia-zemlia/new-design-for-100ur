<?php
    $this->setPageTitle("Отбраковка лида ". Yii::app()->name);
?>

<h1>Отбраковка лида</h1>

<table class="table table-bordered">
    
    <tr>
        <td><strong><?php echo $lead->getAttributeLabel('name'); ?></strong></td>
        <td>
            <?php echo CHtml::encode($lead->name); ?>
        </td>
    </tr>
    
    <tr>
        <td><strong><?php echo $lead->getAttributeLabel('phone'); ?></strong></td>
        <td>
            <?php echo $lead->phone; ?>
        </td>
    </tr>
    
    
    <tr>
        <td><strong><?php echo $lead->getAttributeLabel('town'); ?></strong></td>
        <td><?php echo $lead->town->name; ?></td>
    </tr>
    

    <tr>
        <td><strong><?php echo $lead->getAttributeLabel('deliveryTime'); ?></strong></td>
        <td><?php echo CustomFuncs::niceDate($lead->deliveryTime); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo $lead->getAttributeLabel('question'); ?></strong></td>
        <td><?php echo nl2br(CHtml::encode($lead->question)); ?></td>
    </tr>
</table>



<?php $this->renderPartial('_brakLeadForm', array(
    'lead'  =>  $lead,
));?>