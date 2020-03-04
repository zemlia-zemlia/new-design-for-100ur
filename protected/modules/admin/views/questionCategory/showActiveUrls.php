<?php
    $this->setPageTitle('Категории вопросов, отображаемые на сайте. ' . Yii::app()->name);
?>
<h1>Урлы всех категорий</h1>
<?php foreach ($categories as $category):?>
    <p>
        <?php echo Yii::app()->createUrl('questionCategory/alias', $category->getUrl()); ?>
    </p>
<?php endforeach; ?>

