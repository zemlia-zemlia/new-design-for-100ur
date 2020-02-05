<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */
//Yii::app()->clientScript
$urlToModal = Yii::app()->createUrl('fileCategory/createModalForObject/');
$js = "var urlToModal = '$urlToModal';" ;
$js .= <<<JS
var catId = Number($('#catId').text());
 urlToModal =  urlToModal + ((catId != 0) ? '?id=' + catId : '');
$('#attachFile').on('click' , function() {
  $.get(
  urlToModal ,
  function(msg) {
    $('#fileModal #category').html(msg);
    $('#fileModal').modal('show');
  }
);
});
$('#fileModal').on('click' , '.catLink',  function() {
    var catId = Number($(this).attr('id'));
     urlToModal =  urlToModal + ((catId != 0) ? '?id=' + catId : '');
      $.get(
  urlToModal ,
  function(msg) {
   
    $('#fileModal #category').html(msg);
    
  }
);
   
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
                <h3>Файлы</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  id="category"  class="col-lg-6">

                    </div>

                    <div class="col-lg-6">

                    </div>

                </div>

            </div>
            <div class="modal-footer">
<!--                <button type="button" onclick="$('#exampleModal').modal('hide')" class="btn btn-primary">Продолжить покупки</button>-->
<!--                <a href="" id="modalCartLink" class="btn btn-primary">Перейти в корзину</a>-->
            </div>
        </div>
    </div>
</div>
