<?php
    $this->setPageTitle("Консультация юриста и адвоката. ". Yii::app()->name);
?>

<div class="question-form-wrapper">
<h3>Задайте вопрос юристу бесплатно</h3>
<?php
    $this->renderPartial('application.views.question._formSimple', array(
            'model'=>$questionModel,
        ));
?>
</div>

<h2>Новые вопросы</h2>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  'application.views.question._view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',

)); ?>