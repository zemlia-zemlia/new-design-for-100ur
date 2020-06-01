<?php
/* @var $this UserController */

use App\models\User;
use App\models\YuristSettings;

/* @var $model User */
/* @var $form CActiveForm */
/* @var $yuristSettings YuristSettings */

Yii::app()->clientScript->registerScriptFile('/js/user.js', CClientScript::POS_END);
$yuristSettings->priceConsult = MoneyFormat::rubles($yuristSettings->priceConsult);
$yuristSettings->priceDoc = MoneyFormat::rubles($yuristSettings->priceDoc);
?>

<style>
    .yurist-fields {
        display:block;
    }
</style>

<div class="container-fluid">
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', [
            'id' => 'user-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => [
                'class' => 'login-form',
                'enctype' => 'multipart/form-data',
            ],
        ]);
        ?>

        
        <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
        <?php echo $form->errorSummary($yuristSettings, 'Исправьте ошибки'); ?>

        <?php
        $userCategories = [];

        foreach ($model->categories as $uCat) {
            $userCategories[] = $uCat->id;
        }
        ?>

        <div class='flat-panel inside vert-margin20'>
            <?php if (User::ROLE_JURIST == Yii::app()->user->role): ?> 
                <h3>
                    <?php echo CHtml::encode($model->name . ' ' . $model->name2 . ' ' . $model->lastName); ?>
                </h3>    
                <p><small>Ваш email: <?php echo CHtml::encode($model->email); ?> </small></p> 
                <p>
                    <small>Для изменения ФИО или Email отправьте запрос на адрес admin@100yuristov.com</small>
                </p>

            <?php else: ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name'); ?>
                    <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>
            <?php endif; ?>

        </div>

        <?php if (User::ROLE_JURIST == $model->role): ?>
            <div class='flat-panel inside vert-margin20'>    
                <div class="row">
                    <div class="col-sm-4 center-align">
                        <?php if (false == $model->isNewRecord): ?>

                            <img src="<?php echo $model->getAvatarUrl('big'); ?>"  class='img-bordered' />
                            <small>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'avatarFile'); ?>
                                    <?php echo $form->fileField($model, 'avatarFile'); ?>
                                    <?php echo $form->error($model, 'avatarFile'); ?>
                                </div> 
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-8">
                        <?php if (User::ROLE_CLIENT != Yii::app()->user->role): ?>
                            <div class="form-group"> 
                                <label>Приветствие</label>
                                <?php echo $form->textArea($yuristSettings, 'hello', ['class' => 'form-control', 'rows' => 8]); ?>
                                <?php echo $form->error($yuristSettings, 'hello'); ?>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php echo $form->labelEx($model, 'phone'); ?>
                                        <?php echo $form->textField($model, 'phone', ['class' => 'form-control']); ?>
                                        <?php echo $form->error($model, 'phone'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <p><br /><small>Этот номер не будет отображаться на сайте</small></p>
                                </div>
                            </div>


                        <?php endif; ?>
                    </div>
                </div>      

                <div class="form-group"> 
                    <label>О себе</label>
                    <?php echo $form->textArea($yuristSettings, 'description', ['class' => 'form-control', 'rows' => 5]); ?>
                    <?php echo $form->error($yuristSettings, 'description'); ?>
                </div>

            </div>

            <?php if (User::ROLE_CLIENT != Yii::app()->user->role): ?>
                <div class='flat-panel inside vert-margin20'>
                    <h3 class="left-align text-uppercase">Контакты</h3>

                    <div class="row">
                        <div class="col-sm-6">
                            <?php if (User::ROLE_JURIST != Yii::app()->user->role): ?>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'phone'); ?>
                                    <?php echo $form->textField($model, 'phone', ['class' => 'form-control phone-mask']); ?>
                                    <?php echo $form->error($model, 'phone'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings, 'emailVisible'); ?>
                                <?php echo $form->textField($yuristSettings, 'emailVisible', ['class' => 'form-control']); ?>
                                <?php echo $form->error($yuristSettings, 'emailVisible'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->labelEx($yuristSettings, 'phoneVisible'); ?>
                                <?php echo $form->textField($yuristSettings, 'phoneVisible', ['class' => 'form-control phone-mask']); ?>
                                <?php echo $form->error($yuristSettings, 'phoneVisible'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'townId'); ?>
                                <?php echo CHtml::textField('town', ($model->town->name) ? $model->town->name : '', ['id' => 'town-selector', 'class' => 'form-control']); ?>
                                <?php
                                echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
                                ?>
                            </div>
                        </div>
                    </div> 
                </div>

            <?php endif; ?>    


            <div class='flat-panel inside vert-margin20'>
                <h3 class="left-align text-uppercase">Платные услуги</h3>

                <div class="row">
                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <?php echo $form->labelEx($yuristSettings, 'priceConsult'); ?>
                            <div class="input-group">
                                <?php echo $form->textField($yuristSettings, 'priceConsult', ['class' => 'form-control right-align', 'aria-describedby' => 'price-consult-input']); ?>
                                <span class="input-group-addon" id="price-consult-input">руб.</span>
                            </div>
                            <?php echo $form->error($yuristSettings, 'priceConsult'); ?>
                        </div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <?php echo $form->labelEx($yuristSettings, 'priceDoc'); ?>
                            <div class="input-group">
                                <?php echo $form->textField($yuristSettings, 'priceDoc', ['class' => 'form-control right-align', 'aria-describedby' => 'price-doc-input']); ?>
                                <span class="input-group-addon" id="price-doc-input">руб.</span>
                            </div>
                            <?php echo $form->error($yuristSettings, 'priceDoc'); ?>
                        </div>
                    </div>
                </div>    
            </div>


            <div class='flat-panel inside vert-margin20'>
                <h3 class="left-align text-uppercase">Специализации</h3>

                <div class="form-group"> 
                    <?php
                    $directionsCount = sizeof($allDirections);
                    $counter = 0;
                    ?>

                    <div class="row">

                        <?php foreach ($allDirections as $key => $direction): ?>

                            <?php
                            if (in_array($key, $userCategories)) {
                                $checked = true;
                            } else {
                                $checked = false;
                            }
                            ?>
                            <div class="col-md-6 checkbox-container">
                                <div class="checkbox checkbox-profile">
                                    <label>
                                        <?php echo CHtml::checkBox('User[categories][]', $checked, ['value' => $key, 'class' => 'checkbox-root']); ?>
                                        <?php echo $direction['name']; ?>
                                    </label>
                                </div>
                            </div> <!-- .col-md-6 -->   
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>


        <?php if (false == $model->isNewRecord): ?>

            <div class='flat-panel inside vert-margin20'>
                <h3 class="left-align text-uppercase">
                    Настройки уведомлений о новых вопросах    
                </h3>
                <?php echo $form->radioButtonList($yuristSettings, 'subscribeQuestions', YuristSettings::getSubscriptionsArray()); ?>      

            </div>

            <div class='flat-panel inside vert-margin20'>
                <h3 class="left-align text-uppercase">
                    Пароль <?php echo CHtml::link('Изменить', Yii::app()->createUrl('user/changePassword', ['id' => $model->id]), ['class' => 'btn btn-default btn-sm']); ?>        
                </h3>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group center-align">
                    <?php echo CHtml::submitButton('Сохранить изменения', ['class' => 'btn btn-primary btn-lg']); ?>
                </div>
            </div>
        </div> 

        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>