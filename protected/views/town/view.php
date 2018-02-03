<?php
/* @var $this TownController */
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
Yii::app()->clientScript->registerLinkTag("canonical", NULL, Yii::app()->createUrl('town/alias', array(
            'name' => $model->alias,
            'countryAlias' => $model->country->alias,
            'regionAlias' => $model->region->alias,
)));


$this->breadcrumbs = array(
    CHtml::encode($model->country->name) => array(
        'region/country',
        'countryAlias' => $model->country->alias,
    ),
    CHtml::encode($model->region->name) => array(
        'region/view',
        'regionAlias' => $model->region->alias,
        'countryAlias' => $model->country->alias,
    ),
    $model->name,
);
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>

<div class="row">
    <div class="col-sm-3">
        <?php if (is_array($closeTowns) && sizeof($closeTowns)): ?>
            <h2>Соседние города</h2>
            <?php foreach ($closeTowns as $town): ?>
                <div>
                    <?php
                    echo CHtml::link($town->name, Yii::app()->createUrl('town/alias', array(
                                'name' => $town->alias,
                                'countryAlias' => $town->country->alias,
                                'regionAlias' => $town->region->alias,
                    )));
                    ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="col-sm-9">
        <h1 class="vert-margin30">Юристы и Адвокаты г. <?php echo CHtml::encode($model->name); ?> (<?php echo CHtml::encode($model->region->name); ?>)</h1>


        <div class="row">
            <div class="col-sm-8">
                <?php
                $this->widget('zii.widgets.CListView', array(
                    'dataProvider' => $yuristsDataProvider,
                    'itemView' => 'application.views.yurist._viewLine',
                    'emptyText' => '<div class="text-center vert-margin40 alert alert-info">Не найдено ни одного юриста из этого города. <br />Рассмотрите других юристов нашего портала</div>',
                    'summaryText' => '',
                    'pager' => array('class' => 'GTLinkPager'),
                    'viewData' => array('onPage' => $yuristsDataProvider->getItemCount()),
                ));
                ?>

                <?php if ($yuristsDataProvider->itemCount): ?>
                    <hr />

                <?php else: ?>
                    <div class='vert-margin30'>
                        <?php
                        // выводим виджет с топовыми юристами
                        $this->widget('application.widgets.TopYurists.TopYurists', array(
                            'cacheTime' => 300,
                            'limit' => 6,
                            'template'  =>  'list',
                        ));
                        ?>
                    </div>
                <?php endif; ?>

            </div>
            <div class="col-sm-4">
                <div class="grey-panel inside">
                    <h4>Вы специалист в области права?</h4>
                    <p>
                        Вы можете консультировать наших пользователей онлайн, пройдя нехитрую процедуру 
                        регистрации и подтверждения вашей квалификации.
                    </p>
                    <p class="right-align">
                        <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('/user/create', array('role' => User::ROLE_JURIST))); ?>
                    </p>
                </div>
            </div>
        </div>


        <?php if (sizeof($model->companies)): ?>

            <h3 class="vert-margin20">Юридические компании города</h3>
            <div class="container-fluid">
                <div class="row">
                    <?php
                    $companyCounter = 0;
                    $companyLimit = 6;
                    ?>
                    <?php foreach ($model->companies as $company): ?>
                        <?php
                        $companyCounter++;
                        if ($companyCounter > $companyLimit)
                            break;
                        ?>
                        <?php if ($companyCounter % 2 == 1) echo "<div class='row'>"; ?>

                        <div class="col-md-2">
                            <img src="<?php echo $company->getPhotoUrl('thumb'); ?>" alt="<?php echo CHtml::encode($company->name); ?>" class="img-responsive" />
                        </div>
                        <div class="col-md-4">
                            <?php echo CHtml::link(CHtml::encode($company->name), Yii::app()->createUrl('yurCompany/view', array('id' => $company->id))); ?>
                        </div>
                        <?php if ($companyCounter % 2 == 0) echo "</div>"; ?>
                    <?php endforeach; ?>
                    <?php if ($companyCounter % 2 == 1 && $companyCounter != $companyLimit + 1) echo "</div>"; ?>
                </div>
            </div>
            <hr />
        <?php endif; ?>



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

        <?php if (Yii::app()->user->isGuest || Yii::app()->user->role == User::ROLE_CLIENT): ?>        
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
</div>

