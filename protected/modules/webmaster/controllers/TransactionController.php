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
        $transactionsRows = Yii::app()->db->createCommand()
            ->select("*")
            ->from("{{partnertransaction}} t")
            ->leftJoin("{{lead100}} l", "t.leadId = l.id")
            ->where("t.partnerId=:userId", array(':userId' => Yii::app()->user->id))
            ->queryAll();
        
        echo $this->render('index');
    }
}