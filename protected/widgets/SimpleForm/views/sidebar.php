

<?php $form = $this->beginWidget('CActiveForm', [
    'htmlOptions' => ['class' => 'advice-form'],
    'enableAjaxValidation' => false,
    'action' => Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=sidebar&utm_campaign=' . Yii::app()->controller->id,
]); ?>


<form action="" method="" name="" class="advice-form">
    <h3 class="advice-form__title">Получите совет от юриста онлайн</h3>
    <div class="advice-form__textarea">
        <?php echo $form->textArea($model, 'questionText',
            ['class' => 'form-control', 'rows' => 6, 'placeholder' => 'Опишите вашу проблему...']); ?>
        <?php echo $form->error($model, 'questionText'); ?>

    </div>
    <div class="advice-form__input">
        <?php echo $form->textField($model, 'authorName', ['class' => 'form-control', 'placeholder' => 'Как вас зовут?']); ?>
        <?php echo $form->error($model, 'authorName'); ?>

    </div>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос онлайн' : 'Сохранить',
        ['class' => 'advice-form__btn main-btn', 'onclick' =>
            'yaCounter26550786.reachGoal("simple_form_submit"); return true;']); ?>

<?php $this->endWidget(); ?>