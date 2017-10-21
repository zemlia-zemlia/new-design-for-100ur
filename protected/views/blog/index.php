<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$pageTitle = "Блог юридического портала. ";
if(isset($_GET) && (int)$_GET['Post_page']) {
    $pageNumber = (int)$_GET['Post_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle . Yii::app()->name);

Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('/blog'));
Yii::app()->clientScript->registerMetaTag("Блог портала 100 Юристов", "Description");

$this->breadcrumbs=array(
	'Блог портала 100 Юристов',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>
<div class="panel gray-panel">
    <div class="panel-body">
		<h1>Блог юридического портала "100 Юристов"</h1>
    </div>
</div>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'summaryText'   =>  '',
        'ajaxUpdate'    =>  false,
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
