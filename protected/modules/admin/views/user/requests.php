<?php
$this->pageTitle = "Запросы на проверку документов пользователей. " . Yii::app()->name;


$this->breadcrumbs=array(
    'Запросы на проверку',
);


?>
<div  class="vert-margin30">
    <h1>Запросы на проверку документов пользователей</h1>
</div>

<table class="table table-bordered">
<?php foreach ($users as $user):?>

    <tr>
        <td>
            <?php echo CHtml::encode($user['lastName'] . ' ' . $user['name']);?>
        </td>
        <td>
            <?php echo CHtml::link('обработать', Yii::app()->createUrl('admin/user/update', array('id'=>$user['id'])), array('class'=>'btn btn-xs btn-primary'));?>
        </td>
    </tr>

<?php endforeach; ?>
</table>

