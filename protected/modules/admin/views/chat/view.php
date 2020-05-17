<?php
/* @var $this ChatController */
/* @var $model App\models\Chat */

$this->breadcrumbs = [
    'Чат' => ['index'],
    $model->id,
];

?>

<h1>Чат номер #<?php echo $model->id; ?></h1>

<p><a href="<?php echo Yii::app()->createUrl('/admin/user/view', ['id' => $model->user->id]); ?>"><?php echo $model->user->getShortName(); ?></a> ->
    <a href="<?php echo Yii::app()->createUrl('/admin/user/view', ['id' => $model->lawyer->id]); ?>"><?php echo $model->lawyer->getShortName(); ?></a></p>

статусы:<br>
<?php echo $model->is_confirmed ? 'Открыт' : 'Запрос'; ?> <br>
<?php echo $model->is_payed ? 'Оплачен' : 'Не оплачен'; ?> <br>
<?php echo $model->is_closed ? 'Закрыт' : ''; ?> <br>
<?php echo $model->is_petition ? 'Жалоба' : ''; ?> <br>


<p>Сообщения:</p>


<?php foreach ($model->messages as $message) : ?>

<p><?php echo $message->user->getShortName(); ?> -> <?php echo $message->message; ?></p>

<?php endforeach; ?>