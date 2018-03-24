<?php

/**
 * Контроллер для работы с лидами зарегистрированным пользователям
 */
class LeadController extends Controller {

    public $layout = '//frontend/question';
    
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users 
                'actions' => array('index', 'view', 'buy'),
                'users' => array('@'),
                'expression'    =>  "Yii::app()->user->checkAccess(User::ROLE_JURIST)",
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Просмотр списка лидов
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;   
        $showMy = false;
        
        $criteria->order = 't.id DESC';
        $criteria->addCondition('question_date < NOW() - INTERVAL 20 MINUTE');
        
        if(isset($_GET['my'])) {
            $showMy = true;
            // ищем лиды, проданные текущему пользователю
            $criteria->addColumnCondition(['leadStatus' => Lead100::LEAD_STATUS_SENT, 'buyerId' => Yii::app()->user->id]);
        } else {
            // Найдем лиды, которые не разобраны (не проданы и не бракуются)
            $criteria->addColumnCondition(['leadStatus' => Lead100::LEAD_STATUS_DEFAULT]);
        }

        $dataProvider = new CActiveDataProvider('Lead100', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
        
        $this->render('index', array(
            'dataProvider'  => $dataProvider,
            'showMy'        => $showMy,
        ));
    }
    
    /**
     * Просмотр лида
     */
    public function actionView($id)
    {
        // если передан GET параметр autologin, попытаемся залогинить пользователя
        User::autologin($_GET);
        
        $model = Lead100::model()->findByPk($id);
        
        if(!($model->leadStatus == Lead100::LEAD_STATUS_DEFAULT || ($model->leadStatus == Lead100::LEAD_STATUS_SENT && $model->buyerId == Yii::app()->user->id))) {
            throw new CHttpException(403, 'У вас нет прав на просмотр данной заявки');
        }
        
        if(!$model) {
            throw new CHttpException(404, 'Заявка не найдена');
        }
        
        $this->render('view', [
            'model' => $model,
        ]);
    }
    
    /**
     * Покупка лида пользователем
     * @param integer $id id лида
     */
    public function actionBuy($id)
    {
        $model = Lead100::model()->findByPk($id);
        
        if(!$model) {
            throw new CHttpException(404, 'Заявка не найдена');
        }
        
        if($model->leadStatus != Lead100::LEAD_STATUS_DEFAULT) {
            throw new CHttpException(403, 'Эта заявка уже продана другому пользователю');
        }
        
        $leadPrice = $model->calculatePrices()[1];
        if(Yii::app()->user->balance < $leadPrice) {
            throw new CHttpException(400, 'У вас недостаточно средств для покупки этой заявки');
        }
        
        if($model->sendToBuyer(Yii::app()->user->id)) {
            return $this->redirect(['/lead/view', 'id' => $model->id]);
        } else {
            throw new CHttpException(500, 'В процессе покупки заявки произошла ошибка. Пожалуйста, обратитесь в техподдержку.');
        }
    }
}