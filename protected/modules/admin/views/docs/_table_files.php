<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover">
    <tr>
        <th>Название</th>
        <th>Описание</th>
        <th>Дата загрузки</th>
        <th>Колво скачиваний</th>
        <th>Ред.</th>
    </tr>
    <?php foreach ($files as $file): ?>

        <tr>
            <td>
                <a href="<?= Yii::app()->createUrl('/admin/docs/update', ['id' => $file->id]) ?>"><?= CHtml::encode($file->name) ?></a>


            </td>

            <td class="text-center">
                <?php echo CHtml::encode($file->description); ?>
            </td>
            <td><?php echo CustomFuncs::niceDate(date('Y-m-d H:i:s', $file->uploadTs)); ?></td>
            <td><?php echo $file->downloads_count; ?></td>
            <td>
                <a href="<?= Yii::app()->createUrl('/admin/docs/update', ['id' => $file->id]) ?>">Ред.</a>
            </td>
        </tr>

    <?php endforeach; ?>
</table>