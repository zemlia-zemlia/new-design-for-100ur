<?php

namespace App\services;

use App\helpers\IpHelper;
use App\helpers\PhoneHelper;
use App\models\Lead;
use App\models\Leadsource;
use App\models\Question;
use App\models\Question2category;
use App\models\QuestionSearch;
use App\models\Town;
use App\models\User;
use App\repositories\QuestionRepository;
use CArrayDataProvider;
use CDbCriteria;
use CHttpException;
use DateTime;
use Yii;

/**
 * Бизнес логика работы с вопросами
 * Class QuestionService.
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
     * Создание лида из вопроса.
     *
     * @param Leadsource $source
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

    private function mapQuestionToCategories(
        int $categoryId,
        Question $question,
        array $allDirectionsHierarchy,
        ?Lead $lead
    ): void {
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
                $this->leadService->mapLeadToCategories($lead, $question->categories, $allDirectionsHierarchy, false);
            }
        }
    }

    /**
     * Заполняет атрибуты вопроса данными пользователя.
     */
    public function fillQuestionAttributesFromUserParams(Question $question, ?User $user): Question
    {
        if (is_null($user)) {
            return $question;
        }

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

    /**
     * @param int $qId
     * @param string $sId
     * @param array|null $postedParams
     * @return Question
     * @throws CHttpException
     */
    public function confirm(int $qId, string $sId, ?array $postedParams = null): Question
    {
        $question = $this->questionRepository->getQuestionByParams([
            'id' => $qId,
            'sessionId' => $sId,
        ]);

        /** @var Question $question */
        if (!$question) {
            throw new CHttpException(404, 'Не найден вопрос');
        }

        if ($question->email) {
            throw new CHttpException(400, 'У данного вопроса уже задан Email');
        }

        Yii::app()->user->setState('question_id', $question->id);

        if (isset($postedParams['email']) && $postedParams['email']) {
            $question->email = $postedParams['email'];

            if ($question->createAuthor() == false) {
                $question->addError('authorId', 'Ошибка при создании пользователя');
                return $question;
            }
            $question->save();
        }

        return $question;
    }

    /**
     * @param QuestionSearch $searchModel
     * @param int $limit
     * @param int $pagesize
     * @return CArrayDataProvider
     */
    public function getSearchDataProvider(QuestionSearch $searchModel, $limit = 100, $pagesize = 20): CArrayDataProvider
    {
        // лимит на количество найденных вопросов
        $searchModel->limit = $limit;

        if ($searchModel->townId) {
            $searchModel->townName = Town::getName($searchModel->townId);
        }

        $questions = $searchModel->search();

        return new CArrayDataProvider($questions, [
            'pagination' => [
                'pageSize' => $pagesize,
            ],
        ]);
    }
}