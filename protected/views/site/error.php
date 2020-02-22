<?php
$this->pageTitle = Yii::app()->name . ' - Ошибка ' . $code;
$this->breadcrumbs = [
    'Error',
];
?>
<div class="panel panel-warning">
    <div class="panel-body error-message">
        <h1 class="vert-margin30">Ой, вот ведь незадача, ошибка <?php echo $code; ?>!</h1>
        <h3><?php echo CHtml::encode($message); ?></h3>

        <p class="text-center vert-margin30">
            <?php echo CHtml::link('Перейти на главную страницу', '/', ['class' => 'btn btn-primary']); ?>
        </p>
        <p class="text-muted">
            Все ошибки сохраняются и просматриваются администратором сайта.<br/>
            Если вы считаете необходимым рассказать об этой ошибке разработчикам сайта,
            отправьте письмо с описанием ошибки на адрес <?php echo Yii::app()->params['adminEmail']; ?>.
        </p>
    </div>
</div>