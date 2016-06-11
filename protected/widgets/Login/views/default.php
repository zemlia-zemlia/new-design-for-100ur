<?php if(Yii::app()->user->isGuest):?>


        <?php $form=$this->beginWidget('CActiveForm', array(

                'id'=>'login-form-widget',
                'action' => Yii::app()->createUrl('site/login'),
                'enableAjaxValidation'=>false,
                'htmlOptions'   =>  array(
                    'class' =>  '',
                ),

        )); ?>

        <?
            if(!isset($model)) $model=new LoginForm;
        ?>

        <h3>Вход на сайт</h3>
        <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>

            <?php echo $form->textField($model,'email', array('class'=>'form-control input-sm','placeholder'=>$model->getAttributeLabel('email'))); ?>

            <?php echo $form->error($model,'email'); ?>
        </div>



        <div class="form-group">
            <?php echo $form->labelEx($model,'password'); ?>

            <?php echo $form->passwordField($model,'password', array('class'=>'form-control input-sm')); ?>

            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->checkBox($model,'rememberMe'); ?>

            <?php echo $model->getAttributeLabel('rememberMe');?>

            <?php echo $form->error($model,'rememberMe'); ?>

        </div>

           <?php echo CHtml::submitButton('Войти',array('class'=>'btn btn-primary btn-xs')); ?>



        <?php $this->endWidget(); ?>

<?php else:?>
    <p>    
        <?php echo 'Вы вошли как ' . Yii::app()->user->name . ' ' . Yii::app()->user->lastName; ?>    
    </p>
    <?php echo CHtml::link('Выйти', Yii::app()->createUrl('site/logout'), array('class'=>'btn btn-primary btn-xs'));?>
<?php endif; ?>
