<?php

use App\models\Order;
use App\models\User;

$this->setPageTitle('Редактирование заказа документа #' . $order->id . '. ' . Yii::app()->name);

    $jurist = $order->jurist;

    $this->breadcrumbs = [];
    if (User::ROLE_CLIENT == Yii::app()->user->role) {
        $this->breadcrumbs['Личный кабинет'] = ['/user/'];
    } else {
        $this->breadcrumbs['Заказы документов'] = ['/order/'];
    }
    $this->breadcrumbs['Заказ документа ' . $order->id] = ['/order/view', 'id' => $order->id];
    $this->breadcrumbs[] = 'Редактирование';

    $this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Уточните параметры заказа</h1>

<p>
    <?php echo $order->docType->getClassName(); ?>.
    <?php echo $order->docType->name; ?>
</p>
<p>
    <?php echo CHtml::encode($order->description); ?>
</p>

<?php if ($jurist):?>
<p>
    <strong>Выбран юрист:</strong>
    <br /> 
    <?php echo CHtml::link(CHtml::encode(trim($jurist->name . ' ' . $jurist->name2 . ' ' . $jurist->lastName)), Yii::app()->createUrl('user/view', ['id' => $jurist->id])); ?>
    <?php if (Order::STATUS_JURIST_SELECTED == $order->status && User::ROLE_CLIENT == Yii::app()->user->role):?>
        <?php echo CHtml::link('отменить', Yii::app()->createUrl('order/cancel', ['id' => $order->id]), ['class' => 'btn btn-default btn-xs']); ?>
    <?php endif; ?>
</p>
<?php endif; ?>

<hr />

<?php echo $this->renderPartial('_formShort', [
    'order' => $order,
]); ?>