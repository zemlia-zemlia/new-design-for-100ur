<div class="text-center">
    <?php
    $this->widget(UloginWidget::class, [
        'params' => [
            'redirect' => Yii::app()->createUrl('ulogin/login'),
        ]
    ]);
    ?>
</div>