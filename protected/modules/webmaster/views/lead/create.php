<?php
/* @var $this LeadController */
/* @var $model Lead */

$this->setPageTitle("Новый лид". Yii::app()->name);


$this->breadcrumbs=array(
	'Лиды'=>array('index'),
	'Добавление',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/webmaster"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Новый лид</h1>

<?php if(!empty($apiResult)):?>
    <h2>Результат запроса к API:</h2>
    <textarea class="form-control" rows="10">
        <?php print_r($apiResult);?>
    </textarea>
<?php endif;?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
