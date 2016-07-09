<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'login-form',
        'action' => Yii::app()->createUrl('site/login'),
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'class' =>  '',
        ),

)); ?>

<?
    if(!isset($model)) $model=new LoginForm;
?>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>

            <?php echo $form->textField($model,'email', array('class'=>'form-control input-lg','placeholder'=>$model->getAttributeLabel('email'))); ?>

            <?php echo $form->error($model,'email'); ?>
        </div>



        <div class="form-group">
            <?php echo $form->labelEx($model,'password'); ?>

            <?php echo $form->passwordField($model,'password', array('class'=>'form-control input-lg')); ?>

            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->checkBox($model,'rememberMe'); ?>

            <?php echo $model->getAttributeLabel('rememberMe');?>

            <?php echo $form->error($model,'rememberMe'); ?>

        </div>

           <?php echo CHtml::submitButton('Войти',array('class'=>'btn btn-success btn-lg btn-block')); ?>
    </div>
    <div class="col-sm-4 center-align">
        <p>Забыли пароль?<br />
        <?php echo CHtml::link('Восстановить пароль', Yii::app()->createUrl('user/restorePassword'), array('class'=>'btn btn-primary btn-block'));?>
        </p>
        
        <p>Если Вы у нас впервые<br />
        <?php echo CHtml::link('Регистрация', Yii::app()->createUrl('user/create'), array('class'=>'btn btn-primary btn-block'));?>
        </p>
    </div>
</div>


    


<?php $this->endWidget(); ?>
