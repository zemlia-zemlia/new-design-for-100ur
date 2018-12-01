<?php
/**
* Управление информацией о расходах
*/

class ExpenceController extends Controller {

    public $layout = '//admin/main';

    /**
     * Список расходов
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        
        $criteria->order = "date DESC";
        
        $dataProvider = new CActiveDataProvider('Expence', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    // добавление расхода
    public function actionCreate()
    {
        $model = new Expence;
        $model->type = Expence::TYPE_CALLS;
        $model->date = date('Y-m-d');
        
        if (isset($_POST['Expence'])) {
            $model->attributes = $_POST['Expence'];
            try {
                if($model->save()) {
                    $this->redirect(array('/admin/expence/index'));
                }
            } catch(CDbException $e) {
                throw new CHttpException(500, 'Не удалось сохранить расход, возможно, Вы пытаетесь продублировать существующую запись');
            }
        }
        
        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    // добавление расхода
    public function actionUpdate($id)
    {
        $model = Expence::model()->findByPk($id);
        
        if(!$model) {
            throw new CHttpException(404, 'Расход не найден');
        }
        
        if (isset($_POST['Expence'])) {
            $model->attributes = $_POST['Expence'];
            try {
                if($model->save()) {
                    $this->redirect(array('/admin/expence/index'));
                }
            } catch(CDbException $e) {
                throw new CHttpException(500, 'Не удалось сохранить расход, возможно, Вы пытаетесь продублировать существующую запись');
            }
        }
        
        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    
    public function actionDelete($id)
    {
        $model = Expence::model()->findByPk($id);
        
        if(!$model) {
            throw new CHttpException(404, 'Расход не найден');
        }
        
        if($model->delete()) {
            $this->redirect(array('/admin/expence/index'));
        } else {
            throw new CHttpException(500, 'Не удалось удалить расход');
        }
    }
}
