<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */
//Yii::app()->clientScript
$urlToModal = Yii::app()->createUrl('fileCategory/createModalForObject/');
$urlToAttach = Yii::app()->createUrl('admin/docs/attachFilesToObject/');
$urlToDeAttach = Yii::app()->createUrl('admin/docs/deAttachFilesToObject/');
$js = "var urlToModal = '$urlToModal'; objId = '$model->id'; var urlToAttach = '$urlToAttach'; var urlToDeAttach = '$urlToDeAttach';" ;
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
     urlToModal1 =  urlToModal + ((catId != 0) ? '?id=' + catId : '');
      $.get(
  urlToModal1 ,
  function(msg) {
   
    $('#fileModal #category').html(msg);
    
  }
);
   
});

$('#fileModal').on('click', '.selectFile', function() {
var fileName = $(this).parents('tr').find('td:eq(0)').text();
$('#filesSelected').append('<p class="selectedFile" data="' + $(this).attr('data') + '">' + fileName + '</p>');

$('#fileModal').on('click', '#attachSelectedFiles', function() {
    var fileIds = [];
    
    $('.selectedFile').each(function() {
      fileIds.push($(this).attr('data'));
    });
    // console.log(fileIds);
      $.ajax({
    type: "POST",
    url:urlToAttach,
    dataType: "html",
    data: {fileIds: fileIds, objId: objId},
    success: function(msg){
        $('#files').html(msg);
         $('#fileModal').modal('hide');
  
       console.log(msg);
        
    },
    error:function(error) {
      alert('error');
    }
  });
    
});


});

$('#files').on('click', '#deattach', function(e) {
    e.preventDefault();
   var fileId = Number($(this).attr('data'));
   
         $.ajax({
    type: "POST",
    url:urlToDeAttach,
    dataType: "html",
    data: {fileId: fileId, objId: objId},
    success: function(msg){
        $('#files').html(msg);

  
       console.log(msg);
        
    },
    error:function(error) {
      alert('error');
    }
  });
});


$('#fileModal').on('click', '#linkPrev', function() {
    
    
       var catId = Number($(this).attr('data'));
     urlToModal2 =  urlToModal + ((catId != 0) ? '?id=' + catId : '');
 
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

                    <div id="filesSelected" class="col-lg-6">

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="attachSelectedFiles" class="btn btn-primary">Прикрепить выбранные файлы</button>


            </div>
        </div>
    </div>
</div>
