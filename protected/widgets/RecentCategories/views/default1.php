<?php
/** @var QuestionCategory $recentCategory */
if (empty($recentCategories) || sizeof($recentCategories) == 0) {
    echo "Не найдено ни одной категории";
}
?>

<?php
if ($title) {
    echo $title;
}
?>

<?php $columnWidth = 12 / $columns; ?>

<div class="recent-categories">

    <?php foreach ($recentCategories as $counter => $recentCategory): ?>
        <?php if ($counter % $columns == 0): ?>
            <div class="row">
        <?php endif; ?>

        <div class="col-md-<?php echo $columnWidth; ?> vert-margin30">
            <div>
                <a href="<?php echo Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl()); ?>">
                    <?php echo('<img src="' . $recentCategory->getImagePath() . '" alt="' . CHtml::encode($recentCategory->seoH1) . '" class="img-responsive"/>'); ?>
                </a>
            </div>
            <div>
                <?php echo CHtml::link(CHtml::encode($recentCategory->seoH1), Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl())); ?>
            </div>
        </div>

        <?php if ($counter % $columns == $columns - 1): ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($counter % $columns != 0): ?>
        </div>
    <?php endif; ?>
</div>
