<?php
/** @var QuestionCategory $recentCategory */
if (empty($recentCategories) || sizeof($recentCategories) == 0) {
    echo "Не найдено ни одной категории";
}
?>

<?php foreach ($recentCategories as $counter => $recentCategory): ?>
    <?php if ($counter % 2 == 0): ?>
        <div class="row">
    <?php endif; ?>

    <div class="col-md-6 vert-margin30">
        <div class="row">
            <div class="col-md-3">
                <?php echo('<img src="' . $recentCategory->getImagePath() . '" alt="' . CHtml::encode($recentCategory->seoTitle) . '" class="img-responsive"/>'); ?>
            </div>
            <div class="col-md-9">
                <?php echo CHtml::link(CHtml::encode($recentCategory->seoTitle), Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl())); ?>
            </div>
        </div>
    </div>

    <?php if ($counter % 2 == 1): ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
<?php if ($counter % 2 == 0): ?>
    </div>
<?php endif; ?>
