<?php

use App\models\User;

Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('yurist'));

$pageTitle = 'Каталог юристов и адвокатов. 100 Юристов - Юридические консультации онлайн';

$this->setPageTitle($pageTitle . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag('Каталог юристов и адвокатов юридического портала 100 Юристов.', 'Description');
?>

<h1>Каталог юристов</h1>

<div class="alert alert-success">
    <h4>Вы юрист?</h4>
    <p>
        Ежедневно к нам поступают вопросы от пользователей из вашего региона, все эти пользователи ваши потенциальные клиенты. Хотите получать клиентов, отвечая на вопросы на нашем сайте?
        <br />
        <strong><?php echo CHtml::link('Зарегистрируйтесь как юрист', Yii::app()->createUrl('user/create', ['role' => User::ROLE_JURIST])); ?></strong>
		<br />В данном списке участвуют анкеты только тех юристов и адвокатов, которые заполнили свой профиль и загрузили свою фотографию 
                в виде аватарки анкеты. Позиция юриста в данном рейтинге зависит от количества данных ответов и его "кармы" - количество 
                пользователей, которые отметили ваш ответ как полезный.
    </p>
</div>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $yuristsDataProvider,
    'itemView' => '_view',
        'emptyText' => 'Не найдено ни одного юриста',
        'summaryText' => 'Показаны юристы с {start} до {end}, всего {count}',
        'pager' => ['class' => 'GTLinkPager'],
        'viewData' => ['onPage' => $yuristsDataProvider->getItemCount()],
]); ?>