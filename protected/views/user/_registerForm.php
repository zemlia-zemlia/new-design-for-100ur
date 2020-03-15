<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile('/js/user.js');



?>

<div class="container-fluid">
<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'user-form',
    'enableAjaxValidation' => false,
        'htmlOptions' => [
            'class' => 'login-form',
            'enctype' => 'multipart/form-data',
            ],
]); ?>

    
<?php if (!$model->role):?>
    <div class="form-group">
        <p class="text-center vert-margin20">
        <strong>Выберите подходящий Вам тип аккаунта</strong><br />
        </p> 
        <div class="row vert-margin20">
            <div class="col-sm-6 text-center">
                <?php echo CHtml::link('Я клиент', Yii::app()->createUrl('user/create', ['role' => User::ROLE_CLIENT]), ['class' => 'yellow-button vert-margin20']); ?>
                <p>
                    Вам подойдет этот тип аккаунта, если Вы хотите задать вопрос юристу и получить консультацию.
                </p>
            
            </div>
            <div class="col-sm-6 text-center">
                <?php echo CHtml::link('Я юрист', Yii::app()->createUrl('user/create', ['role' => User::ROLE_JURIST]), ['class' => 'yellow-button vert-margin20']); ?>
                <p>
                    Если вы специалист в области права и хотите отвечать на вопросы пользователей.
                </p>
            
            </div>
        </div>
        
    </div>
<?php else:?>
    
    <?php echo $form->hiddenField($model, 'role'); ?>
    <?php
        switch ($model->role) {
            case User::ROLE_JURIST:
                $formView = '_registerFormJurist';
                break;
            case User::ROLE_BUYER:
                $formView = '_registerFormBuyer';
                break;
            default:
                $formView = '_registerFormClient';
        }
    ?>
    
    <?php echo $this->renderPartial($formView, ['form' => $form,  'model' => $model]); ?>
      
<div class="vert-margin20">
    <small class="text-muted">
      <label>
            <?php echo $form->checkBox($model, 'agree'); ?>
            Регистрируясь, вы соглашаетесь с условиями <?php echo CHtml::link('пользовательского соглашения', Yii::app()->createUrl('site/offer'), ['target' => '_blank']); ?>
        </label>
        <?php echo $form->error($model, 'agree'); ?>
    </small>
</div>

<div class="row">
    <div class="col-sm-12 text-center">
        <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Продолжить' : 'Сохранить', ['class' => 'yellow-button center-block']); ?>
        </div>
    </div>
</div> 
<?php endif; ?>  
    
<?php $this->endWidget(); ?>

</div><!-- form -->


</div>





