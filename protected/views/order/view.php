<?php
$this->setPageTitle("Заказ документа #" . $order->id . '. ' . Yii::app()->name);

$this->breadcrumbs = [];
if (Yii::app()->user->role == User::ROLE_CLIENT) {
    $this->breadcrumbs['Личный кабинет'] = ['/user/'];
} else {
    $this->breadcrumbs['Заказы документов'] = ['/order/'];
}

$this->breadcrumbs[] = 'Заказ документа';

// определим, какую вкладку показать активной по умолчанию
$responsesClass = '';
$commentsClass = '';
if (in_array($order->status, [Order::STATUS_CONFIRMED])) {
    $responsesClass = 'active';
} else {
    $commentsClass = 'active';
}


$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>

<h1>Заказ документа #<?php echo $order->id; ?></h1>

<div class="row">
    <div class="<?php echo (Yii::app()->user->role != User::ROLE_JURIST) ? 'col-sm-8' : 'col-sm-12'; ?>">
        <table class="table table-bordered">
            <tr>
                <td>
                    <strong>Дата заказа</strong>
                </td>
                <td>
                    <?php echo CustomFuncs::niceDate($order->createDate, true, false); ?>
                </td>
            </tr>
            <?php if ($order->author): ?>
                <tr>
                    <td>
                        <strong>Автор</strong>
                    </td>
                    <td>
                        <p>
                            <?php echo CHtml::encode(trim($order->author->name . ' ' . $order->author->name2 . ' ' . $order->author->lastName)); ?>
                        </p>
                        <p>
                            Город: <?php echo $order->author->town->name; ?> (<?php echo $order->author->town->region->name; ?>)
                        </p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if ($order->jurist): ?>
                <tr>
                    <td>
                        <strong>Юрист</strong>
                    </td>
                    <td>
                        <p>
                            <?php echo CHtml::link(CHtml::encode(trim($order->jurist->name . ' ' . $order->jurist->name2 . ' ' . $order->jurist->lastName)), Yii::app()->createUrl('user/view', ['id' => $order->jurist->id])); ?>
                        </p>

                        <?php if ($order->status == Order::STATUS_JURIST_SELECTED && $order->juristId == Yii::app()->user->id): ?>
                            <?php echo CHtml::link("Принять заказ", Yii::app()->createUrl('order/changeStatus', ['action' => 'confirm', 'id' => $order->id]), ['class' => 'btn btn-xs btn-success']); ?>
                            <?php echo CHtml::link("Отказаться", Yii::app()->createUrl('order/changeStatus', ['action' => 'decline', 'id' => $order->id]), ['class' => 'btn btn-xs btn-danger']); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if ($order->term): ?>
                <tr>
                    <td>
                        <strong>Срок</strong>
                    </td>
                    <td>
                        <?php echo CustomFuncs::invertDate($order->term); ?>

                        <?php if ($order->status == Order::STATUS_JURIST_SELECTED && Yii::app()->user->role == User::ROLE_CLIENT): ?>
                            <?php echo CHtml::link('изменить', Yii::app()->createUrl('order/update', ['id' => $order->id]), ['class' => 'btn btn-default btn-xs']); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if ($order->price): ?>
                <tr>
                    <td>
                        <strong>Стоимость</strong>
                    </td>
                    <td>
                        <?php echo $order->price; ?> руб.
                        <?php if ($order->status == Order::STATUS_JURIST_SELECTED && Yii::app()->user->role == User::ROLE_CLIENT): ?>
                            <?php echo CHtml::link('изменить', Yii::app()->createUrl('order/update', ['id' => $order->id]), ['class' => 'btn btn-default btn-xs']); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td>
                    <strong>Вид документа</strong>
                </td>
                <td>
                    <?php echo $order->docType->getClassName(); ?>.
                    <?php echo $order->docType->name; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Статус заказа</strong>
                </td>
                <td>
                    <?php echo $order->getStatusName(); ?>

                    <?php if ($order->status == Order::STATUS_JURIST_SELECTED && Yii::app()->user->role == User::ROLE_CLIENT): ?>
                        <?php echo CHtml::link('отменить', Yii::app()->createUrl('order/cancel', ['id' => $order->id]), ['class' => 'btn btn-default btn-xs']); ?>
                    <?php endif; ?>
                    <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>
                        <p class="text-muted">
                            <?php echo Order::getStatusesNotes()[$order->status]; ?>
                        </p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Комментарий к заказу</strong>
                </td>
                <td>
                    <?php echo CHtml::encode($order->description); ?>
                </td>
            </tr>
        </table>
    </div>
    <?php if (Yii::app()->user->role != User::ROLE_JURIST): ?>
        <div class="col-sm-4">
            <p><strong>Ход работы над заказом</strong></p>
            <ol>
                <li class="text-success">Заказ создан</li>
                <li class="<?php echo ($order->status != Order::STATUS_NEW) ? 'text-success' : 'text-muted'; ?>">Заказ подтвержден</li>
                <li class="<?php echo ($order->responsesCount != 0) ? 'text-success' : 'text-muted'; ?>">Юристы откликнулись 
                    <?php if ($order->responsesCount > 0): ?>
                        (<?php echo $order->responsesCount; ?>)
                    <?php endif; ?>
                </li>
                <li class="<?php echo ($order->juristId != 0) ? 'text-success' : 'text-muted'; ?>">Выбран юрист</li>
                <li class="<?php echo (in_array($order->status, [Order::STATUS_JURIST_CONFIRMED, Order::STATUS_CLOSED, Order::STATUS_DONE])) ? 'text-success' : 'text-muted'; ?>">Заказ в работе</li>
                <li class="<?php echo ($order->juristId != 0) ? 'text-success' : 'text-muted'; ?>">Заказ выполнен</li>
                <li class="<?php echo ($order->status == Order::STATUS_CLOSED) ? 'text-success' : 'text-muted'; ?>">Заказ закрыт</li>
            </ol>
        </div>

        <?php if (Yii::app()->user->role == User::ROLE_CLIENT && $order->status != Order::STATUS_ARCHIVE): ?>
            <strong>Заказ больше не актуален?</strong>
            <p>
                <?php echo CHtml::link("Отправить в архив", Yii::app()->createUrl('order/toArchive', ['id' => $order->id])); ?><br />
                Юристы не будут видеть Ваш заказ и не смогут на него откликнуться
            </p>
        <?php endif; ?>

    <?php endif; ?>


</div>




<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="<?php echo $commentsClass; ?>"><a href="#comments" aria-controls="home" role="tab" data-toggle="tab">Переписка по заказу</a></li>
    <?php if ($responsesClass): ?>
        <li role="presentation" class="<?php echo $responsesClass; ?>"><a href="#responses" aria-controls="profile" role="tab" data-toggle="tab">Предложения юристов</a></li>
    <?php endif; ?>
</ul>

<div class="tab-content">

    <?php if ($responsesClass): ?>
        <div role="tabpanel" class="tab-pane <?php echo $responsesClass; ?>" id="responses">

            <h2>Предложения юристов</h2>

            <?php if (Yii::app()->user->role == User::ROLE_CLIENT && sizeof($order->responses) == 0): ?>
                <p class="center-align">
                    Юристы пока не прислали ни одного предложения.
                </p>
            <?php endif; ?>

            <?php $myResponses = 0; ?>

            <?php foreach ($order->responses as $response): ?>
                <?php
                if (Yii::app()->user->role == User::ROLE_JURIST && $response->authorId != Yii::app()->user->id) {
                    continue;
                }
                ?>

                <?php
                if (Yii::app()->user->role == User::ROLE_JURIST && $response->authorId == Yii::app()->user->id) {
                    $myResponses++;
                }
                ?>

                <?php if ($response->status != Comment::STATUS_SPAM): ?>
                    <div class="answer-comment order-response" style="margin-left:<?php echo ($response->level - 1) * 20; ?>px;">

                        <div class="row">
                            <div class="col-sm-2 col-xs-4">


                                <?php if ($response->author): ?>
                                    <div class="answer-item-avatar">
                                        <img src="<?php echo $response->author->getAvatarUrl(); ?>" alt="<?php echo CHtml::encode($response->author->name . ' ' . $response->author->lastName); ?>" class="img-responsive" />
                                    </div>
                                <?php endif; ?>

                                <?php if (floor((time() - strtotime($response->author->lastActivity)) / 60) < 60): ?>
                                    <div>
                                        <small>
                                            <span class="glyphicon glyphicon-flash"></span>
                                            <span class="text-success">Сейчас на сайте</span>
                                        </small>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="col-sm-10 col-xs-8">
                                <div class="row answer-item-author-block vert-margin20">
                                    <div class="col-sm-6">
                                        <p>
                                            <?php echo CHtml::link(CHtml::encode($response->author->name . ' ' . $response->author->lastName), Yii::app()->createUrl('user/view', ['id' => $response->author->id])); ?>
                                            <?php if ($response->author->settings->isVerified): ?>
                                                <small>
                                                    <span class="label label-default"><?php echo $response->author->settings->getStatusName(); ?></span>
                                                </small>
                                            <?php endif; ?>

                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="right-align order-price-tag">
                                            выполню заказ за
                                            <span class="label label-success">
                                                <?php echo MoneyFormat::rubles($response->price); ?> руб.
                                            </span>
                                        </div>
                                    </div>
                                </div>



                                <p>
                                    <?php echo CHtml::encode($response->text); ?>
                                </p>

                                <?php foreach ($response->comments as $comment): ?>
                                    <div class="answer-comment" style="margin-left:<?php echo ($comment->level - 1) * 20; ?>px;">

                                        <?php //CustomFuncs::printr($comment->children); ?>
                                        <p> <strong><span class="glyphicon glyphicon-comment"></span> 

                                                <?php echo CHtml::encode($comment->author->name . ' ' . $comment->author->lastName); ?>
                                                <?php if ($comment->author->settings->isVerified): ?>
                                                    <small>
                                                        <span class="label label-default"><?php echo $comment->author->settings->getStatusName(); ?></span>
                                                    </small>
                                                <?php endif; ?>

                                            </strong>
                                        </p>
                                        <p>
                                            <?php echo CHtml::encode($comment->text); ?>
                                        </p>

                                        <?php if (!is_null($commentModel) && $comment->authorId != Yii::app()->user->id): ?>
                                            <div class="right-align"> 
                                                <a class="btn btn-xs btn-default" role="button" data-toggle="collapse" href="#collapse-comment-<?php echo $comment->id; ?>" aria-expanded="false">
                                                    Ответить
                                                </a>
                                            </div>    
                                            <div class="collapse child-comment-container" id="collapse-comment-<?php echo $comment->id; ?>">
                                                <strong>Ваш ответ:</strong>
                                                <?php
                                                $this->renderPartial('application.views.comment._form', array(
                                                    'type' => Comment::TYPE_RESPONSE,
                                                    'objectId' => $response->id,
                                                    'model' => $commentModel,
                                                    'hideRating' => true,
                                                    'parentId' => $comment->id,
                                                ));
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>



                        <?php if (!is_null($commentModel) && ($order->userId == Yii::app()->user->id)): ?>
                            <div class="right-align">
                                <a class="btn btn-xs btn-warning" role="button" data-toggle="collapse" href="#collapse-response-<?php echo $response->id; ?>" aria-expanded="false">
                                    Обсудить условия
                                </a>
                                <?php if ($order->userId == Yii::app()->user->id && !$order->juristId): ?>
                                    <?php echo CHtml::link('Выбрать исполнителем', Yii::app()->createUrl('/order/setJurist', ['id' => $order->id, 'juristId' => $response->authorId, 'price' => $response->price]), ['class' => 'btn btn-xs btn-success']); ?>
                                <?php endif; ?>

                            </div>   
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <div class="collapse child-comment-container" id="collapse-response-<?php echo $response->id; ?>">
                                        <strong>Ваш ответ:</strong>
                                        <?php
                                        $this->renderPartial('application.views.comment._form', array(
                                            'type' => Comment::TYPE_RESPONSE,
                                            'objectId' => $response->id,
                                            'model' => $commentModel,
                                            'hideRating' => true,
                                            'parentId' => 0,
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <hr />
            <?php endforeach; ?>


            <?php if (!is_null($orderResponse) && Yii::app()->user->role == User::ROLE_JURIST && $myResponses == 0 && $order->status == Order::STATUS_CONFIRMED): ?>
                <?php
                $this->renderPartial('application.views.orderResponse._form', array(
                    'type' => Comment::TYPE_RESPONSE,
                    'objectId' => $order->id,
                    'model' => $orderResponse,
                    'hideRating' => true,
                    'parentId' => 0,
                ));
                ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div role="tabpanel" class="tab-pane <?php echo $commentsClass; ?>" id="comments">

        <h2>Сообщения</h2>
        <div class="container-fluid">

            <?php if (sizeof($order->comments) == 0 && Yii::app()->user->id == $order->userId): ?>
                <p>
                    Здесь будет переписка с выбранным исполнителем заказа
                </p>
            <?php endif; ?>

            <?php foreach ($order->comments as $com): ?>
                <div class="row">
                    <div class="answer-comment 
                    <?php
                    if ($com->authorId === Yii::app()->user->id) {
                        echo 'col-xs-10 col-xs-offset-2 grey-panel rounded';
                    } else {
                        echo 'col-xs-10 green-panel rounded';
                    }
                    ?>
                         ">

                        <p> <strong><span class="glyphicon glyphicon-comment"></span> 
                                <?php echo ($com->authorId == Yii::app()->user->id) ? 'Вы' : CHtml::encode($com->author->name . ' ' . $com->author->lastName); ?>
                            </strong>
                        </p>
                        <p>
                            <?php echo CHtml::encode($com->text); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
        if (in_array($order->status, [
                    Order::STATUS_JURIST_CONFIRMED,
                    Order::STATUS_DONE,
                    Order::STATUS_CLOSED,
                    Order::STATUS_REWORK,
                ])):
            ?>
            <?php
            $this->renderPartial('application.views.comment._form', array(
                'type' => Comment::TYPE_ORDER,
                'objectId' => $order->id,
                'model' => $orderComment,
                'hideRating' => true,
                    //'parentId'  => $comment->id,
            ));
            ?>
        <?php endif; ?>
    </div>
</div>