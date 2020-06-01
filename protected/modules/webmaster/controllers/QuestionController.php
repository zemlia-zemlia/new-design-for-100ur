<?php

use App\models\Question;

/**
 * Контроллер для работы с вопросами.
 */
class QuestionController extends Controller
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

        // Найдем вопросы, которые пришли из источников, которые привязаны к текущему пользователю
        $criteria->order = 't.id DESC';
        $criteria->addInCondition('sourceId', $mySourcesIds);

        $dataProvider = new CActiveDataProvider(Question::class, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Страница вопроса.
     *
     * @param int $id
     *
     * @throws CException
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $model = Question::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Лид не найден');
        }

        // проверим, что данный вопрос пришел из источника, привязанного к текущему пользователю
        $mySourcesIds = [];
        $mySourcesIdsRows = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{leadsource}}')
                ->where('userId = :userId', [':userId' => Yii::app()->user->id])
                ->queryAll();
        foreach ($mySourcesIdsRows as $row) {
            $mySourcesIds[] = $row['id'];
        }

        if ($model->source->userId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие вопросы');
        }

        $this->render('view', [
            'model' => $model,
        ]);
    }
}
