<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle(CHtml::encode($model->id) . '. Категории вопросов. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Категории вопросов' => ['index'],
];

$ancestors = $model->ancestors()->findAll();
foreach ($ancestors as $ancestor) {
    $this->breadcrumbs[$ancestor->name] = Yii::app()->createUrl('admin/questionCategory/view', ['id' => $ancestor->id]);
}
$this->breadcrumbs[] = CHtml::encode($model->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/admin/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<h1 class="vert-margin30"><?php echo CHtml::encode($model->name); ?></h1>

<div class="row">
    <div class="col-md-9">
        <!--
        <?php if ($model->description1): ?>
            <div class="vert-margin30">
                <?php echo $model->description1; ?>
            </div>
        <?php endif; ?>
        -->
        <div class="box">
            <div class="box-header">
                <div class="box-title">Вложенные категории:</div>
            </div>
            <div class="box-body">
                <?php $this->renderPartial('_table', ['categoriesArray' => $subCategoriesArray]); ?>
            </div>
        </div>
    </div>


    <div class="col-md-3">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Управление:</div>
            </div>
            <div class="box-body">
                <?php echo CHtml::link('Редактировать категорию', Yii::app()->createUrl('/admin/questionCategory/update', ['id' => $model->id]), ['class' => 'btn btn-block btn-primary']); ?>
                <?php echo CHtml::link('Создать подкатегорию', Yii::app()->createUrl('/admin/questionCategory/create', ['parentId' => $model->id]), ['class' => 'btn btn-block btn-primary']); ?>
                <?php echo CHtml::link('Открыть на сайте', Yii::app()->createUrl('/questionCategory/alias', ['name' => $model->alias]), ['class' => 'btn btn-block btn-default', 'target' => '_blank']); ?>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <div class="box-title">Картинка категории:</div>
            </div>
            <div class="box-body">
                <?php if ($model->image): ?>
                    <?php echo CHtml::image($model->getImagePath(), '', ['class' => 'img-responsive']); ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>









