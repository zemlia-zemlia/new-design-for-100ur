<?php

namespace App\repositories;

use App\models\Answer;
use App\models\Question;
use CActiveDataProvider;
use CDbCriteria;

class AnswerRepository
{
    /**
     * Получение провайдера ответов на вопрос
     * @param Question $question
     * @param array $providerCustomConfig Можно задать свойства провайдера
     * @return CActiveDataProvider
     */
    public function getAnswersDataProviderByQuestion(Question $question, $providerCustomConfig = []): CActiveDataProvider
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.id ASC';
        $criteria->with = 'comments';
        $criteria->addColumnCondition(['t.questionId' => $question->id]);

        $providerConfig = [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ];
        $providerConfig = array_merge($providerConfig, $providerCustomConfig);

        return new CActiveDataProvider(Answer::class, $providerConfig);
    }
}
