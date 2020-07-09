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
                <ul class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="../index.html" class="breadcrumbs__link">Главная</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="../index.html" class="breadcrumbs__link">Вопросы юристам</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="#" class="breadcrumbs__link breadcrumbs__link--active">Архив вопросов за октябрь 2015 года</a>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>

<?php endif; ?>




            <?php echo $content; ?>




<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>
