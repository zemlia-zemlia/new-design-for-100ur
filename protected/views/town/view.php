<?php
/* @var $this TownController */

use App\models\Town;
use App\models\User;

/* @var $model Town */

$pageTitle = $model->createPageTitle();

if (isset($_GET) && (int) $_GET['Question_page'] && $dataProvider->pagination) {
    $pageNumber = (int) $_GET['Question_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= '. Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);
Yii::app()->clientScript->registerMetaTag($model->createPageDescription(), 'description');
Yii::app()->clientScript->registerMetaTag($model->createPageKeywords(), 'keywords');
Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('town/alias', [
    'name' => $model->alias,
    'countryAlias' => $model->country->alias,
    'regionAlias' => $model->region->alias,
]));

$this->breadcrumbs = [
    CHtml::encode($model->country->name) => [
        'region/country',
        'countryAlias' => $model->country->alias,
    ],
    CHtml::encode($model->region->name) => [
        'region/view',
        'regionAlias' => $model->region->alias,
        'countryAlias' => $model->country->alias,
    ],
    $model->name,
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
        <?php if (is_array($closeTowns) && sizeof($closeTowns)): ?>
            <h2>Соседние города</h2>
            <ul id="left-menu">
                <?php foreach ($closeTowns as $town): ?>
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
        <h1 class="vert-margin30">Рейтинг юристов и адвокатов: <?php echo CHtml::encode($model->name); ?>
            (<?php echo CHtml::encode($model->region->name); ?>)</h1>


        <div class="row">
            <div class="col-sm-8">
                <?php
                $this->widget('zii.widgets.CListView', [
                    'dataProvider' => $yuristsDataProvider,
                    'itemView' => 'application.views.yurist._viewLine',
                    'emptyText' => '<div class="text-center vert-margin40 alert alert-info">Не найдено ни одного юриста из этого города. <br />Рассмотрите других юристов нашего портала</div>',
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
                <div class="hidden-xs">
                <hr/>

                <?php if ($model->description1): ?>
                    <div class="vert-margin30">
                        <?php echo $model->description1; ?>
                    </div>
                <?php endif; ?>

                <?php if ($model->description2): ?>
                    <div class="vert-margin30">
                        <?php echo $model->description2; ?>
                    </div>
                <?php endif; ?>
                </div>
                <?php if (Yii::app()->user->isGuest || User::ROLE_CLIENT == Yii::app()->user->role): ?>
                    <div class="vert-margin30 blue-block inside">
                        <div class="row">
                            <div class="col-sm-8 center-align">
                                <h3>Ваш вопрос требует составления документа?</h3>
                                <p>Доверьте это опытным юристам</p>
                            </div>
                            <div class="col-sm-4 center-align">
                                <p></p>
                                <?php echo CHtml::link('Заказать документ', Yii::app()->createUrl('question/docs'), ['class' => 'yellow-button']); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <div class="col-sm-4">
                <?php if (Yii::app()->user->isGuest || User::ROLE_ROOT == Yii::app()->user->role): ?>
                    <div class="grey-panel inside">
                        <h4>Вы специалист в области права?</h4>
                        <p>
                            Для участия в рейтинге нужно пройти нехитрую процедуру регистрации и подтверждения вашей
                            квалификации.
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

