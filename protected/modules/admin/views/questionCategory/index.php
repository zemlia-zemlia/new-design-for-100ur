<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Категории вопросов. " . Yii::app()->name);


$this->breadcrumbs = array(
    'Категории справочных материалов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));


?>

<div class="box">
    <div class="box-body">
        <h2>Категории вопросов
            <?php echo CHtml::link('Добавить категорию', Yii::app()->createUrl('/admin/questionCategory/create'), array('class' => 'btn btn-primary')); ?>
            <?php echo CHtml::link('Ссылки на активные категории', Yii::app()->createUrl('/admin/questionCategory/showActiveUrls/'), array('class' => 'btn btn-default')); ?>
            <?php echo CHtml::link('Иерархия всего раздела категорий', Yii::app()->createUrl('/admin/questionCategory/indexHierarchy/'), array('class' => 'btn btn-default')); ?>

        </h2>

        <?php if ($totalCategoriesCount > 0): ?>
            <?php
            $partWithDescription = ($totalCategoriesCount - $emptyCategoriesCount) / $totalCategoriesCount;
            ?>

            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100"
                     style="width: <?php echo $partWithDescription * 100; ?>%;">
                    С описанием: <?php echo($totalCategoriesCount - $emptyCategoriesCount); ?>
                    из <?php echo $totalCategoriesCount; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="box">
    <div class="box-body">
        <?php $this->renderPartial('_table', ['categoriesArray' => $categoriesArray]); ?>
    </div>
</div>
