<?php
$this->setPageTitle("Быстрое редактирование вопроса " . $model->id);
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');

?>

<h1>Редактирование вопроса <?php echo $model->id; ?> (осталось <?php echo $questionsCount;?>)</h1>
<p class="text-center">
    Вы отредактировали <?php  echo $questionsModeratedByMeCount . ' ' . CustomFuncs::numForms($questionsModeratedByMeCount, 'вопрос', 'вопроса', 'вопросов');?>
</p>

<?php echo $this->renderPartial('_formModerate', array(
        'model'         =>  $model,
    )); ?>


<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
<table class="table">
    <tr>
        <th>Модератор</th>
        <th>Вопросов</th>
    </tr>
    <?php foreach($moderatorsStats as $moderator):?>
    <tr>
        <td><?php echo $moderator['name'] . ' ' . $moderator['lastName'];?></td>
        <td><?php echo $moderator['counter'];?></td>
    </tr>
    <?php endforeach;?>
</table>
<?php endif; ?>
