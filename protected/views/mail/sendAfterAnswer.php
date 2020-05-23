<?php
/** @var array $row */
/* @var string $questionLink */
?>

<p>Здравствуйте, <?php echo CHtml::encode($row['authorName']); ?><br /><br />
    Недавно вы задавали вопрос на нашем портале.<br />
    <?php echo  CHtml::encode($row['yuristName'] . ' ' . $row['yuristLastName']); ?> дал(а) ответ на <?php echo  CHtml::link('Ваш вопрос', $questionLink); ?>.
    <br /><br />
    Мы стараемся становиться лучше, поэтому нам важно ваше мнение и хотели бы получить от вас отзыв, это не займет больше минуты.
    <br /><br />
    <?php echo CHtml::link('Посмотреть и оценить ответ', $questionLink, ['class' => 'btn']); ?>
</p>
<p>
    Оставить отзыв можно перейдя по ссылке: <a href="https://yandex.ru/profile/1540217792">Ссылка на профиль в яндекс справочнике</a>
</p>
<p>
    Заранее спасибо!
</p>
