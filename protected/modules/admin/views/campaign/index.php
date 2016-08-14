<?php
/* @var $this CampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Кампании',
);

?>

<h1>Кампании</h1>

<div class="right-align">
    <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('/admin/campaign/create'), array('class'=>'btn btn-primary')); ?>
</div>

<table class="table table-bordered">
    
    <thead>
        <tr>
            <th>ID</th>
            <th>Покупатель</th>
            <th>Регион</th>
            <th>Цена</th>
            <th>Баланс</th>
            <th>Отправлено</th>
        </tr>
    </thead>    
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</table>

<?php 
if(!$showInactive) {
    echo CHtml::link('Показать неактивные', $this->createUrl('?show_inactive=true'));
}
?>
