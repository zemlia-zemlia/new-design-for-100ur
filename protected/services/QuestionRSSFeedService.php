<?php


namespace App\services;

use App\dto\QuestionRssItemDto;
use App\helpers\NumbersHelper;
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
            /** @var QuestionRssItemDto $question */
            $item = $feed->createNewItem();

            if ($question->getAnswersCount()) {
                $item->title = CHtml::encode($question->getTitle()) . ' (' . $question->getAnswersCount() . ' ' . NumbersHelper::numForms($question->getAnswersCount(), 'ответ', 'ответа', 'ответов') . ')';
            } else {
                $item->title = CHtml::encode($question->getTitle());
            }

            $item->link = Yii::app()->createUrl('question/view', ['id' => $question->getId()]);
            $item->date = ($question->getPublishDate()) ? date(DATE_RSS, strtotime($question->getPublishDate())) : date(DATE_RSS, strtotime($question->getCreateDate()));
            $item->description = CHtml::encode($question->getQuestionText());

            $feed->addItem($item);
        }

        return $feed;
    }
}