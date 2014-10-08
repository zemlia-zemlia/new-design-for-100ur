<?php
$this->pageTitle=Yii::app()->name . ' - Ошибка '.$code;
$this->breadcrumbs=array(
	'Error',
);
?>

<h1>Ой, ошибка № <?php echo $code; ?></h1>

<div class="description error">
<?php echo CHtml::encode($message); ?>
</div>    
    <p>
        Все ошибки сохраняются и просматриваются администратором сайта.<br />
        Если вы считаете необходимым рассказать об этой ошибке разработчикам сайта, отправьте письмо с описанием ошибки на адрес <?php echo Yii::app()->params['adminEmail']; ?>.
    </p>
