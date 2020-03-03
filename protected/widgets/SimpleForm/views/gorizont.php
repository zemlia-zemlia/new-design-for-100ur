<div class="sidebar-form">
<div class="form-container">

        <?php $form = $this->beginWidget('CActiveForm', [
                'id' => 'question-form-sidebar',
                'enableAjaxValidation' => false,
                'action' => Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=sidebar&utm_campaign=' . Yii::app()->controller->id,
        ]); ?>

        <h3>Проконсультируйтесь в режиме online</h3>
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="form-group">
                        <?php echo $form->textArea($model, 'questionText', ['class' => 'form-control', 'rows' => 6, 'placeholder' => 'Опишите свою ситуацию максимально подробно чтобы юрист смог сориентироваться и дать максимально развернутый ответ']); ?>
                        <?php echo $form->error($model, 'questionText'); ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <?php echo $form->textField($model, 'authorName', ['class' => 'form-control', 'placeholder' => 'Ваше имя']); ?>
                    <?php echo $form->error($model, 'authorName'); ?>
                </div>
                <div class="form-group" id="form-submit-wrapper">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить вопрос' : 'Сохранить', ['class' => 'yellow-button btn-block', 'onclick' => 'yaCounter26550786.reachGoal("simple_form_submit"); return true;']); ?>
                </div>
                <p class="text-center"><strong>Более 700 Юристов нашего портала готовы ответить на ваш вопрос</strong></p>
                    

            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- .form-container-->   
</div>