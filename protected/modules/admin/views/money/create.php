<?php
/* @var $this MoneyController */
/* @var $model Money */

$this->setPageTitle("Новая запись в кассе. ". Yii::app()->name);

$js = <<<JS
$('input[type=radio]').on('change', function() {
if($(this).attr('id') == 'Money_type_1') {
    $('#expence').removeClass('hidden');
     $('#income').addClass('hidden');
}
else{
     $('#income').removeClass('hidden');
     $('#expence').addClass('hidden');
}
})
JS;


Yii::app()->clientScript->registerScript('myjquery', $js );


?>

<h1>Новая запись кассы</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>