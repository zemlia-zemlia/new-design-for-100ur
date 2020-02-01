<?php

$this->setPageTitle("Не заполнен профиль | Публикации" . " | ". Yii::app()->name);

$this->breadcrumbs  = array(
    'Публикации'    =>  array('/category'),
    'Не заполнен профиль',
);

?>
<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Поиск попутчиков', "/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Ваш профиль не заполнен</h1>
<p>
    Добавлять публикации могут только пользователи с подтвержденным Email.<br />
    Для того, чтобы подтвердить свой Email, укажите его в <?php echo CHtml::link('своем профиле', Yii::app()->createUrl('/user')); ?>
</p>