<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile('/js/admin/user.js');

?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', [
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => [
            'class' => 'login-form',
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <p class="note"><span class="required">*</span> - обязательные поля</p>

    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
    <?php echo $form->errorSummary($yuristSettings, 'Исправьте ошибки'); ?>


    <?php if (Yii::app()->user->checkAccess(User::ROLE_MANAGER) || 'update' != $model->scenario): ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'lastName'); ?>
            <?php echo $form->textField($model, 'lastName', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'lastName'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'name2'); ?>
            <?php echo $form->textField($model, 'name2', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'name2'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'role'); ?>
            <?php echo $form->dropDownList($model, 'role', $rolesNames, ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'role'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'phone'); ?>
            <?php echo $form->textField($model, 'phone', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>
    <?php endif; ?>

    <?php if (true == $model->isNewRecord): ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'password2'); ?>
            <?php echo $form->passwordField($model, 'password2', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'password2'); ?>
        </div>
    <?php else: ?>
        <p>
            <?php echo CHtml::link('Изменить пароль', Yii::app()->createUrl('user/changePassword', ['id' => $model->id]), ['class' => 'btn btn-warning']); ?>
        </p>
    <?php endif; ?>


    <?php if (Yii::app()->user->checkAccess(User::ROLE_MANAGER) || 'update' != $model->scenario): ?>
        <div class="form-group">
            <?php echo $form->checkBox($model, 'active100'); ?>
            <?php echo $model->getAttributeLabel('active100'); ?>
            <?php echo $form->error($model, 'active100'); ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'avatarFile'); ?>
        <?php echo $form->fileField($model, 'avatarFile'); ?>
        <?php echo $form->error($model, 'avatarFile'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'town'); ?>
        <?php echo CHtml::textField('town', '', [
            'id' => 'town-selector',
            'class' => 'form-control',
        ]); ?>
        <?php
        echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
        ?>
        <?php echo $form->error($model, 'townId'); ?>
    </div>

    <?php if (User::ROLE_PARTNER == $model->role): ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'priceCoeff'); ?>
            <?php echo $form->textField($model, 'priceCoeff', [
                'class' => 'form-control',
            ]); ?>
            <?php echo $form->error($model, 'priceCoeff'); ?>
        </div>
    <?php endif; ?>

    <?php if (in_array($model->role, [User::ROLE_BUYER, User::ROLE_JURIST])):?>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'yurcrmToken'); ?>
                    <?php echo $form->textField($model, 'yurcrmToken', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'yurcrmToken'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'yurcrmSource'); ?>
                    <?php echo $form->textField($model, 'yurcrmSource', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'yurcrmSource'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (User::ROLE_JURIST == $model->role || $model->isNewRecord): ?>

        <div class="form-group">
            <?php echo $form->labelEx($yuristSettings, 'startYear'); ?>
            <?php echo $form->textField($yuristSettings, 'startYear', ['class' => 'form-control']); ?>
            <?php echo $form->error($yuristSettings, 'startYear'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($yuristSettings, 'description'); ?>
            <?php echo $form->textArea($yuristSettings, 'description', ['class' => 'form-control', 'rows' => 3]); ?>
            <?php echo $form->error($yuristSettings, 'description'); ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->labelEx($yuristSettings, 'status'); ?>
                    <?php echo $form->dropDownList($yuristSettings, 'status', YuristSettings::getStatusesArray(), ['class' => 'form-control']); ?>
                    <?php echo $form->error($yuristSettings, 'status'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <br/><br/>
                    <?php echo $form->checkBox($yuristSettings, 'isVerified'); ?>
                    <?php echo $yuristSettings->getAttributeLabel('isVerified'); ?>
                    <?php echo $form->error($yuristSettings, 'isVerified'); ?>
                </div>
            </div>
        </div>


        <?php if ($model->files): ?>
            <h4>Заявки на подтверждение статуса</h4>

            <table class="table table-bordered">
                <?php foreach ($model->files as $file): ?>

                    <?php
                    switch ($file->isVerified) {
                        case UserFile::STATUS_REVIEW:
                            $fileTrClass = 'active';
                            break;
                        case UserFile::STATUS_CONFIRMED:
                            $fileTrClass = 'success';
                            break;
                        case UserFile::STATUS_DECLINED:
                            $fileTrClass = 'danger';
                            break;
                    }
                    ?>

                    <tr id="file-id-<?php echo $file->id; ?>" class="<?php echo $fileTrClass; ?>">
                        <td>
                            <?php echo DateHelper::niceDate($file->datetime); ?>
                        </td>
                        <td>
                            <?php echo $file->getTypeName(); ?>
                        </td>
                        <td>
                            <?php echo CHtml::link('Файл', UserFile::USER_FILES_FOLDER . '/' . $file->name, ['target' => '_blank']); ?>
                        </td>
                        <td>
                            <strong><?php echo $file->getStatusName(); ?></strong>
                            <?php if ($file->reason): ?>
                                <p>
                                    <?php echo CHtml::encode($file->reason); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!$file->isVerified): ?>
                                <?php echo CHtml::link('Обработать', '#', ['class' => 'process-user-file', 'data-id' => $file->id]); ?>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    <?php endif; ?>

    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить пользователя' : 'Сохранить', ['class' => 'btn btn-primary btn-lg']); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


<?php if (User::ROLE_OPERATOR == $model->role || User::ROLE_JURIST == $model->role || $model->isNewRecord): ?>

    <!-- Modal -->
    <div class="modal fade" id="file-process-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Проверка скана</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <?php echo CHtml::textArea('reason', '', ['id' => 'file-reason', 'class' => 'form-control', 'placeholder' => 'Причина отказа', 'rows' => 3]); ?>
                            <input type="hidden" name="file_id" id="file_id" value=""/>
                        </div>

                        <button type="button" class="btn btn-success" id="file-process-confirm-btn">Подтвердить</button>
                        <button type="button" class="btn btn-danger" id="file-process-decline-btn">Отказать</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
