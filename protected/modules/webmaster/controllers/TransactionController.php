<?php

/**
 * Страницы раздела транзакций вебмастера
 */
class TransactionController extends Controller {

    public $layout='//frontend/webmaster';
    
    /**
     * Список транзакций вебмастера
     */
    public function actionIndex() {
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('partnerId' => Yii::app()->user->id));
        $criteria->order = "id DESC";
        
        $dataProvider = new CActiveDataProvider('PartnerTransaction', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        
        echo $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
    }
}