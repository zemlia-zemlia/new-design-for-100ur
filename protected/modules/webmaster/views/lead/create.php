<?php
/* @var $this LeadController */
/* @var $model Lead */
/* @var $mySources array */

$this->setPageTitle('Новый лид ' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/scripts.js', CClientScript::POS_END);

$this->breadcrumbs = [
    'Лиды' => ['index'],
    'Добавление',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/webmaster'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

    <div class="vert-margin20"></div>

<?php if (!empty($apiResult)): ?>
    <h2>Результат запроса к API:</h2>
    <textarea class="form-control" rows="10">
        <?php print_r($apiResult); ?>
    </textarea>
<?php endif; ?>

<?php if (sizeof($mySources) == 0) : ?>
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-info">
                <h4>Ой...</h4>
                <p>
                    Форма ручного добавления лида будет доступна когда вы добавите хотябы один <a
                            href="<?= Yii::app()->createUrl('/webmaster/source/create/') ?>">источник лидов</a>
                </p>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Добавляем новый лид</div>
                </div>
                <div class="box-body">
                    <?php echo $this->renderPartial('_form', ['model' => $model]); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
<?php endif; ?>