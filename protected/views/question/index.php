<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerLinkTag("alternate","application/rss+xml","http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question/rss'));
Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question'));

$pageTitle = "Вопросы юристам. ";
if(isset($_GET) && (int)$_GET['Question_page']) {
    $pageNumber = (int)$_GET['Question_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle . Yii::app()->name);


$this->breadcrumbs=array(
	'Вопросы и ответы',
);

?>

<div class="vert-margin30">
<h1><?php echo $pageTitle;?></h1>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Юристы пока не ответили',
        'summaryText'   =>  '',
        'ajaxUpdate'    =>  false,
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>

