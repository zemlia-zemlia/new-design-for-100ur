<?php

use App\Repositories\CampaignRepository;
use webmaster\services\StatisticsService;

class DefaultController extends Controller
{
    public $layout = '//lk/main';

    public function actionIndex()
    {
        $criteria = new CDbCriteria();

        $criteria->with = 'source';

        $mySourcesIds = [];
        $mySourcesIdsRows = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{leadsource}}')
            ->where('userId = :userId', [':userId' => Yii::app()->user->id])
            ->queryAll();
        foreach ($mySourcesIdsRows as $row) {
            $mySourcesIds[] = $row['id'];
        }

        // Найдем лиды, которые пришли из источников, которые привязаны к текущему пользователю
        $criteria->order = 't.id DESC';
        $criteria->addInCondition('sourceId', $mySourcesIds);
        $criteria->limit = 10;

        $leadsDataProvider = new CActiveDataProvider('App\models\Lead', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);

        // Найдем вопросы, которые пришли из источников, которые привязаны к текущему пользователю
        $questionCriteria = new CDbCriteria();
        $questionCriteria->limit = 10;
        $questionCriteria->order = 't.id DESC';
        $questionCriteria->addInCondition('sourceId', $mySourcesIds);

        $questionsDataProvider = new CActiveDataProvider('Question', [
            'criteria' => $questionCriteria,
            'pagination' => false,
        ]);

        $webmasterStat = new StatisticsService(Yii::app()->user->id);
        $showStatsForDays = 14;
        $statsStartDate = (new DateTime())->modify('-' . $showStatsForDays . ' day')->modify('midnight');
        $leadStatsByDates = $webmasterStat->getLeadsStatisticsByField('lead_date', $statsStartDate, 'desc');
        $leadStatsByRegions = $webmasterStat->getLeadsStatisticsByField('region_name', $statsStartDate);

        $statsFor30Days = $webmasterStat->getLeadsStatisticsByField('lead_date', (new DateTime())->modify('-29 day')->modify('midnight'), 'desc');

        // @todo заменить создание объекта на инъекцию при переходе на новый фреймворк
        $activeCampaignsCount = sizeof((new CampaignRepository())->getActiveCampaigns());

        $this->render('index', [
            'dataProvider' => $leadsDataProvider,
            'questionsDataProvider' => $questionsDataProvider,
            'leadStatsByDates' => $leadStatsByDates,
            'leadStatsByRegions' => $leadStatsByRegions,
            'statsFor30Days' => $statsFor30Days,
            'activeCampaignsCount' => $activeCampaignsCount,
        ]);
    }
}
