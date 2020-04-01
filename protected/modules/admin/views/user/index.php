<?php
/* @var $this UserController */

use App\models\User;

/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Пользователи. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Пользователи',
];

?>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px;
    }
</style>

    <h3>Пользователи: <?php echo $roleName; ?> <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
            <?php echo CHtml::link('Добавить', Yii::app()->createUrl('/admin/user/create'), ['class' => 'btn btn-success']); ?>
        <?php endif; ?>
    </h3>


<div class="vert-margin20">
    <?php echo CHtml::link('Пользователи (клиенты)', Yii::app()->createUrl('admin/user/index', ['role' => User::ROLE_CLIENT])); ?>
    &nbsp;&nbsp;
    <?php echo CHtml::link('Юристы', Yii::app()->createUrl('admin/user/index', ['role' => User::ROLE_JURIST])); ?>
    &nbsp;&nbsp;
    <?php echo CHtml::link('Секретари', Yii::app()->createUrl('admin/user/index', ['role' => User::ROLE_SECRETARY])); ?>
    &nbsp;&nbsp;
    <?php echo CHtml::link('Покупатели', Yii::app()->createUrl('admin/user/index', ['role' => User::ROLE_BUYER])); ?>
    &nbsp;&nbsp;
    <?php echo CHtml::link('Контент-менеджеры', Yii::app()->createUrl('admin/user/index', ['role' => User::ROLE_EDITOR])); ?>
    &nbsp;&nbsp;
    <?php echo CHtml::link('Вебмастера', Yii::app()->createUrl('admin/user/index', ['role' => User::ROLE_PARTNER])); ?>
    &nbsp;&nbsp;
</div>
<div class="box">
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <?php if (User::ROLE_JURIST == $role): ?>
                <th>Посл. акт.</th>
            <?php endif; ?>
            <th>Город</th>
            <th>Email</th>
            <th>Телефон</th>
            <?php if (User::ROLE_BUYER == $role): ?>
                <th>
                    Камп.
                </th>
            <?php endif; ?>
            <th>Редактировать</th>
        </tr>
        </thead>

        <?php $this->widget('zii.widgets.CListView', [
            'dataProvider' => $usersDataProvider,
            'itemView' => '_viewTable',
            'emptyText' => 'Не найдено ни одного пользователя',
            'summaryText' => 'Показаны пользователи с {start} до {end}, всего {count}',
            'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
        ]); ?>
    </table>
</div>