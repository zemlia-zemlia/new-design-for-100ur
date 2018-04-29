<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Заявки. " . Yii::app()->name;
?>

<div  class="vert-margin20">
    <h1>Заявки на консультацию юриста</h1>
    <?php if (!$showMy): ?>
        <p>
            Ниже Вы видите список заявок на получение консультации юриста. <br />
            Чтобы получить <strong><abbr title="Каждая заявка продается только ОДИН раз и только ОДНОМУ юристу">эксклюзивный</abbr> доступ</strong> к контактам клиента, купите интересующую заявку.
        </p>
    <?php endif; ?>
</div>

<ul class="nav nav-tabs vert-margin40">
    <li role="presentation" class="<?php echo ($showMy == true || $showAuto == true) ? '' : 'active'; ?>">
        <?php echo CHtml::link('Доступные', Yii::app()->createUrl('/lead/index')); ?>
    </li>
    <li role="presentation" class="<?php echo ($showAuto == true) ? 'active' : ''; ?>">
        <?php echo CHtml::link('Автовыкуп', Yii::app()->createUrl('/lead/index', ['auto' => 1])); ?>
    </li>
    <li role="presentation" class="<?php echo ($showMy == true) ? 'active' : ''; ?>">
        <?php echo CHtml::link('Выкупленные', Yii::app()->createUrl('/lead/index', ['my' => 1])); ?>
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
