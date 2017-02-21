<?php
/* @var $this YurCompanyController */
/* @var $model YurCompany */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCssFile('/css/2015/jquery-ui.css');
Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js', CClientScript::POS_END);

?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'yur-company-form',
        'htmlOptions'   =>  array(
            'enctype'   =>  'multipart/form-data',
            ),
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <?php echo $form->labelEx($model,'name'); ?>
                        <?php echo $form->textField($model,'name',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'name'); ?>
                </div>
                
                <div class="form-group">
                        <?php echo $form->labelEx($model,'townId'); ?>
                        <?php echo CHtml::textField('town', '', array('id'=>'town-selector', 'class'=>'form-control')); ?>
                        <?php
                            echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                        ?>
                        <?php echo $form->error($model,'townId'); ?>
                </div>
                
                <div class="form-group">
                    <?php echo $form->labelEx($model,'photoFile'); ?>
                    <?php echo $form->fileField($model, 'photoFile');?>
                    <?php echo $form->error($model,'photoFile'); ?>
                </div> 
                
                <div class="form-group">
                        <?php echo $form->labelEx($model,'address'); ?>
                        <?php echo $form->textField($model,'address',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'address'); ?>
                </div>
                
                <div class="form-group">
                        <?php echo $form->labelEx($model,'phone1'); ?>
                        <?php echo $form->textField($model,'phone1',array('class'=>'form-control field-phone', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'phone1'); ?>
                </div>
                
                <div class="form-group">
                        <?php echo $form->labelEx($model,'description'); ?>
                        <?php echo $form->textArea($model,'description',array('class'=>'form-control', 'rows'=>6, 'cols'=>50)); ?>
                        <?php echo $form->error($model,'description'); ?>
                </div>
                
            </div>
            <div class="col-md-6">
                <div class="form-group">
                        <?php echo $form->labelEx($model,'metro'); ?>
                        <?php echo $form->textField($model,'metro',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'metro'); ?>
                </div>

                <div class="form-group">
                        <?php echo $form->labelEx($model,'yurName'); ?>
                        <?php echo $form->textField($model,'yurName',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'yurName'); ?>
                </div>


                <div class="form-group">
                        <?php echo $form->labelEx($model,'yurAddress'); ?>
                        <?php echo $form->textField($model,'yurAddress',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'yurAddress'); ?>
                </div>

                <div class="form-group">
                        <?php echo $form->labelEx($model,'phone2'); ?>
                        <?php echo $form->textField($model,'phone2',array('class'=>'form-control field-phone', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'phone2'); ?>
                </div>

                <div class="form-group">
                        <?php echo $form->labelEx($model,'phone3'); ?>
                        <?php echo $form->textField($model,'phone3',array('class'=>'form-control field-phone', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'phone3'); ?>
                </div>

                <div class="form-group">
                        <?php echo $form->labelEx($model,'yearFound'); ?>
                        <?php echo $form->textField($model,'yearFound',array('class'=>'form-control')); ?>
                        <?php echo $form->error($model,'yearFound'); ?>
                </div>

                <div class="form-group">
                        <?php echo $form->labelEx($model,'website'); ?>
                        <?php echo $form->textField($model,'website',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
                        <?php echo $form->error($model,'website'); ?>
                </div>
            </div>
        </div>    
	
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
