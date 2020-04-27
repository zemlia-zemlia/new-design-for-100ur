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
    <div class="vert-margin40">
        <?php foreach ($chats as $chat): ?>
            <a href="/user/chats?chatId=<?= $chat->chat_id ?>"
               class="btn btn-block <?= ($chat->chat_id == $room) ? 'btn-success' : 'btn-default' ?>">
                <img style="width: 20px;"
                     src="<?= ($role == User::ROLE_JURIST) ? $chat->user->getAvatarUrl() : $chat->lawyer->getAvatarUrl() ?>">
                <?= ($role == User::ROLE_JURIST) ? $chat->user->getShortName() : $chat->lawyer->getShortName() ?>
                <?php if ($chat->is_closed): ?>
                    (закрыт)
                <?php elseif ($chat->is_confirmed == null): ?>
                    (запрос)
                <?php endif; ?>
                <?php if ($chat->countMessages) : ?>
                <strong class="red">(<?= $chat->countMessages ?>)</strong>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (!$chats): ?>
        <?php if ($role == User::ROLE_CLIENT): ?>
            <div>
                У вас нет пока нет чатов с юристами.<br>
                Для начала чата выберите подходящего вам юриста в разделе <a href="/yurist/russia/">юристы</a>
            </div>
        <?php endif; ?>
        <?php if ($role == User::ROLE_JURIST): ?>
            <div class="vert-margin30">
                У вас нет пока нет чатов пользователями.
            </div>
        <?php endif; ?>
    <?php endif; ?>


    <?php if ($role == User::ROLE_CLIENT): ?>
        <div class="alert alert-info">
            <h3>Внимание!</h3>
            Никогда не переводите деньги юристу на личный кошелек или карту. Это лишает вас
            всех гарантий сервиса.
        </div>

        <h3>Гарантии 100Юристов</h3>
        <p>Юрист получает оплату после выполнения работы</p>
        <p>Гарантируем возврат денег, если не устраивает качество</p>
        <p>Полная конфидециальность</p>

    <?php endif; ?>

</div>
<?php if (!$chats && !$room): ?>
    <?php if ($role == User::ROLE_CLIENT): ?>
        <div class="col-md-8 col-lg-8 no_chats">
            У вас нет пока нет чатов с юристами.<br>
            Для начала чата выберите подходящего вам юриста в разделе <a href="/yurist/russia/">юристы</a>
        </div>
    <?php endif; ?>
    <?php if ($role == User::ROLE_JURIST): ?>
        <div class="col-md-8 col-lg-8 no_chats">
            У вас нет пока нет чатов пользователями.
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if ($chats && !$room): ?>
    <div class="col-md-8 col-lg-8 no_chats">
        Для просмотра сообщений выберите нужный чат слева
    </div>
<?php endif; ?>
<?php if ($room): ?>

    <div class="col-md-8 col-lg-8 chat-window" id="chats">
        <div class="row row-chat-info">
            <?php if ($curChat): ?>
                <div class="col-md-4">
                    <img style="width: 40px;"
                         src="<?= ($role == User::ROLE_JURIST) ? $curChat->user->getAvatarUrl() : $curChat->lawyer->getAvatarUrl() ?>">
                    <?= ($role == User::ROLE_JURIST) ? $curChat->user->getShortName() : $curChat->lawyer->getShortName() ?><br/>
                </div>
                <div class="col-md-3">
                <span class="small">был(а) в сети <br/>
                <?= ($role == User::ROLE_JURIST) ? $curChat->user->getPeriodFromLastActivity() : $curChat->lawyer->getPeriodFromLastActivity() ?>
                </span>
                </div>
                <div class="col-md-1">
                    <input class="btn btn-block btn-warning" type="button" value="!" title="Пожаловаться на чат"/>
                </div>
                <div class="col-md-4">
                    <input class="btn btn-block btn-success" id="closeButton" style="display: none;" type="button"
                           value="Завершить чат" title="Консультация закроется и юрист получит средства за консультацию."/>
                </div>
            <?php endif; ?>
        </div>
        <ul class="pages">
            <li class="chat page">
                <div class="chatArea">
                    <ul class="messages">
                        <?php if ($messages): ?>
                            <?php foreach ($messages as $mess): ?>
                                <?= $this->renderPartial('chatElem', ['mess' => $mess, 'user' => $user]) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            <li id="fileName">

            </li>
            <li style="display: none" id="typing"></li>
            <li style="width: 100%;">
                <input id="fileButton" class="fileButton" type="button" onclick="$('#fileinput').click()" value="File"/>
                <input id="messageInput" class="inputMessage" placeholder="Сообщение"/>
                <input id="send" class="closeButton" type="button" value="Отправить"/>
            </li>
        </ul>
</div>
<?php endif; ?>
<div id="chatElem" style="display: none">
    <?= $this->renderPartial('chatElem', ['mess' => [], 'user' => []]) ?>
</div>
<input onchange="processWebImage(this)"
       accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,image/*,application/pdf"
       type="file" id="fileinput" style="display: none">
<div id='payForm' style="display: none">
    <h2>Оплата консультации юриста</h2>
    <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="balance-form ">
        <input type="hidden" name="receiver" value="410012948838662">
        <input id="formLabel" type="hidden" name="label" value="">
        <input type="hidden" name="quickpay-form" value="shop">
        <input type="hidden" name="paymentType" value="AC">
        <input type="hidden" name="successURL"
               value="<?= getenv('PROTOCOL') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>">
        <input type="hidden" name="targets" value="Оплата консультаций">
        <div class="form-group">
            <div class="input-group text-center">
                <input type="hidden" name="sum" value="500" data-type="number" id="js-summ"
                       class="form-control text-right ">
                <span class="js-summ text-center"></span> руб.
            </div>
        </div>
        <input type="submit" class="btn btn-default" value="Перейти к оплате">
    </form>
    <div id="buttons" style="display: none">
        <a class="btn btn-success" id="accept" href="#">Принять чат</a> &nbsp;
        <a class="btn btn-danger" id="decline" href="#">Отклонить чат</a>
    </div>
</div>
<div style="clear: both"></div>


