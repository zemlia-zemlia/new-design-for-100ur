
<div class='row'>
    <div class='col-md-6'>
        <div class="form-group">
            <?php echo $form->labelEx($model,'lastName'); ?>
            <?php echo $form->textField($model,'lastName', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'lastName'); ?>
        </div> 
    </div>
</div>
<div class='row'>
    <div class='col-md-6'>
        <div class="form-group">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div> 
    </div>
    <div class='col-md-6'>
        <div class="form-group">
            <?php echo $form->labelEx($model,'name2'); ?>
            <?php echo $form->textField($model,'name2', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'name2'); ?>
        </div> 
    </div>
    
</div>

<div class='row'>
    <div class='col-md-6'>
        <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
    </div>
    <div class='col-md-6'>
        <div class="form-group">
            <?php echo $form->labelEx($model,'townId'); ?>
            <?php echo CHtml::textField('town', ($model->town->name)?$model->town->name:'', array('id'=>'town-selector', 'class'=>'form-control')); ?>
            <?php
                echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
            ?>
        </div>
    </div>
</div>

