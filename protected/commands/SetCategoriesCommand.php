<?php

/*
 * команда ищет вопросы, подходящие к категориям по ключевым словам и создает
 * связки вопрос-категория
 */
class SetCategoriesCommand extends CConsoleCommand
{
    public $keys2categories;

    public function actionIndex()
    {
        // получаем массив ключевых слов и соответствующих категорий
        $keys2categories = QuestionCategory::keys2categories();

        foreach ($this->keys2categories as $key => $categoryId) {
            $questionsIds = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('{{question}}')
                    ->where('status IN (' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED . ', ' . Question::STATUS_MODERATED . ') AND questionText LIKE "%' . $key . '%"')
                    ->queryAll();

            echo $key . PHP_EOL;
            foreach ($questionsIds as $questionRow) {
                echo $questionRow['id'] . PHP_EOL;
                try {
                    Yii::app()->db->createCommand()
                        ->insert('{{question2category}}', ['cId' => $categoryId, 'qId' => $questionRow['id']]);
                } catch (CDbException $e) {
                    // дублирование связей вопрос-категория, не записываем
                }
            }
        }
    }
}
