<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>

<?php if ((Yii::app()->user->isGuest && !(Yii::app()->controller->id == 'question' && Yii::app()->controller->action->id == 'create'))): ?>
    <?php
    // выводим виджет с формой
    $this->widget('application.widgets.SimpleForm.SimpleForm', array());
    ?>

<?php else: ?>
    <div class="container">

        <div class="top-form-replace">
            <hr/>
        </div>

    </div>
<?php endif; ?>

    <div class="container">
        <div class="row">

            <?php echo $content; ?>

        </div>
    </div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>