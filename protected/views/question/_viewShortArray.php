<div class="row">

    <div class="col-sm-9">
        <p><?php echo CHtml::link(CHtml::encode($data['title']), Yii::app()->createUrl('question/view', array('id'=>$data['id'])));?></p>
    </div>
    <div class="col-sm-3">

        <?php if ($data['counter'] == 1) {
    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>Есть ответ</span>";
} elseif ($data['counter']>1) {
    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>" . $data['counter'] . ' ' . NumbersHelper::numForms($data['counter'], 'ответ', 'ответа', 'ответов') . "</span>";
} else {
    echo "Нет ответа";
}
        ?>

    </div>
</div>