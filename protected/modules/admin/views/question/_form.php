<?php
/* @var $this QuestionController */

use App\models\Question;

/* @var $model Question */
/* @var $form CActiveForm */
?>

<?php
    Yii::app()->clientScript->registerCssFile('/css/2015/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js');
    Yii::app()->clientScript->registerScriptFile('/js/question_form.js');
?>

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'question-form',
    'enableAjaxValidation' => false,

]); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
       
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
		<?php echo $form->labelEx($model, 'title'); ?>
		<?php echo $form->textField($model, 'title', ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'title'); ?>
	</div>
        
	<div class="form-group">
		<?php echo $form->labelEx($model, 'questionText'); ?>
		<?php echo $form->textArea($model, 'questionText', ['class' => 'form-control', 'rows' => 10]); ?>
		<?php echo $form->error($model, 'questionText'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model, 'status'); ?><br />
		<?php echo $form->radioButtonList($model, 'status', Question::getStatusesArray(), ['class' => '', 'separator' => '&nbsp;&nbsp;']); ?>
		<?php echo $form->error($model, 'status'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model, 'authorName'); ?>
		<?php echo $form->textField($model, 'authorName', ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'authorName'); ?>
	</div>
        
        <p>
            <a role="button" data-toggle="collapse" href="#extra-collapse" aria-expanded="false" aria-controls="extra-collapse">
                Дополнительные параметры
            </a>
        </p>
        
        <div class="collapse" id="extra-collapse">
            <div class="form-group">
		<?php echo $form->labelEx($model, 'town'); ?>
		<?php echo $form->dropDownList($model, 'townId', $townsArray, ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'townId'); ?>
            </div>
        </div>
        

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-primary btn-block btn-lg']); ?>
	</div>
    </div>
    
    <div class="col-md-3">
        
        <h3>Категории</h3>
        <p>Каждый вопрос может относиться к нескольким категориям. Наните вводить название категории, чтобы увидеть список вариантов</p>
        <?php /*?>
        <?php $selectedCategories = array_keys(CHtml::listData( $model->categories, 'id' , 'id'));?>
        <?php echo CHtml::checkBoxList('Question[categories][]', $selectedCategories, $allCategories,
                array('multiple'=>true, 'checked'=>'checked'));
        ?>
         <?php */?>
        
        <div class="form-group">    
            <input id="category-selector" class="form-control" />
                
            <div id="selected-categories">
                <?php if ($model->categories) {
    foreach ($model->categories as $cat) {
        echo "<p><input type='checkbox' name='Question[categories][]' checked value='" . $cat->id . "'>" . $cat->name . '</input></p>';
    }
}
                ?>
            </div>
            <div id="selected-categories-message"></div>
        </div>
    </div>
	    <div class="col-md-3">
		            <div class="alert alert-warning">
                <h4><b>Удаляем</b></h4>
                <li>Лишние пробелы</li> 
				<li>Номера тел. mail-ов ФИО</li> 
				<li>Символы "№;?& и т.п. </li> 
            </div>
        
            <div class="alert alert-warning">
                <h4><b>Добавляем</b></h4>
                <li>Пробелы между словами если необходимо</li> 
				<li>Заголовок пишем сформулировав его из смысла вопроса (текста) пользователя</li> 
				<li>Категории выставляем исходя из сути вопроса</li> 
				<li>Ставим для всех вопросов всегда 3 категории</li> 
            </div>
     
            <div class="alert alert-warning">
                <h4><b>Орфография</b></h4>
                <li>Исправляем орфографию в том числе знаки препинания</li> 
            </div>
			
			<div class="alert alert-warning">
                <h4><b>Статус</b></h4>
                <li>После выполнения всех условий ставим статус "Одобрено" и жмем "сохранить"</li> 
            </div>
		</div>
</div>

<?php $this->endWidget(); ?>
