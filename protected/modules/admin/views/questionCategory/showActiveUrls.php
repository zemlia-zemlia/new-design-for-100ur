<?php
    $this->setPageTitle("Категории вопросов, отображаемые на сайте. ". Yii::app()->name);
?>

<?php foreach($categories as $category):?>
    <p>
        <?php echo Yii::app()->createUrl('questionCategory/alias', $category->getUrl());?>
    </p>
<?php endforeach; ?>

