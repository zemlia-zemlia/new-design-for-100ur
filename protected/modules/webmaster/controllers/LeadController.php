<?php

use App\helpers\PhoneHelper;
use App\models\Lead;
use App\models\Leadsource;

class LeadController extends Controller
{
    public $layout = '//admin/main';

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

        $dataProvider = new CActiveDataProvider(Lead::class, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = Lead::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Лид не найден');
        }

        if ($model->source->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие лиды');
        }

        $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Добавление лида вебмастером
     */
    public function actionCreate()
    {
        $model = new Lead();

        // Проверим, есть ли источники у текущего пользователя
        $mySources = Leadsource::getSourcesArrayByUser(Yii::app()->user->id);

        if ($_GET['sourceId']) {
            $model->sourceId = (int) $_GET['sourceId'];
        }

        if (isset($_POST['App\models\Lead'])) {
            $model->attributes = $_POST['App\models\Lead'];
            $model->phone = PhoneHelper::normalizePhone($model->phone);

            // посчитаем цену покупки лида, исходя из города и региона,
            // уточним цену покупки лида с учетом коэффициента покупателя
            $prices = $model->calculatePrices();
            $model->buyPrice = $prices[0] ? $prices[0] * Yii::app()->user->priceCoeff : 0;

            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'mySources' => $mySources,
            'model' => $model,
        ]);
    }

    public function actionPrices()
    {
        $this->render('prices', []);
    }
}
