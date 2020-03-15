<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */
/* @var $form CActiveForm */

$statusesArray = YuristSettings::getStatusesArray();
unset($statusesArray[0]);
unset($statusesArray[3]);
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', [
        'id' => 'user-status-request-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'htmlOptions' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <script type="text/javascript">
        $(function () {

            $("input[name='UserStatusRequest[status]']").on('change', function () {
                var yuristStatus = $(this).val();

                $("#user-profile-advocat, #user-profile-yurist, #user-profile-company").hide();

                $("#submitStatusRequest").removeAttr('disabled');

                switch (yuristStatus) {
                    case '1':
                        $("#user-profile-yurist").show();
                        $("#form-submit").show();

                        break;
                    case '2':
                        $("#user-profile-advocat").show();
                        $("#form-submit").show();
                        break;
                    case '4':
                        $("#user-profile-company").show();
                        $("#form-submit").show();
                        break;
                }

                /*if(yuristStatus!=0) {
                    $("#submitStatusRequest").removeAttr('disabled');
                } else {
                    $("#submitStatusRequest").attr('disabled', 'disabled');
                }*/
            })

            $('#user-profile-<?php

                switch ($model->status) {
                    case YuristSettings::STATUS_YURIST:
                        echo 'yurist';
                        break;
                    case YuristSettings::STATUS_ADVOCAT:
                        echo 'advocat';
                        break;
                    case YuristSettings::STATUS_COMPANY:
                        echo 'company';
                        break;
                }
                ?>').show();
            <?php if (count($model->errors)):?>
            $('#form-submit').show();
            <?php endif; ?>
        })
    </script>


    <div class='flat-panel vert-margin20 inside'>
        <p class="text-center"><strong>Какой статус подтверждаем?</strong></p>

        <div class="vert-margin20">
            <div class='row'>
                <?php foreach ($statusesArray as $statusCode => $statusName): ?>
                    <div class='col-md-4 radio-block'>
                        <label>
                            <input type="radio" name="UserStatusRequest[status]"
                                   value="<?php echo $statusCode; ?>" <?php if ($statusCode == $currentUser->settings->status) {
                                echo 'disabled';
                            } ?> <?php if ($statusCode == $model->status) {
                                echo 'checked';
                            } ?> /> <?php echo $statusName; ?>
                        </label>
                        <?php if ($statusCode == $currentUser->settings->status): ?>
                            <small class="text-muted">(текущий)</small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class='flat-panel vert-margin20 inside'>

        <div id="user-profile-yurist">
            <p>
                Для подтверждения статуса юриста необходимо отправить дополнительные данные.
            </p>

            <hr/>
            <h3 class="left-align text-uppercase">Образование</h3>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'vuz'); ?>
                        <?php echo $form->textField($model, 'vuz', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'vuz'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'facultet'); ?>
                        <?php echo $form->textField($model, 'facultet', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'facultet'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'education'); ?>
                        <?php echo $form->textField($model, 'education', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'education'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'vuzTownId'); ?>
                        <?php echo CHtml::textField('vuzTownId', ($model->vuzTown->name) ? $model->vuzTown->name : '', ['id' => 'vuz-town-selector', 'class' => 'form-control']); ?>
                        <?php
                        echo $form->hiddenField($model, 'vuzTownId', ['id' => 'vuz-selected-town']);
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'educationYear'); ?>
                        <?php echo $form->textField($model, 'educationYear', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'educationYear'); ?>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $form->labelEx($userFile, 'userFile'); ?>
                        <?php echo $form->fileField($userFile, 'userFile'); ?>
                        <?php echo $form->error($userFile, 'userFile'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="user-profile-advocat">
            <p>
                Для подтверждения статуса адвоката необходимо отправить дополнительные данные.
            </p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'advOrganisation'); ?>
                        <?php echo $form->textField($model, 'advOrganisation', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'advOrganisation'); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'advNumber'); ?>
                        <?php echo $form->textField($model, 'advNumber', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'advNumber'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'position'); ?>
                        <?php echo $form->textField($model, 'position', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'position'); ?>
                    </div>
                </div>
            </div>

        </div>


        <div id="user-profile-company">
            <p class="text-center">
                Для подтверждения статуса юридической фирмы необходимо отправить дополнительные данные.
            </p>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'companyName'); ?>
                        <?php echo $form->textField($model, 'companyName', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'companyName'); ?>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'inn'); ?>
                        <?php echo $form->textField($model, 'inn', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'inn'); ?>
                    </div>
                </div>

                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'address'); ?>
                        <?php echo $form->textField($model, 'address', ['class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'address'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div id='form-submit' class="row buttons inside center-align">
            <?php

            $submitButtonAttributes = ['id' => 'submitStatusRequest', 'class' => 'yellow-button'];

            if (!$model->errors) {
                $submitButtonAttributes['disabled'] = 'disabled';
            }
            ?>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить заявку' : 'Сохранить', $submitButtonAttributes); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->