<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */
//Yii::app()->clientScript

$js = <<<JS
$('#attachFile').on('click' , function() {
 $('#fileModal').modal('show');
 
});


JS;

Yii::app()->clientScript->registerScript('myjquery', $js );



$this->setPageTitle("Редактирование категории вопросов " . $model->id . ". " . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/admin/category.js');

$ancestors = $model->ancestors()->findAll();
foreach ($ancestors as $ancestor) {
    $this->breadcrumbs[$ancestor->name] = Yii::app()->createUrl('admin/questionCategory/view', ['id' => $ancestor->id]);
}
$this->breadcrumbs[] = CHtml::encode($model->name);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));




?>

    <div class="vert-margin30">
        <h1>Редактирование категории вопросов

            <?php
            if (Yii::app()->user->role == User::ROLE_ROOT) {
                echo " ";
                echo CHtml::link("Удалить категорию", Yii::app()->createUrl('/admin/questionCategory/delete', array('id' => $model->id)), array('class' => 'btn btn-danger', 'onclick' => 'if(!confirm("Вы уверены?")) {return false;}else{return true;}'));
                echo " ";
            }
            ?>
        </h1>
    </div>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
)); ?>




<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
<!--                <button type="button" onclick="$('#exampleModal').modal('hide')" class="btn btn-primary">Продолжить покупки</button>-->
<!--                <a href="" id="modalCartLink" class="btn btn-primary">Перейти в корзину</a>-->
            </div>
        </div>
    </div>
</div>
