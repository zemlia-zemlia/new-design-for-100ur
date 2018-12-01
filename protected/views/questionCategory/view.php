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
//CustomFuncs::printr($additionalTags);exit;
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
    'homeLink' => CHtml::link('Главная', "/"),
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
    <div class="col-sm-3">
        <?php
        $this->widget('application.widgets.CategoriesMenu.CategoriesMenu', [
            'category' => $model,
        ]);
        ?>
    </div>
    <div class="col-sm-9">
        <div itemscope itemtype="http://schema.org/Article">

            <div class="category-hero post-hero">
                <?php if ($model->image): ?>
                    <img src="<?php echo $model->getImagePath(); ?>" alt="<?php echo $pageTitle; ?>"
                         title="<?php echo $pageTitle; ?>" class="img-responsive"/>
                <?php endif; ?>
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

            <?php if ($model->description1): ?>
                <article>
                    <div class="vert-margin30" itemprop="articleBody">
                        <?php echo $model->description1; ?>
                    </div>
                </article>
            <?php endif; ?>

            <?php if ($model->files): ?>
                <div class="vert-margin40">
                    <h2>Образцы документов для скачивания</h2>
                    <ol>
                        <?php foreach ($model->files as $file): ?>
                            <?php if (is_file(Yii::getPathOfAlias('webroot') . $file->getRelativePath())): ?>
                                <li>
                                    <?php echo CHtml::link($file->name, Yii::app()->urlManager->baseUrl . $file->getRelativePath(), ['target' => '_blank']); ?>
                                    <em class="text-muted">
                                        (<?php echo round(filesize(Yii::getPathOfAlias('webroot') . $file->getRelativePath()) / 1024); ?>
                                        КБ)
                                    </em>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <div class="row vert-margin30">
                <div class="col-sm-6 right-align hidden-xs">
                    <strong>Отправить статью:</strong>
                </div>
                <div class="col-sm-6 right-align">
                    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                    <script src="//yastatic.net/share2/share.js"></script>
                    <div class="ya-share2"
                         data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
                </div>
            </div>


            <?php if (Yii::app()->user->isGuest): ?>
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

            <?php if (Yii::app()->user->isGuest || Yii::app()->user->role == User::ROLE_CLIENT): ?>
                <div class="vert-margin30 blue-block inside">
                    <div class="row">
                        <div class="col-sm-8 center-align">
                            <h3>Ваш вопрос требует составления документа?</h3>
                            <p>Доверьте это опытным юристам, закажите документ прямо на сайте в режиме онлайн.</p>
                        </div>
                        <div class="col-sm-4 center-align">
                            <p></p>
                            <?php echo CHtml::link('Заказать документ', Yii::app()->createUrl('question/docs'), ['class' => 'yellow-button']); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($model->description2): ?>

                <?php echo $model->description2; ?>

            <?php endif; ?>

        </div>
    </div>
</div>
