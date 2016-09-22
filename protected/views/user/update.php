<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Редактирование профиля пользователя ' . CHtml::encode($model->name . ' ' . $model->lastName) . '. ' . Yii::app()->name;

$this->breadcrumbs=array(
	CHtml::encode($model->name . ' ' . $model->lastName) =>  array('/user/profile'),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Редактирование профиля</h1>

        <?php echo $this->renderPartial('_form', array(
                'model'             =>  $model,
                'rolesNames'        =>  $rolesNames,
                'allManagersNames'  =>  $allManagersNames,
                'yuristSettings'    =>  $yuristSettings,
                'userFile'          =>  $userFile,
                'townsArray'        =>  $townsArray,
            )); ?>

    </div>
</div>

<?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php if($model->files):?>
                <h4>Заявки на подтверждение статуса</h4>

                <table class="table table-bordered">
                <?php foreach($model->files as $file):?>

                    <?php
                        switch($file->isVerified) {
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

                    <tr id="file-id-<?php echo $file->id;?>" class="<?php echo $fileTrClass;?>">
                        <td>
                            <?php echo CustomFuncs::niceDate($file->datetime);?>
                        </td>
                        <td>
                            <?php echo $file->getTypeName();?>
                        </td>
                        <td>
                            <?php echo CHtml::link("Файл", UserFile::USER_FILES_FOLDER . '/' . $file->name, array('target'=>'_blank'));?>
                        </td>
                        <td>
                            <strong><?php echo ($file->isVerified)?"Проверен":"На проверке";?></strong>
                            <?php if($file->reason):?>
                            <p>
                                <?php echo CHtml::encode($file->reason);?>
                            </p>
                            <?php endif;?>
                        </td>
                    </tr>

                <?php endforeach;?>
                </table>
            <?php endif;?>
    </div>
</div>
<?php endif;?>