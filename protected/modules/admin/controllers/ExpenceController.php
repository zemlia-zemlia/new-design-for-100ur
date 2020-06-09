<?php

use App\models\Expence;
use App\modules\admin\controllers\AbstractAdminController;

/**
 * Управление информацией о расходах.
 */
class ExpenceController extends AbstractAdminController
{
    /**
     * Список расходов.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();

        $criteria->order = 'date DESC';

        $dataProvider = new CActiveDataProvider(Expence::class, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // добавление расхода
    public function actionCreate()
    {
        $model = new Expence();
        $model->type = Expence::TYPE_CALLS;
        $model->date = date('Y-m-d');

        if (isset($_POST['App\models\Expence'])) {
            $model->attributes = $_POST['App\models\Expence'];
            $model->expences *= 100;
            try {
                if ($model->save()) {
                    $this->redirect(['/admin/expence/index']);
                }
            } catch (CDbException $e) {
                throw new CHttpException(500, 'Не удалось сохранить расход, возможно, Вы пытаетесь продублировать существующую запись');
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    // добавление расхода
    public function actionUpdate($id)
    {
        $model = Expence::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Расход не найден');
        }

        if (isset($_POST['App\models\Expence'])) {
            $model->attributes = $_POST['App\models\Expence'];
            $model->expences *= 100;

            try {
                if ($model->save()) {
                    $this->redirect(['/admin/expence/index']);
                }
            } catch (CDbException $e) {
                throw new CHttpException(500, 'Не удалось сохранить расход, возможно, Вы пытаетесь продублировать существующую запись');
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = Expence::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Расход не найден');
        }

        if ($model->delete()) {
            $this->redirect(['/admin/expence/index']);
        } else {
            throw new CHttpException(500, 'Не удалось удалить расход');
        }
    }
}
