<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'brak-lead-form',
    'enableAjaxValidation' => false,
]); ?>

<script type="text/javascript">
    $(function(){
        
        if($("input[name='Lead[brakReason]']:checked").val() == <?php echo Lead::BRAK_REASON_BAD_REGION; ?>) {
            $('#lead-region').show();
        }
        
        $("input[name='Lead[brakReason]']").on('click', function(){
            $('#lead-region').hide();
            var selected_reason = $(this).val();
            if(selected_reason == <?php echo Lead::BRAK_REASON_BAD_REGION; ?>) {
                $('#lead-region').show();
            }
        })
    })
    
</script>

<div class="form-group">
    <?php echo $form->labelEx($lead, 'brakReason'); ?><br />
    <?php echo $form->radioButtonList($lead, 'brakReason', Lead::getBrakReasonsArray(), [
            'class' => '',
    ]); ?>
    <?php echo $form->error($lead, 'brakReason'); ?>
</div>

<div class="form-group" id="lead-region" <?php if (Lead::BRAK_REASON_BAD_REGION != $model->brakReason):?> style="display:none"<?php endif; ?>>
    <?php echo $form->labelEx($lead, 'Укажите реальный город клиента'); ?>
    <?php echo CHtml::textField('town', '', [
                    'id' => 'town-selector',
                    'class' => 'form-control',
    ]); ?>
    <?php
        echo $form->hiddenField($lead, 'newTownId', ['id' => 'selected-town']);
    ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($lead, 'brakComment'); ?><br />
    <?php echo $form->textArea($lead, 'brakComment', [
            'class' => 'form-control',
            'rows' => 3,
    ]); ?>
    <?php echo $form->error($lead, 'brakComment'); ?>
</div>

<div class="form-group">
    <?php echo CHtml::submitButton('Отправить на отбраковку', ['class' => 'btn btn-primary']); ?>
</div>


<?php $this->endWidget(); ?>