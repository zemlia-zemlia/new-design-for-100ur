<div class="post-comment">
    <div class="post-comment-body">
        <p>
        <?php echo CHtml::encode($data->author->name);?>
        <span class="muted"><?php echo CustomFuncs::niceDate($data->dateTime);?></span>
        </p>
        <?php echo nl2br(CHtml::encode($data->text)); ?>
    </div>
    <div class="clearfix"></div>
</div>