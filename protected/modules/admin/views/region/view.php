<?php
/* @var $this RegionController */
/* @var $model Region */

$pageTitle = 'Юристы и Адвокаты ' . CHtml::encode($model->name) . '.';
Yii::app()->clientScript->registerMetaTag('Каталог и рейтинг Юристов и Адвокатов ' . CHtml::encode($model->name), 'Description');
Yii::app()->clientScript->registerScriptFile('/js/admin/town.js');

$this->setPageTitle($pageTitle . Yii::app()->name);

$this->breadcrumbs = [
    'Регионы' => ['/admin/region'],
    CHtml::encode($model->name),
];
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Юристы и Адвокаты', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 2px;
    }
</style>

<h2 class="vert-margin30"><?php echo CHtml::encode($model->name); ?> (Столица: <?php echo CHtml::encode($model->capital->name); ?>)</h2>


<div class="box">
    <div class="box-body">
        <table class="table table-bordered" style="padding: 1px;">
            <tr>
                <th>Город</th>
                <th>Насел.</th>
                <th>Опис.1</th>
                <th>Опис.2</th>
                <th>Title</th>
                <th>Desc</th>
                <th>Keyw</th>
                <th>Редактирование</th>
                <th>Цена</th>
            </tr>
            <?php foreach ($townsArray as $town): ?>
                <tr <?php if ($town['isCapital']): ?>class="success"<?php endif; ?>>
                    <td>
                        <strong><?php echo CHtml::encode($town['name']); ?></strong>

                        <?php
                        $distanceFromCapital = $model->getRangeFromCenter($town['lat'], $town['lng']);
                        if ($distanceFromCapital >= 0) {
                            echo $distanceFromCapital . ' км.';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $town['size']; ?>
                    </td>
                    <td>
        <span class='
        <?php echo ($town['hasDesc1'] > 0) ? 'glyphicon glyphicon-ok' : ''; ?>
        '></span>
                    </td>
                    <td>
        <span class='
        <?php echo ($town['hasDesc2'] > 0) ? 'glyphicon glyphicon-ok' : ''; ?>
        '></span>
                    </td>
                    <td>
        <span class='
        <?php echo ($town['hasSeoTitle'] > 0) ? 'glyphicon glyphicon-ok' : ''; ?>
        '></span>
                    </td>
                    <td>
        <span class='
        <?php echo ($town['hasSeoDescription'] > 0) ? 'glyphicon glyphicon-ok' : ''; ?>
        '></span>
                    </td>
                    <td>
        <span class='
        <?php echo ($town['hasSeoKeywords'] > 0) ? 'glyphicon glyphicon-ok' : ''; ?>
        '></span>
                    </td>
                    <td>
                        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/town/update', ['id' => $town['id']]), ['class' => 'btn btn-primary btn-xs']); ?>
                    </td>
                    <td style="max-width: 50px">
                        <div>
                            <?php echo CHtml::textField('buyPrice', MoneyFormat::rubles($town['buyPrice']), [
                                'class' => 'form-control town-buy-price input-sm input-xs',
                                'data-id' => $town['id'],
                                'style' => 'max-width:50px',
                            ]); ?>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>
        </table>

    </div>
</div>
