<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->breadcrumbs=array(
	'Источники'=>array('index'),
	CHtml::encode($model->name),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Источник лидов <?php echo CHtml::encode($model->name); ?></h1>

<table class="table table-bordered">
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('id');?>
        </td>
        <td>
            <?php echo $model->id;?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('name');?>
        </td>
        <td>
            <?php echo CHtml::encode($model->name);?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('description');?>
        </td>
        <td>
            <?php echo CHtml::encode($model->description);?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('appId');?>
        </td>
        <td>
            <?php echo $model->appId;?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('secretKey');?>
        </td>
        <td>
            <?php echo $model->secretKey;?>
        </td>
    </tr>
</table>