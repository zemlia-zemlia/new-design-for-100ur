<?php
/* @var $this TownController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Города',
);
$this->pageTitle = "Города. " . Yii::app()->name;

?>

<h1>Топ городов</h1>

<p>
    <?php echo CHtml::link('Все города и поиск', Yii::app()->createUrl('/admin/town/admin'));?>
</p>

<table class="table table-bordered">
    <tr>
        <th>Город</th>
        <th>Вопросов</th>
        <th>Редактирование</th>
    </tr>
<?php foreach ($townsArray as $town):?>

<tr>
    <td>
        <strong><?php echo CHtml::encode($town['name']);?></strong>
    </td>
    <td>
        <?php echo CHtml::encode($town['counter']);?>
    </td>
    <td>
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/town/update', array('id'=>$town['id'])), array('class'=>'btn btn-primary btn-xs'));?>
    </td>
</tr>

<?php endforeach; ?>
</table>