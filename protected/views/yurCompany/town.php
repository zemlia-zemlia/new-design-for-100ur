<?php
$pageTitle = "Юридические компании города " . $town->name;
        
if(isset($_GET) && (int)$_GET['YurCompany_page']) {
    $pageNumber = (int)$_GET['YurCompany_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= '. Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}

$pageTitle.= ' ' . Yii::app()->name;

$this->setPageTitle($pageTitle);

Yii::app()->clientScript->registerMetaTag('Юридические компании и фирмы города ' . $town->name . ', оказывающие услуги в сфере права. ' . Yii::app()->name, 'description');
//Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('/yurCompany/town', array('alias'=>$town->alias)));

$this->breadcrumbs=array(
	'Юридические фирмы'=>array('/company'),
	CHtml::encode($town->name),
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Вопрос юристу',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>


        
<h1>Юридические компании города <?php echo CHtml::encode($town->name); ?></h1>

<div class="container-fluid">
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $dataProvider,
            'itemView'      =>  '_view',
            'emptyText'     =>  'Не найдено ни одной фирмы',
            'ajaxUpdate'    =>  false,
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>
</div>

