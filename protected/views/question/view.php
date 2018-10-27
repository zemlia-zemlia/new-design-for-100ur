<?php
/* @var $this QuestionController */
/* @var $model Question */

$pageTitle = CHtml::encode(CustomFuncs::cutString($model->title, 70));
$pageTitle = CustomFuncs::mb_ucfirst($pageTitle, 'utf-8');

$this->setPageTitle($pageTitle);

$pageH1 = CHtml::encode(CustomFuncs::cutString($model->title, 70));

Yii::app()->clientScript->registerLinkTag("canonical", NULL, Yii::app()->createUrl('question/view', array('id' => $model->id)));

Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 160, 'utf-8')), 'description');

$this->breadcrumbs = array(
    'Вопросы' => array('index'),
    CustomFuncs::mb_ucfirst(CHtml::encode($model->title), 'utf-8'),
);
?>

<?php if ($justPublished == true): ?>
    <div class="alert alert-warning gray-panel" role="alert">
        <h4>Спасибо, <?php echo CHtml::encode(Yii::app()->user->name); ?>!</h4>
        <p class="text-success">
            <strong><span class="glyphicon glyphicon-ok"></span> Ваш вопрос опубликован</strong>. Теперь юристы смогут
            дать Вам ответ. <br/>
            <strong><span class="glyphicon glyphicon-ok"></span> Ваш Email подтвержден</strong>. На него Вы будете
            получать уведомления о новых ответах. <br/>

        </p>
    </div>
<?php endif; ?>

<div class="small">
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink' => CHtml::link('Вопрос юристу', "/"),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
    ));
    ?>
</div>

<div itemscope itemtype="http://schema.org/Question">

    <div id="question-hero" class="">

        <div class="row">
            <div class="col-sm-9">
                <p>
                    <?php if ($model->price != 0 && $model->payed == 1): ?>
                        <span class="label label-warning"><span class='glyphicon glyphicon-ruble'></span></span>
                    <?php endif; ?>

                    <small>
                        <?php if ($model->publishDate): ?>
                            <span class="glyphicon glyphicon-calendar"></span>&nbsp;
                            <time itemprop="dateCreated"
                                  datetime="<?php echo $model->publishDate; ?>"><?php echo CustomFuncs::niceDate($model->publishDate, false); ?></time> &nbsp;&nbsp;
                        <?php endif; ?>

                        <?php if ($model->categories): ?>
                            <?php foreach ($model->categories as $category): ?>
                                <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($category->name), Yii::app()->createUrl('questionCategory/alias', $category->getUrl())); ?> &nbsp;&nbsp;
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </small>
                </p>
            </div>
            <div class="col-sm-3">

            </div>
        </div>


        <div>
            <?php if ($model->title): ?>
                <h1 itemprop="name"><?php echo $pageH1; ?></h1>
            <?php endif; ?>
        </div>
    </div>


    <div itemprop="text" class="inside">
        <?php echo nl2br(CHtml::encode($model->questionText)); ?>
    </div>

    <p class="vert-margin30 right-align">
        <em>

            <?php if ($model->authorName): ?>
                <span itemprop="author" itemscope itemtype="http://schema.org/Person">

                    <span class="glyphicon glyphicon-user"></span>&nbsp;<span
                            itemprop="name"><?php echo CHtml::encode($model->authorName); ?></span> &nbsp;&nbsp;
                </span>
            <?php endif; ?>
            <?php if ($model->town): ?>
                <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php
                echo CHtml::link(CHtml::encode($model->town->name), Yii::app()->createUrl('town/alias', array(
                    'name' => $model->town->alias,
                    'countryAlias' => $model->town->country->alias,
                    'regionAlias' => $model->town->region->alias,
                )));
                ?> &nbsp;
                <?php if (!$model->town->isCapital): ?>
                    <span class="text-muted">(<?php echo $model->town->region->name; ?>)</span>
                <?php endif; ?>
                &nbsp;&nbsp;
            <?php endif; ?>
        </em>
    </p>


    <?php if (in_array(Yii::app()->user->role, array(User::ROLE_JURIST, User::ROLE_ROOT)) && !in_array(Yii::app()->user->id, $answersAuthors)): ?>

        <?php if (Yii::app()->user->isVerified || Yii::app()->user->role == User::ROLE_ROOT): ?>


            <div class='flat-panel inside vert-margin30'>
                <h2 class="">Ваш ответ на вопрос клиента:</h2>
                <?php $this->renderPartial('application.views.answer._form', array('model' => $answerModel)); ?>

            </div>
        <?php else: ?>
            <?php if (sizeof($lastRequest)): ?>
                <div class="alert alert-danger">
                    <p>
                        Вы не можете отвечать на вопросы.
                        Ваша заявка на подтверждение квалификации находится на проверке модератором.
                        Пожалуйста, дождитесь модерации.
                    </p>
                </div>

            <?php elseif (sizeof($lastRequest) == 0): ?>
                <div class="alert alert-danger">
                    <p>
                        Вы не можете отвечать на вопросы, пока не подтвердили свою квалификацию.
                    </p><br/>
                    <?php echo CHtml::link('Подтвердить квалификацию', Yii::app()->createUrl('userStatusRequest/create'), array('class' => 'btn btn-default')); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>


    <?php endif; ?>
    <?php if ($answersDataProvider->itemCount > 0): ?>
        <?php if ($answersDataProvider->itemCount == 1): ?>
            <h2>Ответ на вопрос:</h2>
        <?php else: ?>
            <h2>Ответы на вопрос:</h2>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $answersDataProvider,
        'itemView' => 'application.views.answer._view',
        'emptyText' => '<p class="text-muted inside">Ответов на этот вопрос пока нет...</p>',
        'summaryText' => '',
        'pager' => array('class' => 'GTLinkPager'), //we use own pager with russian words
        'viewData' => array(
            'commentModel' => $commentModel,
        ),
    ));
    ?>

</div> <!-- Question -->

<br/>
<?php if (Yii::app()->user->isGuest || Yii::app()->user->role == User::ROLE_CLIENT): ?>
    <div class="flat-panel inside">
        <div class="center-align">
            <?php
            // выводим виджет с номером 8800
            $this->widget('application.widgets.Hotline.HotlineWidget', array(
                'showAlways' => true,
            ));
            ?>
        </div>
    </div>

<?php endif; ?>
<br/>


<?php if (Yii::app()->user->role != User::ROLE_JURIST): ?>
<h3 class="header-block-light-grey vert-margin20">На ваши вопросы отвечают:</h3>
	<div class='vert-margin20'>
        <div class="row">
            <?php
	            // выводим виджет с топовыми юристами
	            $this->widget('application.widgets.TopYurists.TopYurists', array(
	                'cacheTime' => 0,
	            ));
            ?>
        </div>
    </div>
<?php endif; ?>

<?php
// если перед этим опубликовали вопрос, отправим цель в метрику
if (Yii::app()->user->getState('justPublished') == 1):
    ?>

    <script type="text/javascript">
        window.onload = function () {
            console.log('works');
            yaCounter26550786.reachGoal('questionPublished');
        }
    </script>

    <?php Yii::app()->user->setState('justPublished', 0); ?>
<?php endif; ?>

