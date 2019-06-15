<?php
$title = 'Отзыв на юриста ' . CHtml::encode($yurist->name . ' ' . $yurist->lastName);

$this->setPageTitle($title . '. ' . Yii::app()->name);

$this->breadcrumbs = [
    CHtml::encode($yurist->name . ' ' . $yurist->lastName)  => ['user/view', 'id' => $yurist->id],
];
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>
    <h1><?php echo $title; ?></h1>
<?php
$this->renderPartial('application.views.comment._form', array(
    'type' => Comment::TYPE_USER,
    'objectId' => $yurist->id,
    'model' => $commentModel,
    'hideRating' => false,
    'parentId' => 0,
    'buttonText' => 'Оставить отзыв',
    'showTitle' => true,
));

?>