<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'brak-lead-form',
	'enableAjaxValidation'=>false,
)); ?>

<script type="text/javascript">
    $(function(){
        
        if($("input[name='Lead100[brakReason]']:checked").val() == <?php echo Lead100::BRAK_REASON_BAD_REGION;?>) {
            $('#lead-region').show();
        }
        
        $("input[name='Lead100[brakReason]']").on('click', function(){
            $('#lead-region').hide();
            var selected_reason = $(this).val();
            if(selected_reason == <?php echo Lead100::BRAK_REASON_BAD_REGION;?>) {
                $('#lead-region').show();
            }
        })
    })
    
</script>

<div class="form-group">
    <?php echo $form->labelEx($lead,'brakReason'); ?><br />
    <?php echo $form->radioButtonList($lead,'brakReason', Lead100::getBrakReasonsArray(), array(
            'class' =>  '', 
    )); ?>
    <?php echo $form->error($lead,'brakReason'); ?>
</div>

<div class="form-group" id="lead-region" <?php if($model->brakReason != Lead100::BRAK_REASON_BAD_REGION):?> style="display:none"<?php endif;?>>
    <?php echo $form->labelEx($lead,'town'); ?>
    <?php echo CHtml::textField('town', '', array(
                    'id'            =>  'town-selector', 
                    'class'         =>  'form-control',
    )); ?>
    <?php
        echo $form->hiddenField($lead, 'newTownId', array('id'=>'selected-town'));
    ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($lead,'brakComment'); ?><br />
    <?php echo $form->textArea($lead,'brakComment', array(
            'class' =>  'form-control',
            'rows'  =>  3,
    )); ?>
    <?php echo $form->error($lead,'brakComment'); ?>
</div>

<div class="form-group">
    <?php echo CHtml::submitButton('Отправить на отбраковку', array('class'=>'btn btn-primary')); ?>
</div>


<?php $this->endWidget(); ?>