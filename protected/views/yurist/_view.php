<?php if($index%2 == 0) :?>
        <div class="row row-yurist">
    <?php endif;?>

    <div class="col-sm-6 vert-margin30">

<?php
    $yuristName = ($data->settings && $data->settings->alias!='')?$data->settings->alias:$data->lastName . ' ' . $data->name . ' ' . $data->name2; 
?>
        <div class="yurist-list-item">
            <div class="row">
                <div class="col-sm-3">
                    <img src="<?php echo $data->getAvatarUrl();?>" alt="<?php echo CHtml::encode($yuristName);?> " class="img-responsive" />
                </div>
                <div class="col-sm-9">

                    <strong class="left-align" style="font-size: 15px;">
                        <?php echo CHtml::link(CHtml::encode($yuristName), Yii::app()->createUrl('user/view', array('id'=>$data->id)));?>
                    </strong>
                    <p class="small">
                        <?php if($data->town):?>
                            <strong>Город:</strong> <?php echo $data->town->name;?>
                        <?php endif;?>
                                        <br />
                                                <strong>Статус:</strong> <?php echo $data->settings->getStatusName();?>
                        <br />
                                                <strong>Карма:</strong> <?php echo (int)$data->karma;?><br />

                        <?php if($data->answersCount):?>
                            <strong>Ответов:</strong> <?php echo $data->answersCount;?>
                        <?php endif;?>

                        <?php if($data->settings->priceConsult):?>
                        <br />
                        <strong>Стоимость консультации:</strong> от <?php echo $data->settings->priceConsult;?> руб.
                        <?php endif;?>
                        <?php if($data->settings->priceDoc):?>
                            <br />
                            <strong>Стоимость документа:</strong> от <?php echo $data->settings->priceDoc;?> руб.
                        <?php endif;?>

                    </p>
                </div>
            </div>
        </div>
    </div>
<?php if($index%2 == 1 || $index == $onPage - 1) :?>
</div>
<?php endif;?>
