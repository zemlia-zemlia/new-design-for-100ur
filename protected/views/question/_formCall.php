<?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'call-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/call'),
                )); ?>
    <div class="row">  
		<div class="col-md-6">
			<div class="form-group">
					<?php echo $form->labelEx($model,'name'); ?>
					<?php echo $form->textField($model,'name', array('class'=>'form-control', 'placeholder'=>'Иванов Иван')); ?>
					<?php echo $form->error($model,'name'); ?>
			</div>
		</div>
	</div>
	
	<div class="row"> 
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'phone'); ?>
                    <?php echo $form->textField($model,'phone', array(
                            'class'         =>  'form-control phone-mask', 
                            'data-toggle'   =>  "tooltip",
                            'data-placement'=>  "bottom",
                            'title'         =>  "Номер телефона необходим, чтобы юрист смог с Вами связаться. Нигде не публикуется.",
                            )); ?>
                    <?php echo $form->error($model,'phone'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <small>
                <img src="/pics/2017/red_lock.png" alt="" style="float:left;margin-top:10px;" />
                <p class="text-muted" style="padding-top:10px;margin-left:35px;">

                    Ваши данные в безопасности. Ваш телефон <strong>НИГДЕ и НИКОГДА</strong> не публикуется и доступен только юристу-консультанту
                </p>
                </small>
            </div>
	</div>
    <div class="form-group">
        <label>Комментарий:</label>
        <?php echo $form->textArea($model,'question', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Пожалуйста, опишите суть вопроса.')); ?>
        <?php echo $form->error($model,'question'); ?>
    </div>
	

    <div class="form-group">
	<div class="row">
            <div class="col-md-6">
            
            <?php echo $form->labelEx($model,'town'); ?>
            <?php echo CHtml::textField('town', $currenTownName, array(
                'id'            =>  'town-selector', 
                'class'         =>  'form-control',
            )); ?>
                
            <?php
                echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
            ?>
            <?php echo $form->error($model,'townId'); ?>
		</div>
	</div>	
	</div>

<div class="vert-margin20">
    <small class="text-muted">
      <label>
          <input type="checkbox" value="1" checked="checked">
        Отправляя запрос, вы соглашаетесь с условиями <?php echo CHtml::link('пользовательского соглашения', Yii::app()->createUrl('site/offer'), array('target'=>'_blank'));?>
      </label>
    </small>
</div>

    <div class="form-group" id="form-submit-wrapper">
        <?php echo CHtml::submitButton('Отправить запрос', array('class'=>'yellow-button center-block')); ?>
    </div>

<?php $this->endWidget(); ?>