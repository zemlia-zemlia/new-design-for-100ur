<?php
$this->setPageTitle("Мои вопросы");

Yii::app()->clientScript->registerMetaTag("Мои вопросы", 'description');

$this->breadcrumbs = array(
    'Вопросы и ответы' => array('index'),
    'Новый вопрос',
);
?>

    <h1 class="header-block-light-grey vert-margin20">Мои вопросы</h1>

<?php foreach ($questions as $question): ?>
    <div class="row question-list-item  <?php if ($question->payed == 1): ?> vip-question<? endif; ?>">
        <div class="col-sm-10 col-xs-8">
            <p style="font-size:0.9em;">
                <?php echo (new DateTime($question->createDate))->format('d.m.Y');?>
                &nbsp;&nbsp;
                <?php if ($question->payed == 1) {
                    echo "<span class='label label-warning'><abbr title='Вопрос с гарантией получения ответов'><span class='glyphicon glyphicon-ruble'></span></abbr></span>";
                }
                ?>
                <?php echo CHtml::link(CustomFuncs::mb_ucfirst($question->title, 'utf-8'), Yii::app()->createUrl('question/view', array('id' => $question->id))); ?>
            </p>
        </div>

        <div class="col-sm-2 col-xs-4 text-center">
            <small>
                <?php
                if ($question->answersCount == 1) {
                    echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
                } elseif ($question->answersCount > 1) {
                    echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $question->answersCount . ' ' . CustomFuncs::numForms($question->answersCount, 'ответ', 'ответа', 'ответов') . "</span>";
                } elseif ($question->answersCount == 0) {
                    echo "<span class='text-muted'>Нет ответа</span>";
                }
                ?>
            </small>
        </div>
    </div>
<?php endforeach; ?>