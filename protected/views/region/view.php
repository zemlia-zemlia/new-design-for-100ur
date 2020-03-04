<?php
/* @var $this RegionController */
/* @var $model Region */

$pageTitle = 'Юристы и Адвокаты ' . CHtml::encode($model->name) . '.';
Yii::app()->clientScript->registerMetaTag('Каталог и рейтинг Юристов и Адвокатов ' . CHtml::encode($model->name), 'Description');

$this->setPageTitle($pageTitle);

$this->breadcrumbs = [
    CHtml::encode($model->country->name) => ['/region/country', 'countryAlias' => $model->country->alias],
    CHtml::encode($model->name),
];
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>



<div class="row">
    <div class="col-sm-3">
        <h2>Выберите город</h2>
        <?php if (is_array($model->towns) && sizeof($model->towns)): ?>

                <ul id="left-menu">
                <?php
                $townsCounter = 0;
                $townsNumber = sizeof($model->towns);
                ?>
                <?php foreach ($model->towns as $town): ?>

                    <li>
                        <?php
                        echo CHtml::link($town->name, Yii::app()->createUrl('town/alias', [
                                    'name' => $town->alias,
                                    'countryAlias' => $town->country->alias,
                                    'regionAlias' => $town->region->alias,
                        ]));
                        ?>
                    </li>
                <?php endforeach; ?>
                </ul>
        <?php endif; ?>
    </div>



    <div class="col-sm-9">
        <h1 class="vert-margin30">Рейтинг юристов и адвокатов: <?php echo CHtml::encode($model->name); ?></h1>

        <div class="row">
            <div class="col-sm-8">
                <?php
                $this->widget('zii.widgets.CListView', [
                    'dataProvider' => $yuristsDataProvider,
                    'itemView' => 'application.views.yurist._viewLine',
                    'emptyText' => '<div class="text-center vert-margin40 alert alert-info">Не найдено ни одного юриста из этого региона. <br />Рассмотрите других юристов нашего портала</div>',
                    'summaryText' => '',
                    'pager' => ['class' => 'GTLinkPager'],
                    'viewData' => ['onPage' => $yuristsDataProvider->getItemCount()],
                ]);
                ?>

                <?php if ($yuristsDataProvider->itemCount): ?>
                    

                <?php else: ?>
                    <div class='vert-margin30'>
                        <?php
                        // выводим виджет с топовыми юристами
                        $this->widget('application.widgets.TopYurists.TopYurists', [
                            'cacheTime' => 300,
                            'limit' => 6,
                            'template' => 'list',
                        ]);
                        ?>
                    </div>
                <?php endif; ?>

            </div>
            <?php if (Yii::app()->user->isGuest || User::ROLE_ROOT == Yii::app()->user->role): ?>
            <div class="col-sm-4">
                <div class="grey-panel inside">
                    <h4>Вы специалист в области права?</h4>
                    <p>
                        Для участия в рейтинге нужно пройти нехитрую процедуру регистрации и подтверждения вашей квалификации.
                    </p>
                    <p class="right-align">
                        <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('/user/create', ['role' => User::ROLE_JURIST])); ?>
                    </p>
                </div>

				<h4>Новые материалы:</h4>
                <div class="inside">
                <?php
                    $this->widget('application.widgets.RecentCategories.RecentCategories', [
                        'number' => 4,
                    ]);
                ?>
                </div>

            </div>
            <?php endif; ?>
        </div>
        <hr />
    </div>
</div>

