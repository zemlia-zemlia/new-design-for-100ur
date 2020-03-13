<div class="row">
    <div class="col-lg-8">
        <div class='row'>
            <div class='col-md-8 col-md-offset-2 center-align'>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'lastName'); ?>
                    <?php echo $form->textField($model, 'lastName', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'lastName'); ?>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-8 col-md-offset-2 center-align'>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name'); ?>
                    <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-8 col-md-offset-2 center-align'>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name2'); ?>
                    <?php echo $form->textField($model, 'name2', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'name2'); ?>
                </div>
            </div>

        </div>

        <div class='row'>
            <div class='col-md-8 col-md-offset-2 center-align'>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-8 col-md-offset-2 center-align'>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'phone'); ?>
                    <?php echo $form->textField($model, 'phone', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'phone'); ?>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-8 col-md-offset-2 center-align'>
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

    <div class="col-lg-4">
        <div class="alert alert-info">
            Если вы представляете юридическию фирму, проходите регистрацию с указанием контактного лица. После
            регистрации вы сможете указать название своей фирмы и работать от ее имени.
        </div>
    </div>
</div>

