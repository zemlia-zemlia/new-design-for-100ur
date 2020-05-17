<?php

namespace App\repositories;

use App\models\Lead;
use CActiveDataProvider;
use CDbCriteria;

/**
 * Инкапсуляция работы с БД для получения данных по лидам
 * Class LeadRepository
 * @package App\repositories
 */
class LeadRepository
{
    /**
     * Возвращает провайдер с данными найденных лидов
     * @param Lead $searchModel
     * @param array $searchAttributes Параметры поиска (key => value)
     * @return CActiveDataProvider
     */
    public function getDataProviderWithFilteredLeads(Lead $searchModel, array $searchAttributes): \CActiveDataProvider
    {
        $criteria = new CDbCriteria();

        $criteria->order = 't.id DESC';
        $statusId = isset($searchAttributes['status']) ? $searchAttributes['status'] : false;

        if (false !== $statusId) {
            $criteria->addColumnCondition(['t.leadStatus' => $statusId]);
            $criteria->addCondition('campaignId IS NOT NULL');
        }

        if ($searchAttributes['attributes']) {
            // если используется форма поиска по лидам
            $searchModel->attributes = $searchAttributes['attributes'];
            $dataProvider = $searchModel->search();
        } else {
            // если форма не использовалась
            $dataProvider = new CActiveDataProvider(Lead::class, [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => 50,
                    'params' => $searchAttributes['attributes'],
                ],
            ]);
        }

        return $dataProvider;
    }
}