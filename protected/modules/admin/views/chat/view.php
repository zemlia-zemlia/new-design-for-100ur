<?php
/* @var $this ChatController */
/* @var $model App\models\Chat */

$this->breadcrumbs = [
	'Чат'=>['index'],
	$model->id,
];


?>

<h1>Чат номер #<?php echo $model->id; ?></h1>

<p><a href="<?= Yii::app()->createUrl('/admin/user/view', ['id' => $model->user->id]) ?>"><?= $model->user->getShortName()?></a> ->
    <a href="<?= Yii::app()->createUrl('/admin/user/view', ['id' => $model->lawyer->id]) ?>"><?= $model->lawyer->getShortName()?></a></p>

статусы:<br>
<?= $model->is_confirmed ? 'Открыт' : 'Запрос' ?> <br>
<?= $model->is_payed ? 'Оплачен' : 'Не оплачен' ?> <br>
<?= $model->is_closed ? 'Закрыт' : '' ?> <br>
<?= $model->is_petition ? 'Жалоба' : '' ?> <br>


<p>Сообщения:</p>


<?php foreach ($model->messages as $message) : ?>

<p><?= $message->user->getShortName() ?> -> <?= $message->message ?></p>

<?php endforeach; ?>