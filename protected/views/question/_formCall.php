<?php
$allDirections = [0 => 'Не выбрано'] + $allDirections;

$currenTownName = (!is_null($model->town) && !is_null($model->town->name)) ?
    $model->town->name:
    '';

?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id' => 'call-form',
    'enableAjaxValidation' => false,
    'action' => Yii::app()->createUrl('question/call'),
    'htmlOptions' => ['class' => 'form-horizontal'],
        ]);
?>
<div class="row">  
    <div class="col-md-12">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'name', ['class' => 'form-control', 'placeholder' => 'Ваше имя']); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
        </div>
    </div>
</div>

<div class="row"> 
    <div class="col-md-12">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'phone', ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-sm-4">
                <?php
                echo $form->textField($model, 'phone', [
                    //'class'         =>  'form-control phone-mask',
                    'class' => 'form-control icon-input phone-mask',
                    'style' => 'background-image:url(/pics/2017/phone_icon.png)',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                    'title' => 'Номер телефона необходим, чтобы юрист смог с Вами связаться. Нигде не публикуется.',
                ]);
                ?>
                <small>
                    <img src="/pics/2017/red_lock.png" alt="ваши данные в безопасности" style="float:left;margin-top:10px;" />
                    <p class="text-muted" style="padding-top:10px;margin-left:35px;">

                        Ваши данные в безопасности. Телефон <strong>НИГДЕ и НИКОГДА</strong> не публикуется и доступен только юристу-консультанту. Звонок для вас БЕСПЛАТНЫЙ!
                    </p>
                </small>
                <?php echo $form->error($model, 'phone'); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">

    </div>
</div>

<?php if (false):?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-sm-4 control-label">Категория права</label>
            <div class="col-sm-8">
                <?php echo $form->dropDownList($model, 'categories', $allDirections, ['class' => 'form-control']); ?>
                <small>
                    <p class="text-muted">
                        Правильный выбор категории поможет найти специалистов именно в этой отрасли права. Если Вы сомневаетесь в выборе, пропустите этот пункт.
                    </p>
                </small>
                <?php echo $form->error($model, 'categories'); ?>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>

<div class="form-group">
    <label class='col-sm-4 control-label'>Тема:</label>
    <div class="col-sm-7">
        <?php echo $form->textArea($model, 'question', ['class' => 'form-control', 'rows' => 6, 'placeholder' => 'В двух словах, о чем ваш вопрос?']); ?>
        <?php echo $form->error($model, 'question'); ?>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">

            <?php echo $form->labelEx($model, 'town', ['class' => 'col-sm-4 control-label']); ?>
            <div class="col-sm-4">
                <?php
                echo CHtml::textField('town', $currenTownName, [
                    'id' => 'town-selector',
                    'class' => 'form-control icon-input',
                    'style' => 'background-image:url(/pics/2017/map_mark_icon.png)',
                ]);
                ?>

                <?php
                echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
                ?>
                <?php echo $form->error($model, 'townId'); ?>
            </div>
        </div>
    </div>	
</div>

<div class="vert-margin20 center-align">
    <small class="text-muted">
        <label>
            <?php echo $form->checkBox($model, 'agree'); ?>
            Отправляя вопрос, вы соглашаетесь с условиями <?php echo CHtml::link('пользовательского соглашения', Yii::app()->createUrl('site/offer'), ['target' => '_blank']); ?>
        </label>
        <?php echo $form->error($model, 'agree'); ?>
    </small>
</div>

<div class="form-group" id="form-submit-wrapper">
    <?php echo CHtml::submitButton('Отправить запрос', ['class' => 'yellow-button center-block']); ?>
</div>

<?php $this->endWidget(); ?>