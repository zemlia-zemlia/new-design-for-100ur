<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>Название</th>
        <th>Описание</th>
        <th>Загружен</th>
        <th><i class="fa fa-download" aria-hidden="true"></i></th>
        <th>Ред.</th>
    </tr>
    <?php foreach ($files as $file): ?>

        <tr>
            <td class="lead">
                <?php echo CHtml::link(CHtml::encode($file->name), array('/admin/docs/update?id=' . $file->id)); ?></strong>
            </td>
            <td>
                <?php echo CHtml::encode($file->description); ?>
            </td>
            <td><?= CustomFuncs::niceDate(date('Y-m-d H:i:s', $file->uploadTs)) ?></td>
            <td><?= $file->downloads_count ?></td>
            <td>
                <?php echo CHtml::link("Ред.", array('/admin/docs/update/?id=' .  $file->id)); ?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>