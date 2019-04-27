<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>

    <div class="top-form-replace"></div>

    <div class="container">
        <div class="row">
            <div id="left-panel"></div>

            <div class="col-sm-12" id="center-panel">
                <?php echo $content; ?>
            </div>

        </div>
    </div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>