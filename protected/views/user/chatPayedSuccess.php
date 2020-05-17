<?php


Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/chatPayedSuccess.js', CClientScript::POS_END);



?>


<script>
    var chatUrl = "<?= $chatUrl ?>";
</script>

<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        Оплата консультации совершена, вы будете направлены на страницу чата через
        <span class="seconds">5</span>
        секунд

    </div>



</div>
