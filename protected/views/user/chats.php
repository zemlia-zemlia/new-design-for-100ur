<?php
/** @var \App\models\Chat $chat */
/** @var \App\models\Chat $chats */
/** @var \App\models\Chat $curChat */
/* @var $this UserController */
/* @var string $room */
/* @var int $role */
/* @var \App\models\User $user */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/socket.io-client/socket.io.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/chat.js', CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/chat.css');
if (!$room and $chats) {
    $room = $chats[0]->chat_id;
}

?>

<?php if ($room): ?>
    <script>
        window.room = "<?=$room?>"
    </script>
<?php endif; ?>
<script>
    window.username = "<?=$user->getShortName()?>"
    window.token = "<?=$user->confirm_code?>"
    window.role = "<?=$role?>"
</script>
<?php if ($role == \App\models\User::ROLE_JURIST): ?>
    <script>
        window.layer_id = "<?=$user->confirm_code?>"
    </script>
<?php endif; ?>

<div style="clear: both"></div>
<div class="col-md-12 col-lg-12">
    <h1>
        Чат с
        <?php if ($role == 3): ?>
            юристами
        <?php else: ?>
            пользователями
        <?php endif; ?>
    </h1>
</div>
<div class="col-md-4 col-lg-4">
    <h3>Ваши чаты:</h3>
    <?php foreach ($chats as $chat): ?>
        <a href="/user/chats?chatId=<?= $chat->chat_id ?>"
           class="btn <?= ($chat->chat_id == $room) ? 'btn-primary' : 'btn-default' ?>">
            <img style="width: 20px;"
                 src="<?= ($role == 10) ? $chat->user->getAvatarUrl() : $chat->layer->getAvatarUrl() ?>">
            <?= ($role == 10) ? $chat->user->getShortName() : $chat->layer->getShortName() ?>
            <?php if ($chat->is_closed): ?>
                (закрыт)
            <?php endif; ?>
            <?php if ($chat->is_confirmed == null): ?>
                (запрос)
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>
<div class="col-md-8 col-lg-8">
    <div>
        <?php if ($curChat): ?>
            <img style="width: 20px;"
                 src="<?= ($role == 10) ? $curChat->user->getAvatarUrl() : $curChat->layer->getAvatarUrl() ?>"><br>
            <?= ($role == 10) ? $chat->user->getShortName() : $chat->layer->getShortName() ?> <br>
            была в сети <?= ($role == 10) ? $chat->user->getLastOnline() : $chat->layer->getLastOnline() ?>
        <?php endif; ?>

    </div>
    <ul class="pages" style="height: 500px;">
        <li class="chat page">
            <div class="chatArea">
                <ul class="messages">
                    <?php if ($messages): ?>
                        <?php foreach ($messages as $mess): ?>
                            <li class="message <?= ($mess['token'] == $user->confirm_code) ? 'my' : '' ?>"
                                style="display: list-item;">
                                <span class="username" style="color: rgb(247, 139, 0);">
                                    <?php if ($mess['token'] != $user->confirm_code): ?>
                                        <img style="width: 20px;" src="<?= $mess['avatar'] ?>"/>
                                    <?php endif; ?>
                                    <?= ($mess['token'] == $user->confirm_code) ? 'Вы' : $mess['username'] ?></span>
                                <?= $mess['date'] ?>
                                <span class="messageBody"><?= $mess['message'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <li id="fileName">

        </li>
        <li style="width: 100%; position: absolute; bottom: 90px;left: 0px;">
            <input id="closeButton" style="display: none;" type="button" value="Закрыть чат"/>

            <input id="fileButton" class="fileButton" type="button" onclick="$('#fileinput').click()"
                   value="File"/>
            <input id="messageInput" class="inputMessage" placeholder="Сообщение"/>

            <input id="send" class="closeButton" type="button" value="Послать"/>
        </li>
    </ul>
</div>


<input onchange="processWebImage(this)" type="file" id="fileinput" style="display: none">
<div id='payForm' style="display: none">
    <h2> Необходимо оплатить услуги юриста</h2>
    <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="balance-form">
        <input type="hidden" name="receiver" value="410012948838662">
        <input id="formLabel" type="hidden" name="label" value="">
        <input type="hidden" name="quickpay-form" value="shop">
        <input type="hidden" name="successURL"
               value="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>">
        <input type="hidden" name="targets" value="Оплата консультаций">
        <div class="form-group">
            <div class="input-group">
                <input type="hidden" name="sum" value="500" data-type="number" id="js-summ"
                       class="form-control text-right ">
                <div class="input-group-addon"><span class="js-summ"></span> руб.</div>
            </div>
        </div>
        <div class="radio">
            <label><input type="radio" name="paymentType" value="PC" checked>Яндекс.Деньгами <br/>
                <small>Комиссия 0.5%
                </small>
            </label>
            <label><input type="radio" name="paymentType" value="AC">Банковской картой<br/>
                <small>Комиссия 2%
                </small>
            </label>
        </div>

        <input type="submit" class="btn btn-default" value="Оплатить">
    </form>
    <div id="buttons" style="display: none">
        <a class="btn btn-success" id="accept" href="#">Принять чат</a> &nbsp;
        <a class="btn btn-danger" id="decline" href="#">Отклонить чат</a>
    </div>
</div>
<div style="clear: both"></div>


