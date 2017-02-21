<?php
/* @var $this MoneyController */
/* @var $model Money */

$this->setPageTitle("Запись в кассе #" . $model->id . '. ' . Yii::app()->name);


$this->breadcrumbs=array(
	'Moneys'=>array('index'),
	$model->id,
);

?>

<h1>Касса: запись #<?php echo $model->id; ?></h1>

<table class="table">
    <tr>
        <td>Дата</td>
        <td><?php echo CustomFuncs::niceDate($model->datetime, false, false);?></td>
    </tr>
    <tr>
        <td>Счет</td>
        <td><?php echo $model->getAccount();?></td>
    </tr>
    <tr>
        <td>Статья</td>
        <td><?php echo $model->getDirection();?></td>
    </tr>
    <tr>
        <td>Сумма</td>
        <td>
            <?php echo ($model->type == Money::TYPE_INCOME)?'+':'-';?>
            <?php echo $model->value;?> руб.
        </td>
    </tr>
    <tr>
        <td>Комментарий</td>
        <td><?php echo CHtml::encode($model->comment);?></td>
    </tr>
</table>

<?php echo CHtml::link("Редактировать", Yii::app()->createUrl('/admin/money/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>
