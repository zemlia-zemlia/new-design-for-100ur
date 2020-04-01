<?php
/* @var $this CampaignController */

use App\models\Campaign;

/* @var $model Campaign */
$this->setPageTitle('Новая кампания');

$this->breadcrumbs = [
    'Кабинет покупателя' => ['/buyer'],
    'Новая кампания',
];

?>
<div class="vert-margin20">
<h1>Создание кампании по покупке лидов</h1>
</div>
<?php
    $this->renderPartial('_form', [
        'model' => $model,
        'regions' => $regions,
    ]);
?>