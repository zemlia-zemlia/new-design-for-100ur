<?php
/** @var QuestionCategory $recentCategory */
?>

<?php if (sizeof($recentCategories) > 0): ?>

    <?php
    if ($title) {
        echo $title;
    }
    ?>

    <?php $columnWidth = 12 / $columns; ?>

    <div class="recent-categories">

    <?php foreach ($recentCategories as $counter => $recentCategory): ?>
        <?php if (0 == $counter % $columns): ?>
            <div class="row">
        <?php endif; ?>

        <div class="col-md-<?php echo $columnWidth; ?> vert-margin30">
            <div class="category-preview-wrapper">
                <a href="<?php echo Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl()); ?>">
                    <?php echo '<img src="' . $recentCategory->getImagePath() . '" alt="' . CHtml::encode($recentCategory->seoH1) . '" class="img-responsive"/>'; ?>
                </a>
                <div class="category-preview-title">
                    <?php echo CHtml::link(CHtml::encode($recentCategory->seoH1), Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl())); ?>
                </div>
            </div>
        </div>

        <?php if ($counter % $columns == $columns - 1): ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (0 != ($counter + 1) % $columns): ?>
        </div>
    <?php endif; ?>
    </div>
<?php endif; ?>