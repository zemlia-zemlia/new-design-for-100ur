<?php
$sources = Leadsource::getSourcesByUser(Yii::app()->user->id); ?>

    <div class="">
        <h1>Мои источники</h1>
    </div>

<?php if (0 == sizeof($sources)): ?>
    <p>
        Для начала заработка создайте хотя бы один источник лидов или трафика
    </p>
    <?php echo CHtml::link('Создать источник', Yii::app()->createUrl('/webmaster/source/create'), ['class' => 'btn btn-block btn-primary']); ?>
<?php endif; ?>
<?php foreach ($sources as $source): ?>
    <div class="flat-panel">
        <div class="inside">
            <h4>
                <?php echo CHtml::link($source->name, Yii::app()->createUrl('/webmaster/source/view', ['id' => $source->id])); ?>
            </h4>
            <p class="text-center">
                <small>Привлекаем <?php echo $source->getTypeName(); ?>
                    <?php if (Leadsource::TYPE_LEAD == $source->type): ?> <br>
                        <?php echo CHtml::link('Добавить лид вручную', Yii::app()->createUrl('/webmaster/lead/create', ['sourceId' => $source->id])); ?>
                    <?php endif; ?>
                </small>
            </p>
            <?php if ($source->description): ?>
                <p class="text-center">
                    <?php echo CHtml::encode($source->description); ?>
                </p>
            <?php endif; ?>

        </div>
    </div><br/>
<?php endforeach; ?>