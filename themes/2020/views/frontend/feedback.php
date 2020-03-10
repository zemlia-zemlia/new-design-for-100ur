<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._header');
?>
<?
/**
 * Этот шаблон используется в разделe:
 * /feedback/
 **/
?>
    <div class="container">
        <div class="top-form-replace">
            <hr/>
        </div>
    </div>


                <?php echo $content; ?>

<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>