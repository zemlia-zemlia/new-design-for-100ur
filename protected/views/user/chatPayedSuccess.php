<?php


Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/chatPayedSuccess.js', CClientScript::POS_END);



?>


<script>
    var chatUrl = "<?= $chatUrl ?>";
</script>

<div class="vert-margin40"></div>

<div class="row center-align">
    <div class="col-lg-4 col-lg-offset-4">
        <h3>Оплата консультации совершена, вы будете направлены на страницу чата</h3>
        <h3>через <span class="seconds">5</span> секунд</h3>
    </div>
</div>
