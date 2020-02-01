<?php

/**
 * Отправка данных о турбо-сраницах в API яндекса
 * Class YandexTurboCommand
 */
class YandexTurboCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $turboApi = new TurboApi('AQAAAAAC0uqLAAUEeoJmgnrmUkc3sEaMzC4JonQ', (YII_DEV == true)?'DEBUG':'PRODUCTION');
        $userId = $turboApi->requestUserId();
        $host = $turboApi->requestHost();
        $turboApi->requestUploadAddress();

        $tasks = $this->getTasks(2000, 'false');
        $taskIds = [];

        foreach ($tasks as $task) {
            $taskIds[] = $turboApi->uploadRss($task);
        }

        file_put_contents(__DIR__ . '/output/tasks.txt', implode(PHP_EOL, $taskIds));
    }

    /**
     * Формирование XML страниц с сохранением в файлы
     */
    public function actionTestGetTasks()
    {
        $tasks = $this->getTasks(2000);

        foreach ($tasks as $taskNumber => $task) {
            file_put_contents(__DIR__ . '/output/' . $taskNumber . '.xml', $task);
        }
    }

    /**
     * Получение результатов обработки турбостраниц
     */
    public function actionGetStatus()
    {
        $turboApi = new TurboApi('AQAAAAAC0uqLAAUEeoJmgnrmUkc3sEaMzC4JonQ', '');
        $turboApi->requestUserId();
        $turboApi->requestHost();

        $taskIds = file(__DIR__ . '/output/tasks.txt');
        foreach ($taskIds as $taskId) {
            if (trim($taskId) != '') {
                $taskStatus[] = $taskId . ':' . $turboApi->getTask($taskId);
            }
        }

        file_put_contents(__DIR__ . '/output/tasks_results.txt', implode('', $taskStatus));
    }

    protected function getCategories($limit = 1000)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->order = 'publish_date DESC';
        $criteria->addCondition('description1 != "" AND seoH1!=""');

        $categories = QuestionCategory::model()->findAll($criteria);
        return $categories;
    }

    /**
     * Вытаскивает из базы массив вопросов с ответами
     * @param int $limit
     * @return array
     */
    protected function getQuestions($limit = 1000)
    {
        $questionsWithAnswersRows = Yii::app()->db->createCommand()
            ->select('q.id, q.questionText, q.title, q.authorName, a.answerText, a.authorId, u.name, u.lastName')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'a.questionId = q.id')
            ->leftJoin('{{user}} u', 'a.authorId = u.id')
            ->where('q.status IN (:status1, :status2) AND a.id IS NOT NULL', [':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED])
            ->order('q.id')
            ->limit($limit)
            ->queryAll();
        $questions = [];

        foreach ($questionsWithAnswersRows as $row) {
            $questions[$row['id']]['id'] = $row['id'];
            $questions[$row['id']]['text'] = $row['questionText'];
            $questions[$row['id']]['title'] = $row['title'];
            $questions[$row['id']]['authorName'] = $row['authorName'];

            $questions[$row['id']]['answers'][] = [
                'text' => $row['answerText'],
                'authorName' => $row['lastName'] . ' ' . $row['name'],
            ];
        }

        return $questions;
    }

    /**
     * @return array
     */
    private function getTasks($tasksLimit = 50, $turboEnabled = 'true')
    {
        $categories = $this->getCategories(4000);
        $questions = $this->getQuestions(30000);
        echo 'Categories: ' . sizeof($categories) . PHP_EOL;
        echo 'Questions: ' . sizeof($questions) . PHP_EOL;

        $turboPack = new TurboPack();

        foreach ($categories as $category) {
            $link = Yii::app()->createUrl('/questionCategory/alias', $category->getUrl());
            $taskItem = new TurboItem();

            $taskXML = '<item turbo="' . $turboEnabled . '"><link>' . $link . '</link>';
            $taskXML .= '<turbo:content><![CDATA[';
            $taskXML .= '<header>
                       <figure>
                           <img
                            src="' . Yii::app()->urlManager->baseUrl . $category->getImagePath() . '" />
                       </figure>
                       <h1>' . $category->seoH1 . '</h1>
                   </header>';
            $taskXML .= $category->description1;
            $taskXML .= ']]></turbo:content></item>' . PHP_EOL;

            $taskItem->setXml($taskXML);
            $turboPack->addItem($taskItem);
        }

        foreach ($questions as $question) {
            $link = Yii::app()->createUrl('/question/view', ['id' => $question['id']]);
            $taskItem = new TurboItem();

            $taskXML = '<item turbo="' . $turboEnabled . '"><link>' . $link . '</link>';
            $taskXML .= '<turbo:content><![CDATA[';
            $taskXML .= '<header>
                       <h1>' . $question['title'] . '</h1>
                   </header>';
            $taskXML .= '<p>' . $question['text'] . '</p>';
            $taskXML .= '<h2>Ответы юристов</h2>';

            foreach ($question['answers'] as $answer) {
                $taskXML .= '<p><strong>' . $answer['authorName'] . '</strong><br />' .
                    $answer['text'] . '</p>';
            }

            $taskXML .= ']]></turbo:content></item>' . PHP_EOL;

            $taskItem->setXml($taskXML);
            $turboPack->addItem($taskItem);
        }

        $tasks = $turboPack->getTasks($tasksLimit);
        return $tasks;
    }
}
