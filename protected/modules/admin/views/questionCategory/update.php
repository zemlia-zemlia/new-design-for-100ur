<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle("Редактирование категории вопросов " . $model->id . ". " . Yii::app()->name);


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