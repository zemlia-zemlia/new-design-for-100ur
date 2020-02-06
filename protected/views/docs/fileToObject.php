

<?php if (is_array($model->docs)):
    foreach ($model->docs as $doc): ?>
        <div>
            <h6><?php echo CHtml::link(CHtml::encode($doc->name), '/admin/docs/download/?id=' . $doc->id, ['target' => '_blank']); ?>(<?php echo CHtml::encode($doc->downloads_count); ?>)
                <a href="">удалить</a></h6>

        </div>
    <?php endforeach;
endif; ?>