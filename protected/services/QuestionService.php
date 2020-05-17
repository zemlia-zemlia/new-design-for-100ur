<?php

namespace App\services;

use App\helpers\IpHelper;
use App\helpers\PhoneHelper;
use App\models\Lead;
use App\models\Lead2Category;
use App\models\Leadsource;
use App\models\Question;
use App\models\Question2category;
use App\models\User;
use App\repositories\QuestionRepository;
use CDbCriteria;
use DateTime;
use Yii;

/**
 * Бизнес логика работы с вопросами
 * Class QuestionService
 * @package App\services
 */
class QuestionService
{
    /** @var QuestionRepository */
    private $questionRepository;

    /** @var LeadService */
    private $leadService;

    public function __construct(QuestionRepository $questionRepository, LeadService $leadService)
    {
        $this->questionRepository = $questionRepository;
        $this->leadService = $leadService;
    }

    /**
     * @param array $attributes
     * @param array $allDirectionsHierarchy
     * @return Question
     * @throws \CHttpException
     */
    public function createQuestion(array $attributes, array $allDirectionsHierarchy): Question
    {
        $question = new Question();
        $question->attributes = $attributes;
        $question->phone = preg_replace('/([^0-9])/i', '', $question->phone);

        $source = null;
        // если пользователь пришел по партнерской ссылке, запишем в вопрос id источника
        if (Yii::app()->user->getState('sourceId')) {
            $source = Leadsource::model()->findByPk(Yii::app()->user->getState('sourceId'));
            if (Leadsource::TYPE_QUESTION == $source->type) {
                $question->sourceId = Yii::app()->user->getState('sourceId');
                $question->buyPrice = Yii::app()->params['questionPrice'];
            }
        }

        // Вопрос пришел из формы на главной, данных для сохранения недостаточно
        if ('' == $question->sessionId && '' != $question->questionText && '' != $question->authorName) {
            if (!$question->preSave()) {
                // если вопрос не предсохранился, очищаем свойство sessionId
                $question->sessionId = '';
            }
        } else {
            /*
             * если вопрос был предсохранен, создадим объект Question из записи в базе,
             * чтобы при сохранении вопроса произошел update записи
             */
            $question = $this->restorePresavedQuestion($question, $attributes);
        }

        $question->setScenario('create');
        $question->validate();

        if (empty($question->getErrors())) {
            // Создаем лид из вопроса
            $lead = $this->createLeadFromQuestion($question, $source);

            if ($lead->save()) {
                // Если клиент задает второй вопрос и уже подтвердил почту, избавим его от необходимости подтверждать повторно
                if (Yii::app()->user->email && 1 == Yii::app()->user->active100) {
                    $question->status = Question::STATUS_CHECK;
                    $question->authorId = Yii::app()->user->id;
                    $question->email = Yii::app()->user->email;
                    $question->publishDate = (new DateTime())->format('Y-m-d H:i:s');
                } else {
                    $question->status = Question::STATUS_NEW;
                }

                if ($question->save()) {
                    // после сохранения вопроса удаляем id источника из сессии, чтобы вебмастер не добавил несколько вопросов
                    Yii::app()->user->setState('sourceId', null);
                    $lead->questionId = $question->id;
                    $lead->save();

                    // сохраним категории, к которым относится вопрос, если категория указана
                    if ($attributes['categories']) {
                        $this->mapQuestionToCategories($attributes['categories'], $question, $allDirectionsHierarchy, $lead);
                    }
                }
            }
        }

        return $question;
    }

    /**
     * @param Question $question
     * @param array $attributes
     * @return Question
     */
    private function restorePresavedQuestion(Question $question, array $attributes): Question
    {
        if ('' != $question->sessionId) {
            $findQuestionCriteria = new CDbCriteria();
            $findQuestionCriteria->addColumnCondition([
                'sessionId' => $question->sessionId,
            ]);
            $existingQuestion = Question::model()->find($findQuestionCriteria);
            if ($existingQuestion instanceof Question) {
                $question = $existingQuestion;
            }
        }
        $question->attributes = $attributes;
        $question->phone = PhoneHelper::normalizePhone($question->phone);
        $question->status = Question::STATUS_NEW;
        $question->ip = IpHelper::getUserIP();
        $question->townIdByIP = Yii::app()->user->getState('currentTownId');

        return $question;
    }

    /**
     * Создание лида из вопроса
     * @param Question $question
     * @param Leadsource $source
     * @return Lead
     */
    private function createLeadFromQuestion(Question $question, ?Leadsource $source): Lead
    {
        // Создаем лид
        $lead = new Lead();
        $lead->name = $question->authorName;
        $lead->question = $question->questionText;
        $lead->phone = $question->phone;
        $lead->email = $question->email;
        $lead->townId = $question->townId;

        if ($source && Leadsource::TYPE_LEAD == $source->type) {
            $lead->sourceId = $source->id;
            // посчитаем цену покупки лида, исходя из города и региона
            $prices = $lead->calculatePrices();
            if ($prices[0]) {
                $lead->buyPrice = $prices[0];
            } else {
                $lead->buyPrice = 0;
            }
        } else {
            $lead->sourceId = 3; // 100 юристов
        }

        $lead->leadStatus = Lead::LEAD_STATUS_DEFAULT; // по умолчанию лид никуда не отправляем

        $duplicates = $lead->findDublicates(86400);
        if ($duplicates) {
            $lead->leadStatus = Lead::LEAD_STATUS_DUPLICATE;
        }

        return $lead;
    }

    /**
     * @param int $categoryId
     * @param Question $question
     * @param array $allDirectionsHierarchy
     * @param Lead|null $lead
     */
    private function mapQuestionToCategories(
        int $categoryId,
        Question $question,
        array $allDirectionsHierarchy,
        ?Lead $lead
    ): void
    {
        if ($categoryId) {
            $q2cat = new Question2category();
            $q2cat->qId = $question->id;
            $q2cat->cId = $categoryId;
            // сохраняем указанную категорию
            if ($q2cat->save()) {
                // проверим, не является ли указанная категория дочерней
                // если является, найдем ее родителя и запишем в категории вопроса
                foreach ($allDirectionsHierarchy as $parentId => $parentCategory) {
                    if (!$parentCategory['children']) {
                        continue;
                    }

                    foreach ($parentCategory['children'] as $childId => $childCategory) {
                        if ($childId == $categoryId) {
                            $q2cat = new Question2category();
                            $q2cat->qId = $question->id;
                            $q2cat->cId = $parentId;
                            $q2cat->save();
                            break;
                        }
                    }
                }
            }

            if ($lead instanceof Lead) {
                $this->leadService->mapLeadToCategories($lead, $question->categories);
            }
        }
    }

    /**
     * Заполняет атрибуты вопроса данными пользователя
     * @param Question $question
     * @param User $user
     * @return Question
     */
    public function fillQuestionAttributesFromUserParams(Question $question, User $user): Question
    {
        if (!$question->authorName && $user->name) {
            $question->authorName = $user->name;
        }

        if (!$question->phone && $user->phone) {
            $question->phone = $user->phone;
        }

        if (!$question->townId && $user->townId) {
            $question->townId = $user->townId;
        }

        return $question;
    }
}
