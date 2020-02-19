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
    <?php foreach ($categories as $category):
        if ($category->active):?>

        <tr>
            <td>

                <?php echo CHtml::link(CHtml::encode($category->name), ['fileCategory/view', 'id' => $category->id]); ?></strong>

            </td>
            <td>

                <?php echo CHtml::encode($category->description); ?></strong>

            </td>

            <td class="text-center">
                <?php echo CHtml::link('+', ['/admin/file-category/create/?id='.$category->id], ['class' => 'btn btn-xs btn-primary']); ?>
            </td>
            <td>
                <?php echo CHtml::link('Ред.', ['/admin/file-category/update/?id='.$category->id]); ?>
            </td>
        </tr>

    <?php
    endif;
  endforeach; ?>
</table>