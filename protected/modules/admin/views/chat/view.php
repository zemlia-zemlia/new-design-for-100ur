<?php
/* @var $this ChatController */
/* @var $model App\models\Chat */

$this->breadcrumbs = [
    'Чаты' => ['index'],
    $model->id,
];

$this->pageTitle = 'Чаты';
Yii::app()->clientScript->registerScriptFile('/js/admin/region.js');
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

?>

<h1 class="vert-margin40">Чат номер #<?php echo $model->id; ?></h1>

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-body">
                        <h4 class="vert-margin40">Участники чата:</h4>
                        <p>
                            <a href="<?= Yii::app()->createUrl('/admin/user/view', ['id' => $model->user->id]) ?>"><?= $model->user->getShortName() ?> </a> (<?= $model->user->getRoleName() ?>)
                        </p>
                        <p>
                            <a href="<?= Yii::app()->createUrl('/admin/user/view', ['id' => $model->lawyer->id]) ?>"><?= $model->lawyer->getShortName() ?>  </a> (<?= $model->lawyer->getRoleName() ?>)
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-body">
                        <h4 class="vert-margin20">Текущее состояние чата:</h4>
                        <?= $model->is_confirmed ? 'Открыт' : 'Отправлен запрос' ?> <br>
                        <?= $model->is_payed ? 'Оплачен' : 'Не оплачен' ?> <br>
                        <?= $model->is_closed ? 'Закрыт' : '' ?> <br>
                        <?= $model->is_petition ? 'Жалоба' : '' ?> <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Сообщения чата:</h4>
                <?php foreach ($model->messages as $message) : ?>
                    <p><?= date('d.m.y H:i', $message->created) ?> <?= $message->user->getShortName() ?> -> <?= $message->message ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Управление</h4>
                Тут будут кнопки управления чатами для админов
            </div>
        </div>
    </div>
</div>
