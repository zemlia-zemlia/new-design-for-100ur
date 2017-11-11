<?php
    $this->setPageTitle("Заказ документа #" . $order->id . '. '. Yii::app()->name);
    
    $this->breadcrumbs = [];
    if(Yii::app()->user->role == User::ROLE_CLIENT) {
        $this->breadcrumbs['Личный кабинет'] = ['/user/'];
    } else {
        $this->breadcrumbs['Заказы документов'] = ['/order/'];
    }
    
    $this->breadcrumbs[] = 'Заказ документа';
    
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Заказ документа #<?php echo $order->id;?></h1>

<table class="table table-bordered">
    <tr>
        <td>
            <strong>Дата заказа</strong>
        </td>
        <td>
            <?php echo CustomFuncs::niceDate($order->createDate, true, false);?>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Вид документа</strong>
        </td>
        <td>
            <?php echo $order->docType->getClassName();?>.
            <?php echo $order->docType->name;?>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Статус заказа</strong>
        </td>
        <td>
            <?php echo $order->getStatusName();?>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Комментарий к заказу</strong>
        </td>
        <td>
            <?php echo CHtml::encode($order->description);?>
        </td>
    </tr>
</table>

<h2>Предложения юристов</h2>

<?php if(Yii::app()->user->role == User::ROLE_CLIENT && sizeof($order->responses) == 0):?>
<p class="center-align">
    Юристы пока не прислали ни одного предложения.
</p>
<?php endif;?>

<?php $myResponses = 0;?>

<?php foreach($order->responses as $response):?>
    <?php if(Yii::app()->user->role == User::ROLE_JURIST && $response->authorId!=Yii::app()->user->id) {
            continue;
        }
    ?>
    
    <?php if(Yii::app()->user->role == User::ROLE_JURIST && $response->authorId ==Yii::app()->user->id) {
            $myResponses++;
        }
    ?>

    <?php if($response->status != Comment::STATUS_SPAM):?>
        <div class="answer-comment" style="margin-left:<?php echo ($response->level - 1)*20;?>px;">
            
            <div class="row">
                <div class="col-sm-2 col-xs-4">
                    

                        <?php if($response->author):?>
                            <div class="answer-item-avatar">
                                <img src="<?php echo $response->author->getAvatarUrl();?>" alt="<?php echo CHtml::encode($response->author->name . ' ' . $response->author->lastName);?>" class="img-responsive" />
                            </div>
                            <?php if(floor((time() - strtotime($response->author->lastActivity))/60)<60):?>
                                <div class="center-align">
                                    <small>
                                        <span class="glyphicon glyphicon-flash"></span>
                                        <span class="text-success">Сейчас на сайте</span>
                                    </small>
                                </div>
                            <?php endif;?>
                        <div class="center-align lead">
                            <span class="label label-success">
                                <?php echo $response->price;?> руб. 
                            </span>
                        </div>
                        <?php endif;?>

                </div>
                <div class="col-sm-10 col-xs-8">
                    <p>
                    <?php echo CHtml::encode($response->author->name . ' ' . $response->author->lastName);?>
                    <?php if($response->author->settings->isVerified):?>
                    <small>
                        <span class="label label-default"><?php echo $response->author->settings->getStatusName();?></span>
                    </small>
                    <?php endif;?>
                    </p>
                    <p>
                        <?php echo CHtml::encode($response->text);?>
                    </p>
                    
                    <?php foreach($response->comments as $comment):?>
                <div class="answer-comment" style="margin-left:<?php echo ($comment->level - 1)*20;?>px;">
                    
                    <?php //CustomFuncs::printr($comment->children);?>
                    <p> <strong><span class="glyphicon glyphicon-comment"></span> 

                            <?php echo CHtml::encode($comment->author->name . ' ' . $comment->author->lastName);?>
                            <?php if($comment->author->settings->isVerified):?>
                            <small>
                                <span class="label label-default"><?php echo $comment->author->settings->getStatusName();?></span>
                            </small>
                            <?php endif;?>

                        </strong>
                    </p>
                    <p>
                        <?php echo CHtml::encode($comment->text);?>
                    </p>
                    
                    <?php if(!is_null($commentModel) && $comment->authorId != Yii::app()->user->id):?>
                        <div class="right-align">
                        <a class="btn btn-xs btn-default" role="button" data-toggle="collapse" href="#collapse-comment-<?php echo $comment->id;?>" aria-expanded="false">
                            Ответить
                          </a>
                        </div>    
                        <div class="collapse child-comment-container" id="collapse-comment-<?php echo $comment->id;?>">
                            <strong>Ваш ответ:</strong>
                            <?php 
                                $this->renderPartial('application.views.comment._form', array(
                                    'type'      => Comment::TYPE_RESPONSE,
                                    'objectId'  => $response->id,
                                    'model'     => $commentModel,
                                    'hideRating'=> true,
                                    'parentId'  => $comment->id,
                                ));
                            ?>
                        </div>
                    <?php endif;?>
                </div>
            <?php endforeach;?>
                </div>
            </div>
            
            
            
            <?php if(!is_null($commentModel) && ($order->userId == Yii::app()->user->id)):?>
            <div class="right-align">
            <a class="btn btn-xs btn-default" role="button" data-toggle="collapse" href="#collapse-response-<?php echo $response->id;?>" aria-expanded="false">
                Ответить
              </a>
            </div>    
            <div class="collapse child-comment-container" id="collapse-response-<?php echo $response->id;?>">
                <strong>Ваш ответ:</strong>
                <?php 
                    $this->renderPartial('application.views.comment._form', array(
                        'type'      => Comment::TYPE_RESPONSE,
                        'objectId'  => $response->id,
                        'model'     => $commentModel,
                        'hideRating'=> true,
                        'parentId'  => 0,
                    ));
                ?>
            </div>
            <?php endif;?>
        </div>
    <?php endif;?>
<?php endforeach;?>


<?php if(!is_null($orderResponse) && Yii::app()->user->role == User::ROLE_JURIST && $myResponses == 0):?>
    <?php 
        $this->renderPartial('application.views.orderResponse._form', array(
            'type'          => Comment::TYPE_RESPONSE,
            'objectId'      => $order->id,
            'model'         => $orderResponse,
            'hideRating'    =>  true,
            'parentId'      =>  0,
        ));

    ?>
<?php endif;?>