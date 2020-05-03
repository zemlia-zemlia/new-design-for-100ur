<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._header');
?>

<div class="container">
    <div class="top-form-replace">
        <hr/>
    </div>
</div>

<div class="container">
    <?php echo $content; ?>
</div>

<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>