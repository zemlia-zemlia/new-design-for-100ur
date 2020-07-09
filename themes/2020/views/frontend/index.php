<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._header');
?>

<?php if (Yii::app()->user->isGuest ): ?>
    <?php if (Yii::app()->request->requestUri == '/') :

    // выводим виджет с формой
    $this->widget('application.widgets.SimpleForm.SimpleForm', []);
    ?>
    <?php endif; ?>
<?php else: ?>

<?php endif; ?>




            <?php echo $content; ?>




<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>
