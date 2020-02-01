<?php
$this->setPageTitle("Звания юристов. ");
Yii::app()->clientScript->registerMetaTag("", 'description');

$answersCount = $user->answersCount;
$testimonialsCount = $user->commentsCount;
$rating = $user->getRating();
?>

<h1>
    Мои достижения
</h1>


<table class="table table-bordered">
    <tr>
        <th></th>
        <?php foreach ($rangsInfo as $rangId => $rang): ?>
            <td class="text-center">
                <?php if ($user->settings->rang == $rangId): ?>
                    <strong><?php echo $rang['name']; ?></strong>
                <?php else: ?>
                    <?php echo $rang['name']; ?>
                <?php endif; ?>
            </td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <td colspan="<?php echo sizeof($rangsInfo) + 1; ?>" class="text-center">Для достижения уровня необходимы:</td>
    </tr>
    <tr>
        <td>Ответы <sup>1</sup></td>
        <?php foreach ($rangsInfo as $rang): ?>

            <td class="text-center <?php echo ($answersCount >= $rang['limits']['answers']) ? 'success' : ''; ?>"><?php echo $rang['limits']['answers']; ?></td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <td>Карма <sup>2</sup></td>
        <?php foreach ($rangsInfo as $rang): ?>
            <td class="text-center <?php echo ($user->karma >= $rang['limits']['karma']) ? 'success' : ''; ?>"><?php echo $rang['limits']['karma']; ?></td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <td>Отзывы <sup>3</sup></td>
        <?php foreach ($rangsInfo as $rang): ?>
            <td class="text-center <?php echo ($testimonialsCount >= $rang['limits']['testimonials']) ? 'success' : ''; ?>"><?php echo $rang['limits']['testimonials']; ?></td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <td>Рейтинг по отзывам <sup>4</sup></td>
        <?php foreach ($rangsInfo as $rang): ?>
            <td class="text-center <?php echo ($rating >= $rang['limits']['rating']) ? 'success' : ''; ?>"><?php echo $rang['limits']['rating']; ?></td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <td colspan="<?php echo sizeof($rangsInfo) + 1; ?>" class="text-center">При достижении уровня:</td>
    </tr>
    <tr>
        <td>Комиссия платформы <sup>5</sup></td>
        <?php foreach ($rangsInfo as $rang): ?>
            <td class="text-center"><?php echo $rang['commission']; ?>%</td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <td>Вознаграждение <sup>6</sup></td>
        <?php foreach ($rangsInfo as $rang): ?>
            <td class="text-center"><?php echo ($rang['bonus'] > 0) ? $rang['bonus'] . ' руб.' : ''; ?> </td>
        <?php endforeach; ?>
    </tr>
</table>

<div class="text-muted small">
    <ol>
        <li>Ответы - количество ответов на вопросы пользователей, прошедших модерацию</li>
        <li>Карма - количество отметок "Полезный ответ" на ваши ответы, которые поставили авторы вопросов</li>
        <li>Отзывы - количество отзывов, прошедших модерацию</li>
        <li>Рейтинг по отзывам - средний балл по отзывам</li>
        <li>Комиссия платформы - процент от средств, получаемых вами от пользователей нашей платформы за онлайн услуги</li>
        <li>Вознаграждение - сумма, разово зачисляемая на ваш баланс при достижении уровня</li>
    </ol>
    Уровень пересчитывается автоматически один раз в сутки
</div>