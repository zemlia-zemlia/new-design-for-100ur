<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover">
    <tr>
        <th>Название</th>
        <th>Описание</th>
        <th>Добавить</th>
    </tr>
    <?php foreach ($files as $file): ?>

        <tr>
            <td>

                <?php echo CHtml::link(CHtml::encode($file->name), array('/admin/docs/update?id=' . $file->id)); ?></strong>

            </td>

            <td class="text-center">
                <?php echo CHtml::encode($file->description); ?>
            </td>
            <td>
                <?php echo CHtml::link("+", array('/admin/docs/update/?id=' .  $file->id)); ?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>