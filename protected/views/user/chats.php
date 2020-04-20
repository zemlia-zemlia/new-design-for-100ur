<?php
/** @var \App\models\Chat $chat */
/** @var \App\models\Chat $chats */
/** @var \App\models\Chat $curChat */
/* @var $this UserController */
/* @var string $room */
/* @var int $role */

/* @var User $user */

use App\models\User;
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
    window.username = "<?=$user->getShortName()?>";
    window.token = "<?=$user->confirm_code?>";
    window.role = "<?=$role?>";
    window.siteUrl = "<?=Yii::app()->getBaseUrl(true)?>";
    window.chaturl = "<?=getenv('CHAT_URL')?>:<?=getenv('CHAT_PORT')?>";
</script>
<?php if ($role == User::ROLE_JURIST): ?>
    <script>
        window.layer_id = "<?=$user->confirm_code?>"
    </script>
<?php endif; ?>

<div style="clear: both"></div>
<div class="col-md-12 col-lg-12">
    <h1>
        Чат с
        <?php if ($role == User::ROLE_CLIENT): ?>
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
           class="btn btn-block <?= ($chat->chat_id == $room) ? 'btn-success' : 'btn-default' ?>">
            <img style="width: 20px;"
                 src="<?= ($role == User::ROLE_JURIST) ? $chat->user->getAvatarUrl() : $chat->lawyer->getAvatarUrl() ?>">
            <?= ($role == User::ROLE_JURIST) ? $chat->user->getShortName() : $chat->lawyer->getShortName() ?>
            <?php if ($chat->is_closed): ?>
                (закрыт)
            <?php endif; ?>
            <?php if ($chat->is_confirmed == null): ?>
                (запрос)
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
    <?php if (!$chats): ?>
        <?php if ($role == User::ROLE_CLIENT): ?>
            <div>
                У вас нет пока нет чатов с юристами.<br>
                Для начала чата выберите подходящего вам юриста в разделе <a href="/yurist/russia/">юристы</a>
            </div>
        <?php endif; ?>
        <?php if ($role == User::ROLE_JURIST): ?>
            <div>
                У вас нет пока нет чатов пользователями,
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<div class="col-md-8 col-lg-8" id="chats">
    <div class="row">
        <?php if ($curChat): ?>
            <div class="col-md-4">
                <img style="width: 40px;"
                     src="<?= ($role == User::ROLE_JURIST) ? $curChat->user->getAvatarUrl() : $curChat->lawyer->getAvatarUrl() ?>">
            </div>
            <div class="col-md-4">
                <?= ($role == User::ROLE_JURIST) ? $chat->user->getShortName() : $chat->lawyer->getShortName() ?>
            </div>
            <div class="col-md-4">
                была в
                сети <?= ($role == User::ROLE_JURIST) ? $chat->user->getLastOnline() : $chat->lawyer->getLastOnline() ?>
            </div>
        <?php endif; ?>
    </div>
    <ul class="pages">
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
                                <?php if ($mess['token'] == $user->confirm_code): ?>
                                <?php if ($mess['is_read']): ?>
                                    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                                <?php else: ?>
                                <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?= $mess['date'] ?>
                                <span class="messageBody"><?= $mess['message'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <li id="fileName">

        </li>
        <li style="width: 100%;">
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
               value="<?= getenv('PROTOCOL') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>">
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


