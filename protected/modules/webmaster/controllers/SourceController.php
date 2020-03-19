<?php

use App\models\Leadsource;

/**
 * Контроллер для работы вебмастера со своими источниками лидов.
 */
class SourceController extends Controller
{
    public $layout = '//lk/main';

    /**
     * Список моих источников.
     */
    public function actionIndex()
    {
        // выбираем источники, связанные с текущим пользователем
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['userId' => Yii::app()->user->id, 'type' => Leadsource::TYPE_LEAD]);
        $criteriaQ = new CDbCriteria();
        $criteriaQ->addColumnCondition(['userId' => Yii::app()->user->id, 'type' => Leadsource::TYPE_QUESTION]);

        $dataProvider = new CActiveDataProvider(
            Leadsource::class,
            [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ], ]
        );
        $dataProviderQ = new CActiveDataProvider(
            Leadsource::class,
            [
                'criteria' => $criteriaQ,
                'pagination' => [
                    'pageSize' => 20,
                ], ]
        );

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderQ' => $dataProviderQ,
        ]);
    }

    /**
     * Просмотр источника.
     *
     * @param type $id
     */
    public function actionView($id)
    {
        $model = Leadsource::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Источник не найден');
        }

        if ($model->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие источники');
        }

        $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Добавление источника.
     */
    public function actionCreate()
    {
        $model = new Leadsource();

        if (isset($_POST['App\models\Leadsource'])) {
            $model->attributes = $_POST['App\models\Leadsource'];

            // при создании источника генерируем его параметры для API
            $model->generateAppId();
            $model->generateSecretKey();

            // привязываем источник к текущему пользователю
            $model->userId = Yii::app()->user->id;

            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Редактирование источника.
     *
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = Leadsource::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Источник не найден');
        }

        if ($model->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие источники');
        }

        if (isset($_POST['App\models\Leadsource'])) {
            $model->attributes = $_POST['App\models\Leadsource'];

            if (!$model->appId) {
                $model->generateAppId();
            }
            if (!$model->secretKey) {
                $model->generateSecretKey();
            }

            $model->userId = Yii::app()->user->id;

            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }
}
