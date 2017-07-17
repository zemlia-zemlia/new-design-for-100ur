<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile('/js/user.js');

?>
<div class="container-fluid">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'class'     =>  'login-form',
            'enctype'   =>  'multipart/form-data',
            ),
)); ?>


	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
        <?php echo $form->errorSummary($yuristSettings, "Исправьте ошибки"); ?>

    
<?php if(!$model->role):?>
    <div class="form-group">
        <p class="text-center vert-margin20">
        <strong>Выберите подходящий Вам тип аккаунта</strong><br />
        </p> 
        <div class="row vert-margin20">
            <div class="col-sm-6 text-center">
                <?php echo CHtml::link("Я клиент", Yii::app()->createUrl('user/create', array('role' => User::ROLE_CLIENT)), array('class' => 'btn btn-primary btn-lg vert-margin20'));?>
                <p>
                    Вам подойдет этот тип аккаунта, если Вы хотите задать вопрос юристу и получить консультацию.
                </p>
            
            </div>
            <div class="col-sm-6 text-center">
                <?php echo CHtml::link("Я юрист", Yii::app()->createUrl('user/create', array('role' => User::ROLE_JURIST)), array('class' => 'btn btn-primary btn-lg vert-margin20'));?>
                <p>
                    Если вы специалист в области права и хотите отвечать на вопросы пользователей.
                </p>
            
            </div>
        </div>
        
    </div>
<?php else:?>
    
    <?php echo $form->hiddenField($model,'role'); ?>
    <?php
        $formView = ($model->role == User::ROLE_JURIST) ? "_registerFormJurist" : "_registerFormClient";
    ?>
    
    <?php echo $this->renderPartial($formView, array('form' => $form,  'model' => $model));?>
      
<div class="vert-margin20">
    <small class="text-muted">
      <label>
          <input type="checkbox" value="1" checked="checked">
        Регистрируясь, вы соглашаетесь с условиями <?php echo CHtml::link('пользовательского соглашения', Yii::app()->createUrl('site/offer'), array('target'=>'_blank'));?>
      </label>
    </small>
</div>

<div class="row">
    <div class="col-sm-12 text-center">
        <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Продолжить' : 'Сохранить', array('class'=>'btn btn-success btn-lg')); ?>
        </div>
    </div>
</div> 
<?php endif;?>  
    
<?php $this->endWidget(); ?>

</div><!-- form -->
</div>