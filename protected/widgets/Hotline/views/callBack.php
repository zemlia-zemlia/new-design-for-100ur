<div class="hotline-wrapper">
    <div>
        <strong>Горячая линия</strong> - юрист позвонит Вам
    </div>

    <?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'lead-search-form',
        'method' => 'POST',
        'action' => Yii::app()->createUrl('question/callback'),
        'htmlOptions' => ['class' => 'form-inline'],
    'enableAjaxValidation' => false,
    ]); ?>
    
    <div class="form-group">
        <?php echo $form->labelEx($model, 'town'); ?>
        <?php echo CHtml::textField('town', $townName, [
                        'id' => 'town-selector',
                        'class' => 'form-control input-sm',
        ]); ?>
        <?php
            echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
        ?>
    </div>
    
    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton('Заказать звонок', ['class' => 'btn btn-default input-sm']); ?>
    </div>

<?php $this->endWidget(); ?>
    
</div>