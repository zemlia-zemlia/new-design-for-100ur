<?php
$this->pageTitle=Yii::app()->name . ' - Ошибка '.$code;
$this->breadcrumbs=array(
	'Error',
);
?>
<div class="panel panel-warning">
    <div class="panel-body error-message">
    <h1>Ой, вот ведь незадача!</h1>
    <h2>ошибка № <?php echo $code; ?></h2>

    <div class="description error">
        <?php if(!Yii::app()->user->isGuest):?>
            <?php echo CHtml::encode($message); ?>
        <?php endif;?>
    </div>    
        <p>
            Все ошибки сохраняются и просматриваются администратором сайта.<br />
            Если вы считаете необходимым рассказать об этой ошибке разработчикам сайта,
            отправьте письмо с описанием ошибки на адрес <?php echo Yii::app()->params['adminEmail']; ?>.
        </p>
    </div>
</div>