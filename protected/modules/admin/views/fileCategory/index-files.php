<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'App\models\Docs',
];

    foreach (Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
    }

$this->menu = [
    ['label' => 'Create App\models\Docs', 'url' => ['create']],
    ['label' => 'Manage App\models\Docs', 'url' => ['admin']],
];
?>
<?php //var_dump($this->menu);die;?>


<?php if ($category): ?>
<p><a id="linkPrev"  data="<?php echo $category ? $category->id : 0; ?>" href="#">Назад</a></p>
<?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <span class="hide" id="catId"><?php echo $category ? $category->id : 0; ?></span>
            <?php $this->renderPartial('_table', ['categories' => $categories]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($category) {
                $this->renderPartial('_table_files', ['files' => $category->files]);
            }
            ?>
        </div>
    </div>











