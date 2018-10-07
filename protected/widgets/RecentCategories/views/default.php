<?php
/** @var QuestionCategory $recentCategory */
if (empty($recentCategories) || sizeof($recentCategories) == 0) {
    echo "Не найдено ни одной категории";
}
?>

<?php foreach ($recentCategories as $recentCategory): ?>
    <div class="vert-margin30">
        <p>
            <strong>
                <?php echo CHtml::link('<img src="' . $recentCategory->getImagePath() . '"
             alt="' . CHtml::encode($recentCategory->seoTitle) . '" class="img-responsive"/>' . CHtml::encode($recentCategory->seoTitle), Yii::app()->createUrl('questionCategory/alias', array('name' => $recentCategory->alias))); ?>
            </strong>
        </p>
    </div>
<?php endforeach; ?>
