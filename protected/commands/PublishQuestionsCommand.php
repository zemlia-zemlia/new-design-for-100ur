<?php

/*
 * публикация вопросов, готовых к публикации
 */
class PublishQuestionsCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        
        $limit = 1; // сколько вопросов публикуем  
        $sqlCommandResult = Yii::app()->db
                ->createCommand('UPDATE {{question}} SET status='. Question::STATUS_PUBLISHED . ' WHERE status=' . Question::STATUS_MODERATED . ' ORDER BY publishDate LIMIT '. $limit)
                ->execute();

    }
    
}
?>