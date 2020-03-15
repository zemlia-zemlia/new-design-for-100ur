<?php

use App\helpers\DateHelper;

$monthsNames = DateHelper::getMonthsNames();

$pageTitle = 'Архив вопросов за ' . $monthsNames[$month] . ' ' . $year . ' года. ';
if (isset($_GET) && (int) $_GET['Question_page']) {
    $pageNumber = (int) $_GET['Question_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);
Yii::app()->clientScript->registerMetaTag('Ответы юристов и адвокатов. ' . $pageTitle, 'Description');
Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('/question/archive', ['date' => $year . '-' . $month]));

?>
<div class="flat-panel">
        <h1 class="vert-margin20">Архив вопросов за <?php echo $monthsNames[$month] . ' ' . $year; ?> года</h1>

        <div class="inside">
        <div class="row vert-margin20">
        <?php foreach ($datesArray as $monthArchive):?>
            <div class="col-md-2 text-center">
                <?php if ($monthArchive != $month):?>
                    <?php echo CHtml::link($monthsNames[$monthArchive], Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $monthArchive])); ?> 
                <?php else:?>
                    <span class="text-muted"><?php echo $monthsNames[$monthArchive]; ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>

        <?php $this->widget('zii.widgets.CListView', [
                'dataProvider' => $dataProvider,
                'itemView' => '_viewArchive',
                'summaryText' => '',
                'ajaxUpdate' => false,
                'pager' => ['class' => 'GTLinkPager'],
        ]); ?>
    </div>
</div>
