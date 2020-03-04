<?php
$this->setPageTitle(CHtml::encode($model->longtitle) . '. Кодексы РФ. ' . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag($model->introtext, 'description');

$this->breadcrumbs = [
    'Кодексы РФ' => ['/codecs'],
];

$parents = $model->getParents();

foreach ($parents as $parentPath => $parentTitle) {
    $this->breadcrumbs += [$parentTitle => [$parentPath]];
}

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('Консультация юриста', '/'),
        'separator' => ' &rarr; ',
        'links' => $this->breadcrumbs,
     ]);
?>


        <h1 class="header-block header-block-light-grey vert-margin30"><?php echo CHtml::encode($model->longtitle); ?></h1>


        <?php foreach ($model->children as $child):?>
            <p>    
            <?php
                $realPath = str_replace('|', '/', $child->path);
                echo CHtml::link(
                    CHtml::encode($child->longtitle),
                    Yii::app()->createUrl($realPath),
                    ['class' => 'codecs-link']
                );
            ?>
            </p>
        <?php endforeach; ?>



