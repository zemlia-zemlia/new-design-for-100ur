
<?php
$this->breadcrumbs = [
        CHtml::encode($user->lastName . ' ' . $user->name) => ['user/view', 'id' => $user->id],
    'Статистика ответов',
];

$this->setPageTitle('Статистика ответов пользователя. ' . Yii::app()->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Статистика ответов пользователя <?php echo CHtml::encode($user->lastName . ' ' . $user->name); ?></h1>
<?php if (!sizeof($statsRows)):?>
    <p>Нет статистики по ответам</p>
<?php else:?>
    
    <table class="table table-bordered">
        <tr>
            <th>Месяц</th>
            <th>Ответов</th>
        </tr>
    <?php foreach ($statsRows as $row):?>
        <tr>
            <td><?php echo $row['month'] . '.' . $row['year']; ?></td>
            <td><?php echo $row['counter']; ?></td>
        </tr>

    <?php endforeach; ?>
    </table>
<?php endif; ?>