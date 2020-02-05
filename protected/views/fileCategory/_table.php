<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover">
    <tr>
        <th>Название категории</th>
        <th>Описание</th>
        <th>Подк.</th>
        <th>Ред.</th>
    </tr>
    <?php foreach ($categories as $category):?>

        <tr>
            <td>

                <?php echo CHtml::link(CHtml::encode($category->name), array('fileCategory/view', 'id' => $category->id)); ?></strong>

            </td>
            <td>

                <?php echo CHtml::encode($category->description); ?></strong>

            </td>

            <td class="text-center">
                <?php echo CHtml::link("+", array('/admin/file-category/create/?id=' . $category->id), array('class' => 'btn btn-xs btn-primary')); ?>
            </td>
            <td>
                <?php echo CHtml::link("Ред.", array('/admin/file-category/update/?id=' . $category->id)); ?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>