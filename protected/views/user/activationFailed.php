<?php
$this->pageTitle = Yii::app()->name . ' - Аккаунт не активирован';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Не получается активировать аккаунт</h1>
        <p>
        <?php
            if (isset($message)) {
                echo $message;
            }
        ?>
        </p>
    </div>
<div>