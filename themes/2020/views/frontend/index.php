<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._header');
?>

<?php if (Yii::app()->user->isGuest ): ?>
    <?php if (Yii::app()->request->requestUri == '/') :

    // выводим виджет с формой
    $this->widget('application.widgets.SimpleForm.SimpleForm', []);
    else :
    ?>


<div class="container">
    <div class="breadcrumbs">
        <?php
        $this->widget('zii.widgets.CBreadcrumbs', [
            'homeLink' => false,
            'separator' => null,
            'tagName' => 'ul',
            'activeLinkTemplate' => '<li class="breadcrumbs__item">
                        <a href="{url}" class="breadcrumbs__link">{label}</a></li>',
            'htmlOptions' => ['class' => 'breadcrumbs__list'],
            'inactiveLinkTemplate' => '<li class="breadcrumbs__item">
                        <a href="{url}" class="breadcrumbs__link breadcrumbs__link--active">{label}</a></li>',
            'links' => $this->breadcrumbs,

        ]);
        ?>
    </div>
</div>

    <?php endif; ?>
<?php else: ?>

<?php endif; ?>




            <?php echo $content; ?>




<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>
