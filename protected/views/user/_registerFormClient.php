<div class="form-group">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'name'); ?>
</div> 

<div class="form-group">
    <?php echo $form->labelEx($model, 'email'); ?>
    <?php echo $form->textField($model, 'email', ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'email'); ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'phone'); ?>
    <?php echo $form->textField($model, 'phone', ['class' => 'form-control phone-mask']); ?>
    <?php echo $form->error($model, 'phone'); ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'townId'); ?>
    <?php echo CHtml::textField('town', ($model->town->name) ? $model->town->name : '', ['id' => 'town-selector', 'class' => 'form-control']); ?>
    <?php
        echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
    ?>
</div>