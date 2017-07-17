<?php

/**
 * Контроллер для работы вебмастера со своими источниками лидов
 */
class SourceController extends Controller {

    public $layout='//frontend/webmaster';
    
    
    /**
     * Список моих источников
     */
    public function actionIndex() {
        
        // выбираем источники, связанные с текущим пользователем
        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array('userId' => Yii::app()->user->id));
        
        $dataProvider = new CActiveDataProvider('Leadsource100', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ))
        );
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    /**
     * Просмотр источника
     * @param type $id
     */
    public function actionView($id) {
        
        $model = Leadsource100::model()->findByPk($id);
        
        if(!$model) {
            throw new CHttpException(404, 'Источник не найден');
        }
        
        if($model->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие источники');
        }
        
        $this->render('view', array(
            'model' => $model,
        ));
    }
    
    /**
     * Добавление источника
     */
    public function actionCreate() {
        $model = new Leadsource100;

        if (isset($_POST['Leadsource100'])) {
            $model->attributes = $_POST['Leadsource100'];
            
            // при создании источника генерируем его параметры для API
            $model->generateAppId();
            $model->generateSecretKey();
            
            // привязываем источник к текущему пользователю
            $model->userId = Yii::app()->user->id;
            
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Редактирование источника
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        
        $model = Leadsource100::model()->findByPk($id);
        
        if(!$model) {
            throw new CHttpException(404, 'Источник не найден');
        }
        
        if($model->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие источники');
        }

        if (isset($_POST['Leadsource100'])) {
            $model->attributes = $_POST['Leadsource100'];
            
            if (!$model->appId) {
                $model->generateAppId();
            }
            if (!$model->secretKey) {
                $model->generateSecretKey();
            }
            
            $model->userId = Yii::app()->user->id;

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

}