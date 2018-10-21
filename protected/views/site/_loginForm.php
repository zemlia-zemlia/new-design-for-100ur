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
<div class="container-fluid">
<div class="row">
    <?php if(!isset($hideForgetPassword) || !$hideForgetPassword):?>
    <div class="col-sm-7">
        <?php else:?>
    <div class="col-sm-12">    
        <?php endif;?>
        
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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->checkBox($model,'rememberMe'); ?>

                    <?php echo $model->getAttributeLabel('rememberMe');?>

                    <?php echo $form->error($model,'rememberMe'); ?>

                </div>
            </div>
            <div class="col-md-6">
                <?php echo CHtml::submitButton('Войти',array('class'=>'yellow-button btn-lg btn-block')); ?>
            </div>
        </div>
        

        
    </div>
    <?php if(!isset($hideForgetPassword) || !$hideForgetPassword):?>
	<div class="col-sm-1 center-align">
	</div>
    <div class="col-sm-4 center-align">
        <br />
		<p>Если забыли пароль<br />
        <?php echo CHtml::link('Восстановить', Yii::app()->createUrl('user/restorePassword'), array('class'=>'btn btn-default btn-block'));?>
        </p>
        <p>Если Вы у нас впервые<br />
        <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('user/create'), array('class'=>'btn btn-default btn-block'));?>
        </p>

    </div>
    <?php endif;?>
</div>
</div>

    


<?php $this->endWidget(); ?>
