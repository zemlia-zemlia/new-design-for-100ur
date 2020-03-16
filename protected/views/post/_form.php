<?php
/* @var $this PostController */

use App\models\Post;

/* @var $model Post */
/* @var $form CActiveForm */
?>

<div>

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'post-form',
        'enableAjaxValidation' => false,
    ]);
    ?>

    <p class="note"><span class="required">*</span> обязательные поля</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', ['class' => 'form-control', 'maxlength' => 256]); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="form-group"> 
        <?php echo $form->labelEx($model, 'preview'); ?>

        <?php
        $this->widget('ImperaviRedactorWidget', [
            // You can either use it for model attribute
            'model' => $model,
            'attribute' => 'preview',
            // Some options, see http://imperavi.com/redactor/docs/
            'options' => [
                'lang' => 'en',
                'imageUpload' => Yii::app()->createUrl('site/upload'),
                'toolbar' => true,
                'iframe' => true,
            ],
        ]);
        ?>
        <?php echo $form->error($model, 'preview'); ?>
    </div>

    <div class="form-group">   
        <?php echo $form->labelEx($model, 'text'); ?>

        <?php
        $this->widget('ImperaviRedactorWidget', [
            // You can either use it for model attribute
            'model' => $model,
            'attribute' => 'text',
            // Some options, see http://imperavi.com/redactor/docs/
            'options' => [
                'lang' => 'en',
                'imageUpload' => Yii::app()->createUrl('site/upload'),
                'toolbar' => true,
                'iframe' => false,
            ],
        ]);
        ?>

        <?php echo $form->error($model, 'text'); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', ['class' => 'form-control', 'rows' => 3]); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>


<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать пост' : 'Сохранить пост', ['class' => 'btn btn-primary btn-large']); ?>
    <?php
    if (!$model->isNewRecord) {
        echo CHtml::link('Не сохранять', Yii::app()->createUrl('post/view', ['id' => $model->id]), ['class' => 'btn btn-large']);
    }
    ?>


    <h2>Категории</h2>
    <p>Каждый пост может относиться к нескольким категориям. Отметьте нужные категории, зажав клавишу Ctrl (Command в MacOS)</p>
    <div class="form-group"> 
<?php
echo $form->dropDownList($model, 'categories', $categoriesArray, ['multiple' => 'multiple', 'style' => 'width:100%; height:10em; text-align:left;', 'class' => 'form-control']);
?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->