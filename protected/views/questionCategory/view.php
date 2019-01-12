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
                    <img src="<?php echo $model->getImagePath(); ?>" alt="<?php echo $pageTitle; ?>"
                         title="<?php echo $pageTitle; ?>" class="img-responsive"/>
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

            <div class="row vert-margin10">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <?php if ($model->files): ?>
                        <a href="#downloads" class="btn btn-default"><span
                                    class="glyphicon glyphicon-download-alt"></span> К образцам документов для
                            скачивания</a>
                    <?php endif; ?>
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

            <div class="row vert-margin30">
                <div class="col-sm-12 right-align">
                    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                    <script src="//yastatic.net/share2/share.js"></script>
                    <div class="ya-share2"
                         data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
                </div>
            </div>

            <?php
            $this->widget('application.widgets.RecentCategories.RecentCategories', [
                'number' => 4,
                'template' => 'default1',
                'rootId' => $model->root,
                'title' => '<h2>Похожие статьи</h2>',
            ]);
            ?>

        </div>
    </div>
</div>
