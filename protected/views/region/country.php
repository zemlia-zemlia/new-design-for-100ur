<?php

use App\models\User;

$this->setPageTitle('Юристы ' . CHtml::encode($country->name) . '. Рейтинг юристов');

Yii::app()->clientScript->registerMetaTag('Найти юриста по специализации или по рейтингу и получить консультацию онлайн.', 'Description');

$this->breadcrumbs = [
    CHtml::encode($country->name),
];
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Главная', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<div class="row">
    <div class="col-sm-3">
        <h2>Выберите регион</h2>

        <ul id="left-menu">
        <?php
        $regionCounter = 0;
        $regionsNumber = sizeof($regions);

        foreach ($regions as $region) {
            echo '<li>';
            echo CHtml::link($region['regionName'], Yii::app()->createUrl('region/view', [
                        'regionAlias' => $region['regionAlias'],
                        'countryAlias' => $region['countryAlias'],
            ]));
            echo '</li>';
        }
        ?>
        </ul>
    </div>
    <div class="col-sm-9">
        <h1 class="vert-margin30">Рейтинг юристов и адвокатов, <?php echo CHtml::encode($country->name); ?></h1>


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
                    <hr />

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
			<div class="col-sm-4">
            <?php if (Yii::app()->user->isGuest || User::ROLE_ROOT == Yii::app()->user->role): ?>
                
                    <div class="grey-panel inside">
                        <h4>Вы специалист в области права?</h4>
                        <p>
                            Для участия в рейтинге нужно пройти нехитрую процедуру регистрации и подтверждения вашей квалификации.
                        </p>
                        <p class="right-align">
                            <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('/user/create', ['role' => User::ROLE_JURIST])); ?>
                        </p>
                    </div>
                
            <?php endif; ?>
			<h4>Новые материалы:</h4>
			<div class="inside">
                <?php
                    $this->widget('application.widgets.RecentCategories.RecentCategories', [
                        'number' => 4,
                    ]);
                ?>
            </div>
        </div>
        </div>
    </div>
</div>

