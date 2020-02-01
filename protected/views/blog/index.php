<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$pageTitle = "Новости - новостные и информационные публикации";
if (isset($_GET) && (int)$_GET['Post_page']) {
    $pageNumber = (int)$_GET['Post_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);

Yii::app()->clientScript->registerLinkTag("canonical", null, Yii::app()->createUrl('/blog'));
Yii::app()->clientScript->registerMetaTag("Раздел в котором мы публикуем самые интересные новости и факты произошедшие в стране.", "Description");

$this->breadcrumbs=array(
    'Новости',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('100 Юристов', "/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>
<div class="panel gray-panel">
    <div class="panel-body">
		<h1>Новости</h1>
    </div>
</div>


<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
        'summaryText'   =>  '',
        'ajaxUpdate'    =>  false,
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
