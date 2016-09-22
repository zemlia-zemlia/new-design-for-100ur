<?php foreach ($users as $user):?>

    <div class="col-sm-4 center-align vert-margin30">
        <img src="<?php echo $user->getAvatarUrl();?>" class="img-responsive center-block" />
        <p>
            <?php echo CHtml::encode($user->name . ' ' . $user->lastName);?>
        </p>
        <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=<?php echo CHtml::encode($user->name . '_' . $user->lastName);?>" class="btn btn-warning btn-xs" rel="nofollow">Получить консультацию</a>

    </div>

<?php endforeach; ?>
