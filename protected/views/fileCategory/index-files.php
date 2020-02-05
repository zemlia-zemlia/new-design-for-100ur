<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docs',
);



    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
    }




$this->menu=array(
	array('label'=>'Create Docs', 'url'=>array('create')),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>
<?php //var_dump($this->menu);die;?>






    <div class="row">
        <div class="col-lg-12">
            <span class="hide" id="catId"><?= $category ? $category->id : 0 ?></span>
            <?php $this->renderPartial('_table', ['categories' => $categories]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($category)
                $this->renderPartial('_table_files', ['files' => $category->files]);
            ?>
        </div>
    </div>











