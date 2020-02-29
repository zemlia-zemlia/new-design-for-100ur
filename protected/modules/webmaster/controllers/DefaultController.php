<?php

use webmaster\services\StatisticsService;

class DefaultController extends Controller
{
    public $layout = '//lk/main';

    public function actionIndex()
    {
        $criteria = new CDbCriteria;

        $criteria->with = "source";

        $mySourcesIds = array();
        $mySourcesIdsRows = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{leadsource}}')
            ->where('userId = :userId', array(':userId' => Yii::app()->user->id))
            ->queryAll();
        foreach ($mySourcesIdsRows as $row) {
            $mySourcesIds[] = $row['id'];
        }

        // Найдем лиды, которые пришли из источников, которые привязаны к текущему пользователю
        $criteria->order = 't.id DESC';
        $criteria->addInCondition('sourceId', $mySourcesIds);
        $criteria->limit = 10;

        $leadsDataProvider = new CActiveDataProvider('Lead', array(
            'criteria' => $criteria,
            'pagination' => false,
        ));

        // Найдем вопросы, которые пришли из источников, которые привязаны к текущему пользователю
        $questionCriteria = new CDbCriteria();
        $questionCriteria->limit = 10;
        $questionCriteria->order = 't.id DESC';
        $questionCriteria->addInCondition('sourceId', $mySourcesIds);

        $questionsDataProvider = new CActiveDataProvider('Question', array(
            'criteria' => $questionCriteria,
            'pagination' => false,
        ));

        $webmasterStat = new StatisticsService(Yii::app()->user->id);
        $leadStatsByDates = $webmasterStat->getLeadsStatisticsByField('lead_date', (new DateTime())->modify('-14 day')->modify('midnight'), 'desc');
        $leadStatsByRegions = $webmasterStat->getLeadsStatisticsByField('region_name', (new DateTime())->modify('-14 day')->modify('midnight'));

        $this->render('index', array(
            'dataProvider' => $leadsDataProvider,
            'questionsDataProvider' => $questionsDataProvider,
            'stat' => $webmasterStat,
            'leadStatsByDates' => $leadStatsByDates,
            'leadStatsByRegions' => $leadStatsByRegions,
        ));
    }
}
