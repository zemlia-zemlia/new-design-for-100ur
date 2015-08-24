<?php
/* @var $this CodecsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Codecs',
);

?>

<h1>Кодексы РФ</h1>

<div class="panel">
    <div class="panel-body">
        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProvider,
                'itemView'=>'_viewCat',
                'emptyText'     =>  '',
                'summaryText'   =>  '',
                'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
        )); ?>
    </div>
</div>
