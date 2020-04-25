<?php
/** @var User $data */
?>

<div class="row row-yurist">


    <?php
    $yuristName = ($data->settings && '' != $data->settings->alias) ? $data->settings->alias : $data->getNameOrCompany();
    ?>
    <div class="yurist-list-item">
        <div class="row">
            <div class="col-sm-3 col-xs-5">
                <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $data->id]); ?>">
                    <img src="<?php echo $data->getAvatarUrl(); ?>" alt="<?php echo CHtml::encode($yuristName); ?> " class="img-responsive" />
                </a>
            </div>
            <div class="col-sm-9 col-xs-7">

                <?php if ($data->town): ?>
                    <div style="float:right;">
                        <em class="text-muted">
                            <small>
                                <?php echo $data->town->name; ?>
                            </small>
                        </em>
                    </div>
                <?php endif; ?>

                <strong class="left-align" style="font-size: 15px;">
                    <?php echo CHtml::link(CHtml::encode($yuristName), Yii::app()->createUrl('user/view', ['id' => $data->id])); ?> 
                    <span class="text-muted"><em><?php echo $data->settings->getStatusName(); ?></em></span>
                </strong>
                <p class="">

                    <strong>Карма:</strong> <?php echo (int) $data->karma; ?>

                    <?php if ($data->answersCount): ?>
                        <strong>Ответов:</strong> <?php echo $data->answersCount; ?>
                    <?php endif; ?>

                    <?php if ($data->settings->priceConsult): ?>
                        <br />
                        <strong>Консультация:</strong> от <?php echo MoneyFormat::rubles($data->settings->priceConsult); ?> <span class="glyphicon glyphicon-ruble"></span>
                    <?php endif; ?>
                    <?php if ($data->settings->priceDoc): ?>
                        <br />
                        <strong>Документ:</strong> от <?php echo MoneyFormat::rubles($data->settings->priceDoc); ?>  <span class="glyphicon glyphicon-ruble"></span>
                    <?php endif; ?>

                    <!--                        ниже выводим кнопку чата-->

                    <?php if ($data->settings && $data->settings->isVerified && $data->isShowChatButton && $data->answersCount > 50) : ?>

                        <?=  CHtml::link('конс. в чате ' . MoneyFormat::rubles($data->settings->priceConsult) . ' руб',
                            Yii::app()->createUrl(Yii::app()->user->isGuest ? '/site/login' : '/user/chats',
                            ['chatId' => 'new', 'layerId' => $data->id]), ['class' => 'btn btn-primary btn-block']); ?>

                    <?php endif; ?>

                </p>
            </div>
        </div>
    </div>
</div>