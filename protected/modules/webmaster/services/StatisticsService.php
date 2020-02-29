<?php

namespace webmaster\services;
use function Aws\map;

/**
 * Класс для получения различных статистик
 * Class StatisticsService
 */
class StatisticsService
{
    public  $userId;

    public function getUserSources()
    {

        $sources = \Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{leadsource}}')
            ->where('userId=:userId', [':userId' => $this->userId])
            ->queryAll();
        $sourceIds = [];
        foreach ($sources as $ids){
            $sourceIds[] = $ids['id'];
        }
        return $sourceIds;
    }


    public function getAllLead()
    {

        $allLead = \Yii::app()->db->createCommand()
            ->select('*')
            ->from("{{lead}}")
            ->where("sourceId IN (:sourceId) AND question_date > NOW() - INTERVAL 30 DAY", [
                ':sourceId' => implode(',' , $this->getUserSources())
            ])
            ->queryAll();

        return $allLead;
    }


}
