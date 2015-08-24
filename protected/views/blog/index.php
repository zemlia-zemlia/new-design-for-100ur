<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Блог" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Блог',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Блог</h1>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'summaryText'   =>  '',
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