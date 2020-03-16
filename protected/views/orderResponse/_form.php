<?php
/* @var $this CommentController */

use App\models\Comment;

/* @var $model Comment */
/* @var $form CActiveForm */

$model->price = MoneyFormat::rubles($model->price);
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'comment-form',
    'enableAjaxValidation' => false,
]); ?>

    <?php if (Yii::app()->user->isVerified):?>
    <div class="alert alert-success">
        <p>
            Для того, чтобы предложить свои услуги по данному заказу, заполните форму ниже.<br />
            В случае выбора Вас исполнителем по данному заказу, Вам придет уведомление.
        </p>
    </div>
    
	
    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'text'); ?>
		<?php echo $form->textArea($model, 'text', ['rows' => 4, 'class' => 'form-control']); ?>
		<?php echo $form->error($model, 'text'); ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                    <?php echo $form->labelEx($model, 'price'); ?>
                    <?php echo $form->textField($model, 'price', ['class' => 'form-control right-align']); ?>
                    <?php echo $form->error($model, 'price'); ?>
            </div>

            <?php echo $form->hiddenField($model, 'type', ['value' => $type]); ?>
            <?php echo $form->hiddenField($model, 'objectId', ['value' => $objectId]); ?>
            <?php echo $form->hiddenField($model, 'parentId', ['value' => $parentId]); ?>

            <div class="form-group">
                    <?php echo CHtml::submitButton('Предложить услуги', ['class' => 'yellow-button center-block']); ?>
            </div>
        </div>
    </div>
    <?php else:?>
    <div class="alert alert-warning">
        <p>
            Для того, чтобы предложить свои услуги по данному заказу, Вы должны <?php echo CHtml::link('подтвердить свою квалификацию', Yii::app()->createUrl('/user/')); ?>.
        </p>
    </div>
    <?php endif; ?>
    
        

<?php $this->endWidget(); ?>

</div><!-- form -->