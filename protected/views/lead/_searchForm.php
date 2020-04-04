<div class="vert-margin30 inside text-right">
    <?php

    use App\models\Region;

    $form = $this->beginWidget('CActiveForm', [
            'id' => 'lead-search-form',
            'method' => 'GET',
            'action' => Yii::app()->createUrl('lead/index'),
            'htmlOptions' => ['class' => 'form-inline'],
            'enableAjaxValidation' => false,
        ]);
    ?>
    
    <div class="form-group">
        <?php
        echo $form->dropDownList($model, 'regionId', [0 => 'Все регионы'] + Region::getAllRegions(), [
            'class' => 'form-control',
        ]);
        ?>
    </div>


    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton('Найти заявки', ['class' => 'btn btn-primary']); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>