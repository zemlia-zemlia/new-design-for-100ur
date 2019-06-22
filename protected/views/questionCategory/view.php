<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

if ($model->seoTitle) {
    $pageTitle = CHtml::encode($model->seoTitle);
} else {
    $pageTitle = CHtml::encode($model->name) . ". Консультация юриста и адвоката. ";
}

if (isset($_GET) && (int)$_GET['page'] && $questionsDataProvider->pagination) {
    $pageNumber = (int)$_GET['page'];
    $pagesTotal = ceil($questionsDataProvider->totalItemCount / $questionsDataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);


if ($model->seoDescription) {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->seoDescription), 'description');
} else {
    Yii::app()->clientScript->registerMetaTag("Получите бесплатную консультацию юриста. Ответы квалифицированных юристов на вопросы тематики " . CHtml::encode($model->name), 'description');
}

if ($model->seoKeywords) {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->seoKeywords), 'keywords');
}

Yii::app()->clientScript->registerLinkTag("canonical", NULL, Yii::app()->createUrl('/questionCategory/alias', $model->getUrl()));

$additionalTags = $model->getAdditionalMetaTags();
foreach ($additionalTags as $property => $content) {
    Yii::app()->clientScript->registerMetaTag($content, $property);
}

$this->breadcrumbs = array('Темы' => array('/cat'));

foreach ($ancestors as $ancestor) {
    $this->breadcrumbs[$ancestor->name] = Yii::app()->createUrl('questionCategory/alias', $ancestor->getUrl());
}
$this->breadcrumbs[] = $model->name;

?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>

<?php
if ($model->seoH1) {
    $pageTitle = CHtml::encode($model->seoH1);
} else {
    $pageTitle = CHtml::encode($model->name);
}
?>

<div class="row">

    <div class="col-sm-9">
        <div itemscope itemtype="http://schema.org/Article" >

            <div class="category-hero post-hero vert-margin40">
                   <!--  <img src="<?php echo $model->getImagePath(); ?>" alt="<?php echo $pageTitle; ?>"
                         title="<?php echo $pageTitle; ?>" class="img-responsive"/> -->
                <div class="text-over-hero">
                    <h1>
                        <span itemprop="name">
                        <?php
                        echo $pageTitle;
                        ?>

                        <?php
                        if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {
                            echo CHtml::link("<span class='glyphicon glyphicon-pencil'><span>", Yii::app()->createUrl('/admin/questionCategory/update', array('id' => $model->id)), array('target' => '_blank'));
                        }
                        ?>
                        </span>
                    </h1>
                </div>
            </div>
            <?php if ($model->files): ?>
                 <div class="row vert-margin40 ">
                    <div class="col-sm-7 text-right"><h3>Есть образцы документов для свободного скачивания:</h3></div>
                    <div class="col-sm-5 text-right">
                    <a href="#downloads" class="btn btn-default"><span class="glyphicon glyphicon-download-alt"></span> К образцам документов для скачивания</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($model->description1): ?>
                <article>
                    <div class="article vert-margin40" itemprop="articleBody">
                        <?php echo $model->description1; ?>
                    </div>
                </article>
            <?php endif; ?>

            <?php if ($model->files): ?>
                <div class="vert-margin40 blue-block inside25" id="downloads">
                    <h2><span class="glyphicon glyphicon-download-alt"></span> Образцы документов для скачивания <span
                                class="glyphicon glyphicon-download-alt"></span></h2>
                    <ol>
                        <?php foreach ($model->files as $file): ?>
                            <?php if (is_file(Yii::getPathOfAlias('webroot') . $file->getRelativePath())): ?>
                                <li style="font-size: 17px;">
                                    <?php echo CHtml::link($file->name, Yii::app()->urlManager->baseUrl . $file->getRelativePath(), ['target' => '_blank']); ?>
                                    <em class="text-muted" style="font-size: 14px;">
                                        (<?php echo round(filesize(Yii::getPathOfAlias('webroot') . $file->getRelativePath()) / 1024); ?>
                                        КБ)
                                    </em>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <div class="row vert-margin40">
                <div class="col-sm-12 right-align">
                    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                    <script src="//yastatic.net/share2/share.js"></script>
                    <div class="ya-share2"
                         data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
                </div>
            </div>


            <?php /*
            $this->widget('application.widgets.RecentCategories.RecentCategories', [
                'number' => 4,
                'template' => 'default1',
                'rootId' => $model->root,
                'title' => '<h2>Похожие статьи</h2>',
            ]); */
            ?>

        </div>

                <div class="vert-margin20">
                    <?php
                    // выводим виджет с формой
                    $this->widget('application.widgets.SimpleForm.SimpleForm', array(
                        'template' => 'gorizont',
                    ));
                    ?>
                </div>

		        <div class="popular-questions vert-margin40">
		            <h2>Примеры бесплатных онлайн-консультаций</h2>
		            <?php
		            $this->widget('application.widgets.PopularQuestions.PopularQuestions', [
		                'template' => 'default',
		                'cacheTime' => 10,
		            ]);
		            ?>
		        </div>




    </div>

    <div class="col-sm-3">

                <div class="vert-margin20">
                    <h4>Меню раздела</h4>
                    <?php
                    $this->widget('application.widgets.CategoriesMenu.CategoriesMenu', [
                        'category' => $model,
                    ]);
                    ?>
                </div>

                <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>


                    <div class="vert-margin20">
                        <?php
                        // выводим виджет с поиском вопросов
                        $this->widget('application.widgets.SearchQuestions.SearchQuestionsWidget', array());
                        ?>
                    </div>

                    <div class="vert-margin20">
                        <?php
                        // выводим виджет со статистикой ответов
                        $this->widget('application.widgets.MyAnswers.MyAnswers', array());
                        ?>
                    </div>
                <?php endif; ?>



                <?php if (Yii::app()->user->role != User::ROLE_JURIST): ?>

                    <div data-spy="" data-offset-top="200" class="hidden-xs">
                        
                        <div class="consult-phone-widget vert-margin20">
                            <h3>Горячая линия юридических консультаций по телефону</h3>
                            <!--
                            <h3>для Москвы и МО:</h3>
                            <p class="vert-margin20"><strong>8 499 255-69-85</strong></p>
                            <h3>для Санкт Петербурга и ЛО:</h3>
                            <p class="vert-margin20"><strong>8 812 466-87-81</strong></p>
                            <h3>для других регионов:</h3>
                            -->
                            <?php echo CHtml::link('Запрос на обратный звонок ', Yii::app()->createUrl('question/call'), array('class' => 'button button-green-border btn-block')); ?>
                        </div>
                        

                        <div class="question-docs-block vert-margin20">
                            <h3>Вы также можете задать свой вопрос и получить ответ прямо на сайте</h3>
                            <?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/')) ? CHtml::link('Задать вопрос online', Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=question-docs-block&utm_campaign=' . Yii::app()->controller->id, array('class' => 'button button-green-border btn-block')) : ''; ?>
                        </div>

                        <div class="question-docs-block vert-margin20">
                            <h3>Заказать юридический документ у профессиональных юристов</h3>
                            <?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/docs/')) ? CHtml::link("Заказать документ", Yii::app()->createUrl('question/docs'), array('class' => 'button button-green-border btn-block')) : '<span class="active">Заказать документы</span>'; ?>

                        </div>
                    </div>
                <?php endif; ?>

            <div class="vert-margin40">
                <?php
                $this->widget('application.widgets.RecentCategories.RecentCategories', [
                    'number' => 5,
                    'template' => 'default',
                    ]);
                ?>
            </div>
            <div class="inside article-preview">
                    <h3>Обсуждаемые новости</h3>
                    <?php
                    $this->widget('application.widgets.RecentPosts.RecentPosts', [
                        'number' => 8,
                        'order' => 'fresh_views',
                        'intervalDays' => 100,
                        'template' => 'default1',

                    ]);
                    ?>
                </div>
    </div>


</div>
