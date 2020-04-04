<?php
/* @var $this CategoryController */

use App\models\Postcategory;

/* @var $model Postcategory */

$this->setPageTitle(CHtml::encode($model->title) . ' | Блог' . ' | ' . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->description), 'Description');

$this->breadcrumbs = [
    'Блог' => ['/blog'],
    CHtml::encode($model->title),
];
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('Консультация юриста', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>
<div class="row">
    <div class="col-md-8">
        <h1><?php echo CHtml::encode($model->title); ?></h1>
        
        <div class="category-description vert-margin40">
            <?php echo nl2br(CHtml::encode($model->description)); ?>
        </div>

        <?php if ($postsDataProvider):?>
        <?php $this->widget('zii.widgets.CListView', [
                'dataProvider' => $postsDataProvider,
                'itemView' => 'application.views.post._view',
                'emptyText' => 'Не найдено ни одного поста. Ваш может стать первым!',
                'summaryText' => '',
                'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
        ]); ?>
        <?php endif; ?>

    </div>
    <div class="col-md-4">
   
        <div class="vert-margin40 rounded side-block">
            <h3>Популярные посты в этой категории</h3>
                <?php
                // выводим виджет с популярными постами
                $this->widget('application.widgets.posts.Posts', [
                    'category' => $model->id,
                ]);
            ?>
        </div>
    </div>
</div>