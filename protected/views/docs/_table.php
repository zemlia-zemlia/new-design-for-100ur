<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover">
    <tr>
        <th>Название категории</th>
        <th>Описание</th>
        <th>Ред.</th>
    </tr>
    <?php foreach ($categories as $category):
        if ($category->active):?>

        <tr>
            <td class="lead">
               <?php echo CHtml::link(CHtml::encode($category->name), array('fileCategory/view', 'id' => $category->id)); ?>
            </td>
            <td class="small">
                <?php echo CHtml::encode($category->description); ?>
            </td>

            <td>
                <?php echo CHtml::link("Ред.", array('/admin/file-category/update/?id=' . $category->id), array('class' => 'btn btn-xs btn-default')); ?>
            </td>
        </tr>

    <?php
    endif;
  endforeach; ?>
</table>