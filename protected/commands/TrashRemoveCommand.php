<?php


class TrashRemoveCommand extends CConsoleCommand
{
    /**
     * Удаление старых недозаполненных вопросов
     * @throws Exception
     */
    public function actionIncompleteQuestions()
    {
        Yii::app()->db
            ->createCommand()
            ->delete('{{question}}', 'status=:status AND createDate < :startDate', [
                ':status' => Question::STATUS_PRESAVE,
                ':startDate' => (new DateTime())->modify('-30 days')->format('Y-m-d'),
            ]);
    }
}
