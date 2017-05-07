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


<h1 class="vert-margin30">Редактирование профиля</h1>

<?php if($newUser):?>
<div class="alert alert-info">
    Максимально заполните информацию о себе.<br /><br />
    Юристы с заполненным профилем участвуют в рейтинге и доверие к ним выше.
</div>
<?php endif;?>

<?php echo $this->renderPartial('_form', array(
        'model'             =>  $model,
        'rolesNames'        =>  $rolesNames,
        'allManagersNames'  =>  $allManagersNames,
        'yuristSettings'    =>  $yuristSettings,
        'userFile'          =>  $userFile,
        'townsArray'        =>  $townsArray,
        'allDirections'     =>  $allDirections,
    )); ?>