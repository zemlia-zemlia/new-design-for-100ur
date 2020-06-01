<?php


namespace App\services;

use CHtml;
use EFeed;
use Yii;

/**
 * Логика работы с RSS вопросов
 * Class QuestionRSSFeedService
 * @package App\services
 */
class QuestionRSSFeedService
{
    /**
     * Создает RSS фид из массива вопросов
     * @param array $questions Каждый элемент - ассоциативный массив
     * @param array $feedOptions
     * @return EFeed
     */
    public function createFeed(array $questions, array $feedOptions):\EFeed
    {
        // RSS 2.0 is the default type
        $feed = new EFeed();

        $feed->title = Yii::app()->name;
        $feed->description = 'Вопросы квалифицированным юристам';

        $feed->addChannelTag('language', 'ru-ru');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', $feedOptions['link']);

        foreach ($questions as $question) {
            $item = $feed->createNewItem();

            if ($question->answersCount) {
                $item->title = CHtml::encode($question['title']) . ' (' . $question['answersCount'] . ' ' . NumbersHelper::numForms($question['answersCount'], 'ответ', 'ответа', 'ответов') . ')';
            } else {
                $item->title = CHtml::encode($question['title']);
            }

            $item->link = Yii::app()->createUrl('question/view', ['id' => $question['id']]);
            $item->date = ($question['publishDate']) ? date(DATE_RSS, strtotime($question['publishDate'])) : date(DATE_RSS, strtotime($question['createDate']));
            $item->description = CHtml::encode($question['questionText']);

            $feed->addItem($item);
        }

        return $feed;
    }
}