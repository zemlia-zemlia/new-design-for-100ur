<?php
$title = 'Отзывы на юриста ' . CHtml::encode($yurist->name . ' ' . $yurist->lastName);

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

<h1><?php echo $title;?></h1>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $testimonialsDataProvider,
    'itemView' => 'application.views.comment._viewUser',
    'emptyText' => 'Не найдено ни одного отзыва',
    'summaryText' => '',
    'pager' => array('class' => 'GTLinkPager'), //we use own pager with russian words
));
?>
