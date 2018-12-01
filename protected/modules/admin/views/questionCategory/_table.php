<style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px 1px;
    }
</style>

<table class="table table-bordered table-hover">
    <tr>
        <th>Название категории</th>
        <th>Текст</th>
        <th>H1</th>
        <th>Title</th>
        <th>Descr.</th>
        <th>Keyw.</th>
        <th>Напр</th>
        <th>Подк.</th>
        <th>Ред.</th>
    </tr>
    <?php foreach ($categoriesArray as $rootId => $rootCategory): ?>

        <tr>
            <td>

                <?php echo CHtml::link(CHtml::encode($rootCategory['name']), array('view', 'id' => $rootId)); ?></strong>
                (id <?php echo $rootId; ?>)
            </td>
            <td><?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'description1'); ?></td>
            <td>
                <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoH1'); ?>
            </td>
            <td>
                <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoTitle'); ?>
            </td>
            <td>
                <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoDescription'); ?>
            </td>
            <td>
                <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoKeywords'); ?>
            </td>
            <td>
                <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'isDirection'); ?>
            </td>
            <td class="text-center">
                <?php echo CHtml::link("+", array('create', 'parentId' => $rootId), array('class' => 'btn btn-xs btn-primary')); ?>
            </td>
            <td>
                <?php echo CHtml::link("Ред.", array('update', 'id' => $rootId)); ?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>