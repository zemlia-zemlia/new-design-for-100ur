<?php
$this->setPageTitle('Ошибка при покупке заявки.' . Yii::app()->name);
?>

<h1>При покупке заявки произошла ошибка</h1>
<?php if (is_array($errors)): ?>
    <ul>
        <?php foreach ($errors as $errorType): ?>
            <?php foreach ($errorType as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
