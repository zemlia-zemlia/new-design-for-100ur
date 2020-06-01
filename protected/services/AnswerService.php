<?php

namespace App\services;

use App\models\Answer;
use App\models\Question;
use App\models\User;
use App\repositories\AnswerRepository;
use App\repositories\UserRepository;
use CHttpException;
use WebUser;

/**
 * Инкапсуляция бизнес логики работы с ответами
 * Class AnswerService.
 */
class AnswerService
{
    /** @var AnswerRepository */
    private $answerRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(AnswerRepository $answerRepository, UserRepository $userRepository)
    {
        $this->answerRepository = $answerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CHttpException
     */
    public function createAnswer(array $attributes, Question $question, WebUser $currentUser): Answer
    {
        $answer = new Answer();

        if (!$currentUser->checkAccess(User::ROLE_JURIST)) {
            throw new CHttpException(403, 'Для того, чтобы отвечать на вопросы вы должны залогиниться на сайте как юрист');
        }

        if ($currentUser->checkAccess(User::ROLE_ROOT)) {
            $answer->setScenario('addVideo');
        }

        // отправлен ответ, сохраним его
        $answer->attributes = $attributes;
        $answer->authorId = $currentUser->id;
        $answer->questionId = $question->id;
        $answer->datetime = date('Y-m-d H:i:s');

        if ($answer->save()) {
            // записываем время ответа в запись о пользователе
            $this->userRepository->saveLastAnswerTs($currentUser->getModel(), $answer->datetime);
        }

        return $answer;
    }
}
