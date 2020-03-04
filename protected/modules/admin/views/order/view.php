<?php
/* @var $this OrderController */
/* @var $model Order */
$this->setPageTitle('Заказ документов #' . $order->id . '. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Заказы документов' => ['index'],
    $order->id,
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<h1>Заказ документов #<?php echo $order->id; ?>
    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/order/update', ['id' => $order->id]), ['class' => 'btn btn-primary']); ?>
    <?php endif; ?>
</h1>
<div class="box">
    <table class="table table-bordered">
        <tr>
            <td>
                <strong>Дата заказа</strong>
            </td>
            <td>
                <?php echo DateHelper::niceDate($order->createDate, true, false); ?>
            </td>
        </tr>
        <?php if ($order->author): ?>
            <tr>
                <td>
                    <strong>Автор</strong>
                </td>
                <td>
                    <p>
                        <?php echo CHtml::link(CHtml::encode(trim($order->author->name . ' ' . $order->author->name2 . ' ' . $order->author->lastName)), Yii::app()->createUrl('admin/user/view', ['id' => $order->author->id])); ?>
                    </p>
                    <p>
                        Город: <?php echo $order->author->town->name; ?>
                        (<?php echo $order->author->town->region->name; ?>)
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
                        <?php echo CHtml::link(CHtml::encode(trim($order->jurist->name . ' ' . $order->jurist->name2 . ' ' . $order->jurist->lastName)), Yii::app()->createUrl('admin/user/view', ['id' => $order->juristId])); ?>
                    </p>
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($order->term): ?>
            <tr>
                <td>
                    <strong>Срок</strong>
                </td>
                <td>
                    <?php echo DateHelper::invertDate($order->term); ?>
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
<h3>Предложения юристов</h3>
<div class="box">
    <?php if (User::ROLE_CLIENT == Yii::app()->user->role && 0 == sizeof($order->responses)): ?>
        <p class="center-align">
            Юристы пока не прислали ни одного предложения.
        </p>
    <?php endif; ?>

    <?php $myResponses = 0; ?>

    <?php foreach ($order->responses as $response): ?>
        <?php if (User::ROLE_JURIST == Yii::app()->user->role && $response->authorId != Yii::app()->user->id) {
    continue;
}
        ?>

        <?php if (User::ROLE_JURIST == Yii::app()->user->role && $response->authorId == Yii::app()->user->id) {
            ++$myResponses;
        }
        ?>

        <?php if (Comment::STATUS_SPAM != $response->status): ?>
            <div class="answer-comment" style="margin-left:<?php echo($response->level - 1) * 20; ?>px;">

                <div class="row">
                    <div class="col-sm-2 col-xs-4">


                        <?php if ($response->author): ?>
                            <div class="answer-item-avatar">
                                <img src="<?php echo $response->author->getAvatarUrl(); ?>"
                                     alt="<?php echo CHtml::encode($response->author->name . ' ' . $response->author->lastName); ?>"
                                     class="img-responsive"/>
                            </div>
                            <?php if (floor((time() - strtotime($response->author->lastActivity)) / 60) < 60): ?>
                                <div class="center-align">
                                    <small>
                                        <span class="glyphicon glyphicon-flash"></span>
                                        <span class="text-success">Сейчас на сайте</span>
                                    </small>
                                </div>
                            <?php endif; ?>
                            <div class="center-align lead">
                            <span class="label label-success">
                                <?php echo MoneyFormat::rubles($response->price, true); ?> руб.
                            </span>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="col-sm-10 col-xs-8">
                        <p>
                            <?php echo CHtml::link(CHtml::encode($response->author->name . ' ' . $response->author->lastName), Yii::app()->createUrl('admin/user/view', ['id' => $response->author->id])); ?>
                            <?php if ($response->author->settings->isVerified): ?>
                                <small>
                                    <span class="label label-default"><?php echo $response->author->settings->getStatusName(); ?></span>
                                </small>
                            <?php endif; ?>
                        </p>
                        <p>
                            <?php echo CHtml::encode($response->text); ?>
                        </p>

                        <?php foreach ($response->comments as $comment): ?>
                            <div class="answer-comment"
                                 style="margin-left:<?php echo($comment->level - 1) * 20; ?>px;">

                                <?php //CustomFuncs::printr($comment->children);?>
                                <p><strong><span class="glyphicon glyphicon-comment"></span>

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
                                        <a class="btn btn-xs btn-default" role="button" data-toggle="collapse"
                                           href="#collapse-comment-<?php echo $comment->id; ?>" aria-expanded="false">
                                            Ответить
                                        </a>
                                    </div>
                                    <div class="collapse child-comment-container"
                                         id="collapse-comment-<?php echo $comment->id; ?>">
                                        <strong>Ваш ответ:</strong>
                                        <?php
                                        $this->renderPartial('application.views.comment._form', [
                                            'type' => Comment::TYPE_RESPONSE,
                                            'objectId' => $response->id,
                                            'model' => $commentModel,
                                            'hideRating' => true,
                                            'parentId' => $comment->id,
                                        ]);
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>


                <?php if (!is_null($commentModel) && ($order->userId == Yii::app()->user->id)): ?>
                    <div class="right-align">
                        <a class="btn btn-xs btn-default" role="button" data-toggle="collapse"
                           href="#collapse-response-<?php echo $response->id; ?>" aria-expanded="false">
                            Ответить
                        </a>
                    </div>
                    <div class="collapse child-comment-container" id="collapse-response-<?php echo $response->id; ?>">
                        <strong>Ваш ответ:</strong>
                        <?php
                        $this->renderPartial('application.views.comment._form', [
                            'type' => Comment::TYPE_RESPONSE,
                            'objectId' => $response->id,
                            'model' => $commentModel,
                            'hideRating' => true,
                            'parentId' => 0,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>


    <?php if (!is_null($orderResponse) && User::ROLE_JURIST == Yii::app()->user->role && 0 == $myResponses): ?>
        <?php
        $this->renderPartial('application.views.orderResponse._form', [
            'type' => Comment::TYPE_RESPONSE,
            'objectId' => $order->id,
            'model' => $orderResponse,
            'hideRating' => true,
            'parentId' => 0,
        ]);

        ?>
    <?php endif; ?>
</div>
