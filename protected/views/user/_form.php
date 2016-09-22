<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScriptFile('/js/user.js');

?>

<style>
    .yurist-fields {
        display:block;
    }
</style>

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

<?php if($model->isNewRecord):?>    
    <div class="form-group radio-labels">
            <strong>Выберите подходящий Вам тип аккаунта</strong><br />
            <?php // echo $form->radioButtonList($model,'role', $rolesNames, array('class'=>'form-control'));?>
            <div class="alert alert-info">
                <?php echo $form->radioButton($model,'role', array('class'=>'form-control', 'value'=>User::ROLE_CLIENT, 'id'=>'role_client'));?>
                <label for="role_client"><strong>Клиент.</strong> Вам подойдет этот тип аккаунта, если Вы хотите задать вопрос юристу или получить юридическую помощь</label><br />
            </div>
            <div class="alert alert-info">
                <?php echo $form->radioButton($model,'role', array('class'=>'form-control', 'value'=>User::ROLE_JURIST, 'id'=>'role_yurist'));?>
                <label for="role_yurist"><strong>Юрист.</strong> Для юристов и специалистов в области права.</label>
            </div>
            <?php echo $form->error($model,'role'); ?>
    </div>
<?php endif;?>
   
    
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Личная информация</a></li>
    <li role="presentation"><a href="#contacts" aria-controls="contacts" role="tab" data-toggle="tab">Контакты</a></li>
    <li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab">Пароль</a></li>
    <?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>
    <li role="presentation"><a href="#qualification" aria-controls="qualification" role="tab" data-toggle="tab">Квалификация</a></li>
    <?php endif;?>
  </ul>
    
    
<div class="tab-content vert-margin30">  
    
    <div role="tabpanel" class="tab-pane active" id="profile">
    
        <div class="row">
            <div class="col-sm-4 center-align">
                <?php if($model->isNewRecord == false):?>

                    <img src="<?php echo $model->getAvatarUrl();?>" />
                    <small>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'avatarFile'); ?>
                        <?php echo $form->fileField($model, 'avatarFile');?>
                        <?php echo $form->error($model,'avatarFile'); ?>
                    </div> 
                    </small>
                <?php endif;?>
            </div>

            <div class="col-sm-8">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'name'); ?>
                    <?php echo $form->textField($model,'name', array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div> 

                <?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>
                <div class="yurist-fields">
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'name2'); ?>
                        <?php echo $form->textField($model,'name2', array('class'=>'form-control')); ?>
                        <?php echo $form->error($model,'name2'); ?>
                    </div> 

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'lastName'); ?>
                        <?php echo $form->textField($model,'lastName', array('class'=>'form-control')); ?>
                        <?php echo $form->error($model,'lastName'); ?>
                    </div> 
                </div>
                <?php endif;?>
            </div>
        </div>      
    </div>
     
    
    <div role="tabpanel" class="tab-pane" id="contacts">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'email'); ?>
                    <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'email'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'phone'); ?>
                    <?php echo $form->textField($model,'phone', array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'phone'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12"> 
                <div class="form-group">
                    <?php echo $form->labelEx($model,'townId'); ?>
                    <?php echo CHtml::textField('town', ($model->town->name)?$model->town->name:'', array('id'=>'town-selector', 'class'=>'form-control')); ?>
                    <?php
                        echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                    ?>
                </div>
            </div>
        </div>    
    </div>        
        
    
    <div role="tabpanel" class="tab-pane" id="password">
        <?php if($model->isNewRecord == false):?>
            <div class="vert-margin30">
                <br /><br />
                <?php echo CHtml::link('Изменить пароль', Yii::app()->createUrl('user/changePassword', array('id'=>$model->id)), array('class'=>'btn btn-warning'));?>        
            </div>
        <?php endif;?>
    </div>   
       
    
    <?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>

    <div role="tabpanel" class="tab-pane" id="qualification">
        
        <div class="yurist-fields">
        <?php if(($model->role == User::ROLE_JURIST || $model->role == User::ROLE_OPERATOR) && !$model->isNewRecord):?>

        <div class="row">
            <div class="col-sm-12"> 
                <div class="form-group">
                        <?php echo $form->labelEx($yuristSettings,'startYear'); ?>
                        <?php echo $form->textField($yuristSettings,'startYear', array('class'=>'form-control')); ?>
                        <?php echo $form->error($yuristSettings,'startYear'); ?>
                </div>

                <?php echo $form->hiddenField($yuristSettings,'townId', array('class'=>'form-control', 'value'=>$model->townId)); ?>

                <div class="form-group"> 
                    <label>О себе</label>
                    <?php echo $form->textArea($yuristSettings, 'description', array('class'=>'form-control', 'rows'=>3));?>
                    <?php echo $form->error($yuristSettings,'description'); ?>
                </div>
            </div>
        </div>
            <?php endif;?>
        </div>


        <?php if(($model->role == User::ROLE_JURIST || $model->role == User::ROLE_OPERATOR)):?>    
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"> 
                        <?php echo $form->labelEx($yuristSettings,'status'); ?>
                        <?php echo $form->dropDownList($yuristSettings, 'status', YuristSettings::getStatusesArray(), array('class'=>'form-control'));?>
                        <?php echo $form->error($yuristSettings,'status'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"> 
                        <br /><br />
                        <?php
                            if($yuristSettings->isVerified == 1) {
                                echo "<span class='label label-success'>Верифицирован</span>";
                            } else {
                                echo "<span class='label label-danger'>Не верифицирован</span>";
                            }
                        ?>
                    </div>
                </div>
            </div>

            <?php if($yuristSettings->status != 0 && $yuristSettings->isVerified == 0):?>
                <script type="text/javascript">
                    $(function(){
                        $('#user-profile-<?php 

                        switch($yuristSettings->status) {
                            case YuristSettings::STATUS_YURIST:
                                echo 'yurist';
                                break;
                            case YuristSettings::STATUS_ADVOCAT:
                                echo 'advocat';
                                break;
                            case YuristSettings::STATUS_JUDGE:
                                echo 'judge';
                                break;

                        }
                        ?>').show();
                    })
                </script>
            <?php endif;?>

            <div id="user-profile-yurist">
                <p>
                    Для подтверждения статуса юриста необходимо отправить дополнительные данные.
                </p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings,'vuz'); ?>
                                <?php echo $form->textField($yuristSettings,'vuz', array('class'=>'form-control')); ?>
                                <?php echo $form->error($yuristSettings,'vuz'); ?>
                        </div>
                        <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings,'facultet'); ?>
                                <?php echo $form->textField($yuristSettings,'facultet', array('class'=>'form-control')); ?>
                                <?php echo $form->error($yuristSettings,'facultet'); ?>
                        </div>
                        <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings,'education'); ?>
                                <?php echo $form->textField($yuristSettings,'education', array('class'=>'form-control')); ?>
                                <?php echo $form->error($yuristSettings,'education'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                    <?php echo $form->labelEx($yuristSettings,'vuzTownId'); ?>
                                    <?php echo CHtml::textField('vuzTownId', ($yuristSettings->vuzTown->name)?$yuristSettings->vuzTown->name:'', array('id'=>'vuz-town-selector', 'class'=>'form-control')); ?>
                                    <?php
                                        echo $form->hiddenField($yuristSettings, 'vuzTownId', array('id'=>'vuz-selected-town'));
                                    ?>
                            </div>
                    </div>    
                    <div class="col-md-6">    
                            <div class="form-group">
                                    <?php echo $form->labelEx($yuristSettings,'educationYear'); ?>
                                    <?php echo $form->textField($yuristSettings,'educationYear', array('class'=>'form-control')); ?>
                                    <?php echo $form->error($yuristSettings,'educationYear'); ?>
                            </div>
                    </div>

                </div>     

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php echo $form->labelEx($userFile,'userFile'); ?>
                            <?php echo $form->fileField($userFile, 'userFile');?>
                            <?php echo $form->error($userFile,'userFile'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="user-profile-advocat">
                <p>
                    Для подтверждения статуса юриста необходимо отправить дополнительные данные.
                </p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings,'advOrganisation'); ?>
                                <?php echo $form->textField($yuristSettings,'advOrganisation', array('class'=>'form-control')); ?>
                                <?php echo $form->error($yuristSettings,'advOrganisation'); ?>
                        </div>

                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings,'advNumber'); ?>
                                <?php echo $form->textField($yuristSettings,'advNumber', array('class'=>'form-control')); ?>
                                <?php echo $form->error($yuristSettings,'advNumber'); ?>
                            </div>
                    </div>    
                    <div class="col-md-6">    
                            <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings,'position'); ?>
                                <?php echo $form->textField($yuristSettings,'position', array('class'=>'form-control')); ?>
                                <?php echo $form->error($yuristSettings,'position'); ?>
                            </div>
                    </div>
                </div>

            </div>


            <div id="user-profile-judge">
                <p>
                    Функция подтверждения статуса судьи пока в разработке..
                </p>
            </div>   
        <?php endif;?>
    </div> 
    <?php endif;?>
</div> <!-- .tab-content -->        
        
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Зарегистрироваться' : 'Сохранить', array('class'=>'btn btn-primary btn-lg')); ?>
        </div>
    </div>
</div> 

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>