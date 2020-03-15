<?php
/* @var $this ContactController */

use App\helpers\DateHelper;

/* @var $model Contact */

$this->setPageTitle(CHtml::encode($model->name) . '. Просмотр заявки.');

$this->breadcrumbs = [
    'Заявки' => ['lead/index'],
];

if ($model->buyerId == Yii::app()->user->id) {
    $this->breadcrumbs['Мои заявки'] = ['lead/index', 'my' => 1];
}

$this->breadcrumbs[] = CHtml::encode($model->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Кабинет', (User::ROLE_JURIST == Yii::app()->user->role) ? '/user/feed' : '/buyer/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<h1><?php echo CHtml::encode($model->name); ?></h1>

<?php if ($model->buyerId == Yii::app()->user->id): ?>
    <div class="alert alert-info">
        Вы купили эту заявку <?php echo DateHelper::niceDate($model->deliveryTime, true, false); ?>
        за <?php echo $model->price; ?> руб.
    </div>
<?php endif; ?>

<table class="table table-bordered">
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('id'); ?></strong></td>
        <td><?php echo $model->id; ?></td>
    </tr>

    <tr>
        <td><strong><?php echo $model->getAttributeLabel('question_date'); ?></strong></td>
        <td><?php echo DateHelper::niceDate($model->question_date); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('question'); ?></strong></td>
        <td><?php echo nl2br(CHtml::encode($model->question)); ?></td>
    </tr>

    <?php if (Lead::LEAD_STATUS_DEFAULT == $model->leadStatus): ?>
        <?php $sellPrice = (int) $model->calculatePrices()[1]; ?>
        <?php if ($sellPrice > 0): ?>
            <tr>
                <td colspan="2" class="text-center">
                    <p>
                        <strong>Заинтересовала заявка? Купите её за <?php echo $sellPrice; ?> руб.</strong>

                        <?php
                        $buyLinkAttributes = ['class' => 'btn btn-info btn-xs', 'onclick' => 'return confirm("Купить эту заявку за ' . $sellPrice . ' рублей?")'];
                        if ($sellPrice > Yii::app()->user->balance) {
                            $buyLinkAttributes['disabled'] = 'disabled';
                        }
                        ?>
                        <?php echo CHtml::link('Купить', Yii::app()->createUrl('lead/buy', ['id' => $model->id]), $buyLinkAttributes); ?>

                    </p>
                    <p>
                        После покупки другие юристы не смогут увидеть эту заявку.
                    </p>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>

    <tr>
        <td><strong><?php echo $model->getAttributeLabel('phone'); ?></strong></td>
        <td>
            <?php if (Lead::LEAD_STATUS_SENT == $model->leadStatus && $model->buyerId == Yii::app()->user->id): ?>
                <?php echo $model->phone; ?>
            <?php else: ?>
                <span class="text-danger">Купите эту заявку, чтобы получить доступ к контактной информации клиента</span>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td><strong><?php echo $model->getAttributeLabel('email'); ?></strong></td>
        <td>
            <?php if (Lead::LEAD_STATUS_SENT == $model->leadStatus && $model->buyerId == Yii::app()->user->id): ?>
                <?php echo $model->email; ?>
            <?php else: ?>
                <span class="text-danger">Купите эту заявку, чтобы получить доступ к контактной информации клиента</span>
            <?php endif; ?>
        </td>
    </tr

    <tr>
        <td><strong><?php echo $model->getAttributeLabel('town'); ?></strong></td>
        <td><?php echo $model->town->name; ?></td>
    </tr>
</table>    