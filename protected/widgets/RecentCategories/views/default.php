<?php
/** @var QuestionCategory $recentCategory */

use App\models\QuestionCategory;

if (empty($recentCategories) || 0 == sizeof($recentCategories)) {
    echo 'Не найдено ни одной категории';
}
?>

<?php
if ($title) {
    echo $title;
}
?>

<div class="recent-categories">

    <?php foreach ($recentCategories as $recentCategory): ?>
        <div class="vert-margin30">
            <div class="category-preview-wrapper">
                <img src="<?php echo $recentCategory->getImagePath(); ?>"
                     alt="<?php echo CHtml::encode($recentCategory->seoH1); ?>" class="img-responsive"/>
                <div class="category-preview-title">
                    <strong>
                        <?php echo CHtml::link(CHtml::encode($recentCategory->seoH1), Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl())); ?>
                    </strong>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>
