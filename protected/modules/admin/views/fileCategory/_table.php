<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover">
    <tr>
        <th>Название категории</th>
        <th>Описание</th>

    </tr>
    <?php foreach ($categories as $category):?>
        <tr>
            <td>
               <strong><a href="#" class="catLink"  id="<?php echo $category->id; ?>"><?php echo $category->name; ?></a> </strong>
            </td>
            <td>
                <?php echo CHtml::encode($category->description); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>