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

        <h4>Вход на сайт</h4>
        <div class="form-group">

            <?php echo $form->textField($model,'email', array('class'=>'form-control input-sm','placeholder'=>$model->getAttributeLabel('email'))); ?>

            <?php echo $form->error($model,'email'); ?>
        </div>



        <div class="form-group">

            <?php echo $form->passwordField($model,'password', array('class'=>'form-control input-sm','placeholder'=>$model->getAttributeLabel('password'))); ?>

            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->checkBox($model,'rememberMe'); ?>

            <?php echo $model->getAttributeLabel('rememberMe');?>

            <?php echo $form->error($model,'rememberMe'); ?>

        </div>

        <div class="row">
            <div class="col-md-6">
                <p><?php echo CHtml::submitButton('Войти',array('class'=>'btn btn-primary btn-xs btn-block')); ?></p>
            </div>
            <div class="col-md-6">
                <p><?php echo CHtml::link('Регистрация', Yii::app()->createUrl('user/create'), array('class'=>'btn btn-success btn-xs btn-block')); ?></p>
            </div>
        </div>
        



        <?php $this->endWidget(); ?>

<?php else:?>
    <p>    
        <span class="glyphicon glyphicon-user"></span> <?php echo CHtml::link(CHtml::encode(Yii::app()->user->name . ' ' . Yii::app()->user->lastName), Yii::app()->createUrl('/user'));?>
    
        <?php echo CHtml::link('<span class="glyphicon glyphicon-log-out"></span>', Yii::app()->createUrl('site/logout'), array());?>
    </p>
<?php endif; ?>
