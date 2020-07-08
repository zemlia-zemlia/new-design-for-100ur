<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._header');
?>

<?php if ((Yii::app()->user->isGuest && !(Yii::app()->controller->id == 'question' && Yii::app()->controller->action->id == 'create'))): ?>
    <?php
    // выводим виджет с формой
    $this->widget('application.widgets.SimpleForm.SimpleForm', []);
    ?>

<?php else: ?>

<?php endif; ?>




            <?php echo $content; ?>




<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>
