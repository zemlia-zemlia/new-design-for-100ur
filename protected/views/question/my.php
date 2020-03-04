<?php
$this->setPageTitle('Мои вопросы');

Yii::app()->clientScript->registerMetaTag('Мои вопросы', 'description');

$this->breadcrumbs = [
    'Вопросы и ответы' => ['index'],
    'Новый вопрос',
];
?>

    <h1 class="header-block-light-grey vert-margin20">Мои вопросы</h1>

<?php foreach ($questions as $question): ?>
    <div class="row question-list-item  <?php if (1 == $question->payed): ?> vip-question<?php endif; ?>">
        <div class="col-sm-10 col-xs-8">
            <p style="font-size:0.9em;">
                <?php echo (new DateTime($question->createDate))->format('d.m.Y'); ?>
                &nbsp;&nbsp;
                <?php if (1 == $question->payed) {
    echo "<span class='label label-warning'><abbr title='Вопрос с гарантией получения ответов'><span class='glyphicon glyphicon-ruble'></span></abbr></span>";
}
                ?>
                <?php echo CHtml::link(StringHelper::mb_ucfirst($question->title, 'utf-8'), Yii::app()->createUrl('question/view', ['id' => $question->id])); ?>
            </p>
        </div>

        <div class="col-sm-2 col-xs-4 text-center">
            <small>
                <?php
                if (1 == $question->answersCount) {
                    echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
                } elseif ($question->answersCount > 1) {
                    echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $question->answersCount . ' ' . NumbersHelper::numForms($question->answersCount, 'ответ', 'ответа', 'ответов') . '</span>';
                } elseif (0 == $question->answersCount) {
                    echo "<span class='text-muted'>Нет ответа</span>";
                }
                ?>
            </small>
        </div>
    </div>
<?php endforeach; ?>