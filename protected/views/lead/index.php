<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Заявки. " . Yii::app()->name;
?>

<div  class="vert-margin20">
    <h1>Заявки на консультацию юриста</h1>
    <?php if (!$showMy): ?>
        <p> <small>
            Ниже Вы видите список заявок на получение консультации юриста. <br />
            Чтобы получить <strong>эксклюзивный</abbr> доступ</strong> к контактам клиента, нажмите кнопку "купить".<br/>
            <strong>Каждое обращение потенциального клиента продается только ОДИН раз и только ОДНОМУ юристу</strong><br/>
            <strong>Внимание!</strong> Если вы не нашли интересующий вас регион значит он весь выкупается в автоматическом режиме, напишите нам на admin@100yuristov.com об интересующем вас регионе.
        </small></p>
    <?php endif; ?>
</div>

<ul class="nav nav-tabs vert-margin40">
    <li role="presentation" class="<?php echo ($showMy == true || $showAuto == true) ? '' : 'active'; ?>">
        <?php echo CHtml::link('В продаже', Yii::app()->createUrl('/lead/index')); ?>
    </li>
    <li role="presentation" class="<?php echo ($showAuto == true) ? 'active' : ''; ?>">
        <?php echo CHtml::link('Автовыкуп лидов', Yii::app()->createUrl('/lead/index', ['auto' => 1])); ?>
    </li>
    <li role="presentation" class="<?php echo ($showMy == true) ? 'active' : ''; ?>">
        <?php echo CHtml::link('Выкупленные мной', Yii::app()->createUrl('/lead/index', ['my' => 1])); ?>
    </li>
</ul>

<?php if ($showAuto == false): ?>
    <?php
    echo $this->renderPartial('_leadList', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'showMy' => $showMy,
    ]);
    ?>
<?php else: ?>
    
<p>
    Совсем скоро Вы сможете настроить автоматический выкуп нужных Вам заявок.
</p>

<?php endif; ?>
