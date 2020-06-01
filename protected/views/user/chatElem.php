<?php
/** @var $mess */
?>
<div class="small ">
    <li class="message <?php echo ($mess['token'] == $user->chatToken) ? 'my' : ''; ?>"
        style="display: list-item;">
        <span class="username">
            <?php if ($mess['token'] != $user->chatToken): ?>
                <img style="width: 20px;" src="<?php echo $mess['avatar']; ?>"/>
            <?php endif; ?>

            <?php echo ($mess['token'] == $user->chatToken) ? 'Вы:' : $mess['username']; ?>
        </span>

        <span class="dateMessage"><?php echo $mess['date']; ?></span>

        <?php if ($mess['token'] == $user->chatToken): ?>
            <?php if ($mess['is_read']): ?>
                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"
                      title="Прочитано"></span>
            <?php else: ?>
                <span class="glyphicon glyphicon-ok" aria-hidden="true"
                      title="Не прочитано"></span>
            <?php endif; ?>
        <?php endif; ?>

        <br/>
        <span class="messageBody"><?php echo $mess['message']; ?></span>
    </li>
</div>
