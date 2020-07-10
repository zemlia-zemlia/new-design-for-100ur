<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.2020
 * Time: 13:09
 */
?>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_viewArchive',
    'htmlOptions' => ['class' => 'archive-questions__body'],
    'ajaxUpdate' => true,
    'pager' => ['class' => 'GTLinkPager', 'cssFile' => false],
    'pagerCssClass' => 'pagination',
    'template' => '{items} <a href="#" data-per_page="25" class="archive-questions__more-btn">Показать еще 25 вопросов</a> {pager}'
]); ?>
