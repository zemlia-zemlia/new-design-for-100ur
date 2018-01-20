<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'                    =>  'question-form',
	'enableAjaxValidation'  =>  false,
        'action'                =>  Yii::app()->createUrl('question/create'),
        'htmlOptions'               =>  ['class'=>'form-horizontal'],
)); ?>

	

<?php //echo $form->errorSummary($model, "Для отправки вопроса укажите данные"); ?>
<?php //CustomFuncs::printr($model->attributes);?>
    
<?php
$allDirections = array(0=>'Не выбрано') + $allDirections;
?>

  
<div class="form-group">
    <?php echo $form->labelEx($model,'title', ['class' =>  'col-sm-4 control-label']); ?>
    <div class="col-sm-8">
        <?php echo $form->textField($model,'title', array('class'=>'form-control', 'placeholder'=>'Например, Как оспорить наследство?')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Подробное описание</label>
    <div class="col-sm-8">
        <?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>10, 'placeholder'=>'Опишите вашу ситуацию подробнее, чтобы юрист мог более детально в нем сориентироваться и дать на него квалифицированный ответ.')); ?>
        <?php echo $form->error($model,'questionText'); ?>
    </div>
    
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-sm-4 control-label">Категория права</label>
            <div class="col-sm-8">
            <?php echo $form->dropDownList($model,'categories', $allDirections, array('class'=>'form-control')); ?>
                <small>
                <p class="text-muted">
                    Правильный выбор категории поможет найти специалистов именно в этой отрасли права. Если Вы сомневаетесь в выборе, пропустите этот пункт.
                </p>
                </small>
                <?php echo $form->error($model,'categories'); ?>
            </div>
            
	</div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo $form->labelEx($model,'authorName', ['class' =>  'col-sm-4 control-label']); ?>
            <div class="col-sm-8">
		<?php echo $form->textField($model,'authorName', array('class'=>'form-control', 'placeholder'=>'Представьтесь')); ?>
                <?php echo $form->error($model,'authorName'); ?>
            </div>
            
	</div>
    </div>
</div>
        
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
		<?php echo $form->labelEx($model,'phone', ['class' =>  'col-sm-4 control-label']); ?>
            <div class="col-sm-8">
		<?php echo $form->textField($model,'phone', array(
                    'class'         =>  'form-control icon-input', 
                    'style'         =>  'background-image:url(/pics/2017/phone_icon.png)',
                    'data-toggle'   =>  "tooltip",
                    'data-placement'=>  "bottom",
                    'title'         =>  "Номер телефона необходим, чтобы юрист смог с Вами связаться. Нигде не публикуется.",
                    )); ?>
                <small>
                <img src="/pics/2017/red_lock.png" alt="ваши данные в безопасности" style="float:left;margin-top:10px;" />
                <p class="text-muted" style="padding-top:10px;margin-left:35px;">

                    Ваши данные в безопасности. Ваш телефон <strong>НИГДЕ и НИКОГДА</strong> не публикуется и доступен только юристу-консультанту
                </p>
                </small>
		<?php echo $form->error($model,'phone'); ?>
            </div>
	</div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">    
        
        <div class="form-group">
            <?php
                // Если город определен по IP адресу, не показываем поле ввода города
                //$currenTownName = (Yii::app()->user->getState('currentTownName'))?Yii::app()->user->getState('currentTownName'):'';
                //$currentRegionName = (Yii::app()->user->getState('currentTownRegionName'))?Yii::app()->user->getState('currentTownRegionName'):'';
                //$currenTownId = (Yii::app()->user->getState('currentTownId'))?Yii::app()->user->getState('currentTownId'):0;
                
                /*if(!$currenTownName && $model->townId!=0 && !is_null($model->town->name)) {
                    $currenTownName = $model->town->name;
                }*/
                $currenTownId = 0;
                if(!is_null($model->town->name)) {
                    $currenTownName = $model->town->name;
                }
                
            ?>
            
            <?php if($currenTownId == 0):?>
                <?php echo $form->labelEx($model,'town', ['class' =>  'col-sm-4 control-label']); ?>
                <div class="col-sm-8">
                    <?php echo CHtml::textField('town', $currenTownName, array(
                        'id'            =>  'town-selector', 
                        'class'         =>  'form-control icon-input', 
                        'style'         =>  'background-image:url(/pics/2017/map_mark_icon.png)',
                    )); ?>
                    <?php echo $form->error($model,'townId'); ?>
                </div>
            <?php endif;?>
            
                <?php
                    echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                ?>
		
            
            
		<?php //echo $form->labelEx($model,'email'); ?>
		<?php /*echo $form->textField($model,'email', array(
                    'class'         =>  'form-control', 
                    'data-toggle'   =>  "tooltip",
                    'data-placement'=>  "bottom",
                    'title'         =>  "Необходим для отправки Вам уведомлений о новых ответах юристов, а также является логином для входа на сайт. Нигде не публикуется.",
                    'placeholder'=>'ivanov@mail.ru'));*/ ?>
		<?php //echo $form->error($model,'email'); ?>
	</div>
    </div>
</div>

<?php echo $form->hiddenField($model, 'sessionId', array('value'=>$model->sessionId));?>

<div class="vert-margin20 center-align">
<small class="text-muted">
  <label>
      <input type="checkbox" value="1" checked="checked">
    Отправляя вопрос, вы соглашаетесь с условиями <?php echo CHtml::link('пользовательского соглашения', Yii::app()->createUrl('site/offer'), array('target'=>'_blank'));?>
  </label>
</small>
</div>


<?php if($pay == true):?>
<div class="payment-options  vert-margin30">
    <div class="row">
        <div class="col-sm-3">
            <label>
            <input type="radio" name="Question[price]" value="0" checked="checked"/> <strong>Бесплатно</strong>
            <p><small>Ответ в течение суток</small></p>
            </label>
        </div>
        <div class="col-sm-3">
            <label>
            <input type="radio" name="Question[price]" value="99" /> <strong>125 руб.</strong>
            <p><small>Гарантия 1 ответа юриста в течение 3 часов</small></p>
            </label>
        </div>
        <div class="col-sm-3">
            <label>
            <input type="radio" name="Question[price]" value="199" /> <strong>255 руб.</strong>
            <p><small>Гарантия 3 ответов юристов в течение 3 часов</small></p>
            </label>
        </div>
        <div class="col-sm-3">
            <label>
            <input type="radio" name="Question[price]" value="299" /> <strong>399 руб.</strong>
            <p><small>Гарантия 5 ответов юристов в течение 3 часов</small></p>
            </label>
        </div>
    </div>
</div>
<?php endif;?>

	<div class="form-group">
		<?php echo CHtml::submitButton('Отправить вопрос юристу', array('class'=>'yellow-button center-block')); ?>
	</div>

<?php $this->endWidget(); ?>