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

Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('/blog'));


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
<div class="panel">
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


<h3>При поддержке</h3>

<div class="panel">
    <div class="panel-body">
	    <div class="row">
            <div class="col-md-6 col-sm-6 center-align">
                <img class="img-responsive center-block" alt="При поддержке правительства РФ" src="/pics/pravitelstvo.png">
                <p class="center-align">Правительство РФ
                </p>
            </div>

            <div class="col-md-6 col-sm-6 center-align"> 
                <img class="img-responsive center-block" alt="При поддержке Министерства Юстиции" src="/pics/minyust.png"> 
                <p class="center-align">Министерство Юстиции</p>
            </div>
        </div>
    </div>
</div>