<?php
/* @var $this CategoryController */

use App\models\User;

/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Новости' . ' | ' . Yii::app()->name);

$this->breadcrumbs = [
    'Новости',
];
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('CRM', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>


<h1>Новости</h1>

<div class="right-align">
    <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
        <?php echo CHtml::link('Добавить новость', Yii::app()->createUrl('/admin/post/create'), ['class' => 'btn btn-primary']); ?>
    <?php endif; ?>
</div>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => 'application.modules.admin.views.post._view',
        'summaryText' => '',
        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
]); ?>

