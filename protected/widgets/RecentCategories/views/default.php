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

<div class="recent-categories">

<?php foreach ($recentCategories as $recentCategory): ?>
    <div class="vert-margin30">
        <p>
            <strong>
                <?php echo CHtml::link('<img src="' . $recentCategory->getImagePath() . '"
             alt="' . CHtml::encode($recentCategory->seoH1) . '" class="img-responsive"/>' . CHtml::encode($recentCategory->seoH1), Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl())); ?>
            </strong>
        </p>
    </div>
<?php endforeach; ?>

</div>
