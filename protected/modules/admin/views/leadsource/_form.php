<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */
/* @var $form CActiveForm */
?>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <div class="form">

                    <?php
                    $form = $this->beginWidget('CActiveForm', [
                        'id' => 'leadsource-form',
                        'enableAjaxValidation' => false,
                    ]);
                    ?>

                    <p class="note"><span class="required">*</span> - обязательные поля</p>

                    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>


                    <div class="form-group">
                        <?php echo $form->checkBox($model, 'active', []); ?>
                        <?php echo $model->getAttributeLabel('active'); ?>
                        <?php echo $form->error($model, 'active'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->checkBox($model, 'moderation', []); ?>
                        <?php echo $model->getAttributeLabel('moderation'); ?>
                        <?php echo $form->error($model, 'moderation'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->checkBox($model, 'priceByPartner', []); ?>
                        <?php echo $model->getAttributeLabel('priceByPartner'); ?>
                        <?php echo $form->error($model, 'priceByPartner'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'name'); ?>
                        <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'description'); ?>
                        <?php echo $form->textArea($model, 'description', ['class' => 'form-control', 'rows' => '3']); ?>
                        <?php echo $form->error($model, 'description'); ?>
                    </div>


                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'userId'); ?>
                        <?php echo $form->textField($model, 'userId', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'userId'); ?>

                        <?php if ($model->user): ?>
                            Текущий пользователь: <?php echo CHtml::link(CHtml::encode($model->user->name . ' ' . $model->user->lastName), Yii::app()->createUrl('admin/user/view', ['id' => $model->user->id])); ?>
                            (id: <?php echo $model->user->id; ?>)
                        <?php endif; ?>
                    </div>


                    <div class="form-group">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
                    </div>

                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            </div>
        </div>
    </div>
</div>