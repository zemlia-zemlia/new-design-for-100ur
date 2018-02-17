<?php
$this->setPageTitle(CHtml::encode($country->name) . '. ' . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Каталог Юристов и Адвокатов " . CHtml::encode($country->name), "Description");

$this->breadcrumbs = array(
    CHtml::encode($country->name),
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
        <h2>Выберите регион</h2>
        <?php
        $regionCounter = 0;
        $regionsNumber = sizeof($regions);

        foreach ($regions as $region) {

            


            echo " ";
            echo CHtml::link($region['regionName'], Yii::app()->createUrl('region/view', array(
                        'regionAlias' => $region['regionAlias'],
                        'countryAlias' => $region['countryAlias'],
            )));
            echo " <br />";
        }
        
        ?>
    </div>
    <div class="col-sm-9">
        <h1 class="vert-margin30">Рейтинг юристов и адвокатов, <?php echo CHtml::encode($country->name); ?></h1>


        <div class="row">
            <div class="col-sm-8">
                <?php
                $this->widget('zii.widgets.CListView', array(
                    'dataProvider' => $yuristsDataProvider,
                    'itemView' => 'application.views.yurist._viewLine',
                    'emptyText' => '<div class="text-center vert-margin40 alert alert-info">Не найдено ни одного юриста из этого региона. <br />Рассмотрите других юристов нашего портала</div>',
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
                            'template' => 'list',
                        ));
                        ?>
                    </div>
                <?php endif; ?>

            </div>
            <div class="col-sm-4">
                <div class="grey-panel inside">
                    <h4>Вы специалист в области права?</h4>
                    <p>
                        Для участия в рейтинге нужно пройти нехитрую процедуру регистрации и подтверждения вашей квалификации.
                    </p>
                    <p class="right-align">
                        <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('/user/create', array('role' => User::ROLE_JURIST))); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

