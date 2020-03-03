<?php
$this->setPageTitle('Заказать юридические услуги ' . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag('Заказ юридических услуг онлайн, выбирайте из нескольких профессионалов.', 'description');

?>


<h1 class="header-block header-block-light-grey">Заказать юридические услуги</h1>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formServices', [
            'model' => $model,
            'townsArray' => $townsArray,
        ]); ?>
    </div>
</div>
