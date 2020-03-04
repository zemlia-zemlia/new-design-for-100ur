<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'order-form',
    'enableAjaxValidation' => false,
        'htmlOptions' => ['class' => 'form-horizontal'],
]); ?>

<div class="form-group">
    <label class="col-sm-4 control-label">Срок, дней</label>
    <div class="col-sm-2">
        <?php echo $form->textField($order, 'termDays', ['class' => 'form-control', 'style' => 'text-align:right']); ?>
        <?php echo $form->error($order, 'termDays'); ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Стоимость работы, руб.</label>
    <div class="col-sm-2">
            <?php echo $form->textField($order, 'price', ['class' => 'form-control', 'style' => 'text-align:right']); ?>
        <?php echo $form->error($order, 'price'); ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-3 col-sm-offset-4">
        <p>
            <?php echo CHtml::submitButton('Отправить заказ юристу', ['class' => 'yellow-button center-block']); ?>
        </p>
        <p>
            или <?php echo CHtml::link('вернитесь к заказу', Yii::app()->createUrl('order/view', ['id' => $order->id])); ?>
        </p>
    </div>
</div>

<?php $this->endWidget(); ?>