<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->id) . ". Вопросы. " . Yii::app()->name);

$this->breadcrumbs = array(
    'Вопросы' => array('index'),
    $model->id,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('Кабинет вебмастера', "/webmaster/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));

?>
    <div class="vert-margin30">
        <h1>Вопрос #<?php echo $model->id; ?></h1>
    </div>

    <div class="vert-margin30">
        <p>
            <?php echo nl2br(CHtml::encode($model->questionText)); ?>
        </p>
    </div>

    <div class="vert-margin30">
        <p>
            <strong>Источник:</strong>
            <?php echo CHtml::link(CHtml::encode($model->source->name), Yii::app()->createUrl('/webmaster/source/view', array('id' => $model->source->id))); ?>

        </p>
        <p><strong>Статус:</strong> <?php echo CHtml::encode($model->getQuestionStatusName()); ?><br/>
        </p>
        <p>
            <strong>Дата создания:</strong>
            <span class="muted"><?php echo CustomFuncs::niceDate($model->createDate, false, false); ?></span>
        </p>

        <p><strong>Автор вопроса:</strong> <?php echo CHtml::encode($model->authorName); ?></p>
        <?php if ($model->town): ?>
            <p><strong>Город:</strong> <?php echo CHtml::encode($model->town->name); ?></p>
        <?php endif; ?>

        <?php if (in_array($model->status, array(Question::STATUS_CHECK, Question::STATUS_PUBLISHED))): ?>
            <strong>Ваш заработок: </strong> <?php echo $model->buyPrice; ?> руб.
        <?php endif; ?>
    </div>

<?php if (in_array($model->status, array(Question::STATUS_CHECK, Question::STATUS_PUBLISHED))): ?>
    <?php echo CHtml::link('Смотреть на сайте', Yii::app()->createUrl('/question/view', array('id' => $model->id)), array('class' => 'btn btn-info', 'target' => '_blank')); ?>
<?php endif; ?>