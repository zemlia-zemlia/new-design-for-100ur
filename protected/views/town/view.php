<?php
/* @var $this TownController */
/* @var $model Town */
$this->setPageTitle("Консультация юриста в городе " . CHtml::encode($model->name) . ". " . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Консультация юриста по всем отраслям права в городе " . CHtml::encode($model->name) . ", только профессиональные юристы и адвокаты.", 'description');

?>

<h1>Консультация юриста <?php echo CHtml::encode($model->name); ?></h1>

<div class="question-form-wrapper">
<h3>Задайте вопрос юристу бесплатно</h3>
<?php
    $this->renderPartial('application.views.question._formSimple', array(
            'model'=>$questionModel,
        ));
?>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $dataProvider,
	'itemView'      =>  'application.views.question._view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',

)); ?>


