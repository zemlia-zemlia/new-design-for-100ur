<?php

use App\models\User;

CController::renderPartial('webroot.themes.2020.views.frontend.include._header');

?>

<div class="container">
    <div class="top-form-replace">
        <hr/>
    </div>
</div>

<div class="container">
    <div class="row">
        <div id="left-panel"></div>

        <div class="col-sm-12" id="center-panel">
            <?php echo $content; ?>
        </div>
    </div>
</div>

<?php
CController::renderPartial('webroot.themes.2020.views.frontend.include._footer');
?>
