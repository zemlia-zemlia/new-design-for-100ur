<?php
/** @var QuestionCategory $recentCategory */
if (empty($recentCategories) || sizeof($recentCategories) == 0) {
    echo "Не найдено ни одной категории";
}
?>

<?php foreach ($recentCategories as $recentCategory): ?>
    <div class="vert-margin30">
        <div class="col-md-6 vert-margin30">
            <div class="row">
                <div class="col-md-3">
                     <?php echo ('<img src="' . $recentCategory->getImagePath() . '" alt="' . CHtml::encode($recentCategory->seoTitle) . '" class="img-responsive"/>'); ?>
                </div>
                <div class="col-md-9">
                    <?php echo CHtml::link(CHtml::encode($recentCategory->seoTitle), Yii::app()->createUrl('questionCategory/alias', array('name' => $recentCategory->alias))); ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
