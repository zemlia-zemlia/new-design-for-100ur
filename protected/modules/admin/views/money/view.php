<?php
/* @var $this MoneyController */

use App\helpers\DateHelper;
use App\models\Money;

/* @var $model Money */

$this->setPageTitle('Запись в кассе #' . $model->id . '. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Moneys' => ['index'],
    $model->id,
];

?>

<h1 class="vert-margin30">Касса: запись #<?php echo $model->id; ?></h1>
<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <table class="table">
                    <tr>
                        <td>Дата</td>
                        <td><?php echo DateHelper::niceDate($model->datetime, false, false); ?></td>
                    </tr>
                    <tr>
                        <td>Счет</td>
                        <td><?php echo $model->getAccount(); ?></td>
                    </tr>
                    <tr>
                        <td>Статья</td>
                        <td><?php echo $model->getDirection(); ?></td>
                    </tr>
                    <tr>
                        <td>Сумма</td>
                        <td>
                            <?php echo (Money::TYPE_INCOME == $model->type) ? '+' : '-'; ?>
                            <?php echo MoneyFormat::rubles($model->value); ?> руб.
                        </td>
                    </tr>
                    <tr>
                        <td>Комментарий</td>
                        <td><?php echo CHtml::encode($model->comment); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/money/update', ['id' => $model->id]), ['class' => 'btn btn-warning btn-block vert-margin20']); ?>
        <?php echo CHtml::link('Добавить еще запись', Yii::app()->createUrl('/admin/money/create'), ['class' => 'btn btn-block btn-primary']); ?>

    </div>
</div>
