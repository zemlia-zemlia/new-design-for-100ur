<div class="sidebar-form">
<div class="form-container">
        <h2 class="center-align">Не нашли свой ответ?</h2>
        <p class="center-align">
            Задайте вопрос. Это бесплатно.
        </p>

        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'                    =>  'question-form',
                'enableAjaxValidation'  =>  false,
                'action'                =>  Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=sidebar&utm_campaign='.Yii::app()->controller->id,
        )); ?>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                        <?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Меня хотят выписать из квартиры в которой не проживаю больше двух лет, как мне действовать чтобы сохранить прописку?')); ?>
                        <?php echo $form->error($model,'questionText'); ?>
                </div>

                <div class="form-group">
                    <label>Ваше имя *</label>
                    <?php echo $form->textField($model,'authorName', array('class'=>'form-control', 'placeholder'=>'Владимир')); ?>
                    <?php echo $form->error($model,'authorName'); ?>
                </div>
                <div class="form-group" id="form-submit-wrapper">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'yellow-button btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                </div>
                    

            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- .form-container-->   
</div>