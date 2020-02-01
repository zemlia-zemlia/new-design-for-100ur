<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->id) . ". Вопросы-ответы. " . Yii::app()->name);

$this->breadcrumbs = array(
    'Вопросы' => array('index'),
    $model->id,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));

?>


    <div class="row">
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body">
                    <div class="vert-margin30">
                        <h1>Вопрос #<?php echo $model->id; ?></h1>
                        <?php if ($model->title): ?>
                            <h3><?php echo CHtml::encode($model->title); ?> </h3>
                        <?php endif; ?>
                    </div>


                    <p>
                        <?php echo nl2br(CHtml::encode($model->questionText)); ?>
                    </p>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <h2>Ответы</h2>
                    <?php $this->widget('zii.widgets.CListView', array(
                        'dataProvider' => $answersDataProvider,
                        'itemView' => 'application.views.answer._view',
                        'emptyText' => 'Не найдено ни одного ответа',
                        'summaryText' => 'Показаны ответы с {start} до {end}, всего {count}',
                        'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words

                    )); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                <div class="box">
                    <div class="box-body">
                        <div class="vert-margin30">
                            <p>
                                <strong><?php echo $model->getAttributeLabel('category'); ?>:</strong>
                                <?php foreach ($model->categories as $category): ?>
                                    <span class="label label-warning"><?php echo CHtml::link($category->name, Yii::app()->createUrl('/admin/questionCategory/view', array('id' => $category->id))); ?></span>
                                <?php endforeach; ?>
                            </p>
                            <p><strong>Статус:</strong> <?php echo CHtml::encode($model->getQuestionStatusName()); ?>
                                <span class="muted"><?php echo CustomFuncs::niceDate($model->publishDate) . ' ' . CHtml::encode($model->bublishUser->name . ' ' . $model->bublishUser->lastName); ?></span>
                            </p>

                            <p><strong>Автор вопроса:</strong> <?php echo CHtml::encode($model->authorName); ?></p>
                            <p><strong>Email автора:</strong> <?php echo CHtml::encode($model->email); ?></p>

                            <?php if ($model->town): ?>
                                <p><strong>Город:</strong> <?php echo CHtml::encode($model->town->name); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php echo CHtml::link('Редактировать вопрос', Yii::app()->createUrl('/admin/question/update', array('id' => $model->id)), array('class' => 'btn btn-primary')); ?>

                        <?php echo CHtml::link('Смотреть на сайте', Yii::app()->createUrl('/question/view', array('id' => $model->id)), array('class' => 'btn btn-info', 'target' => '_blank')); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>


<?php //echo CHtml::link('Добавить ответ', Yii::app()->createUrl('/admin/answer/create',array('questionId'=>$model->id)),array('class'=>'btn btn-primary')); ?>