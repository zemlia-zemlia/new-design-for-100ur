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
        
        $transaction = new PartnerTransaction();
        $transaction->setScenario('pull');
        
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('partnerId' => Yii::app()->user->id, 'status' => PartnerTransaction::STATUS_COMPLETE));
        $criteria->order = "id DESC";
        
        $dataProvider = new CActiveDataProvider('PartnerTransaction', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        
        $requestsCriteria = new CDbCriteria();
        $requestsCriteria->addColumnCondition(array('partnerId' => Yii::app()->user->id, 'sum<'=>0));
        $requestsCriteria->order = "id DESC";
        
        $requestsDataProvider = new CActiveDataProvider('PartnerTransaction', array(
            'criteria' => $requestsCriteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        
        $currentUser = User::model()->findByPk(Yii::app()->user->id);
        
        // если это вебмастер, кешируем баланс, рассчитанный из транзакций вебмастера
        if($cachedBalance = Yii::app()->cache->get('webmaster_' . Yii::app()->user->id . '_balance')) {
            $balance = $cachedBalance;
        } else {
            $balance = $currentUser->calculateWebmasterBalance();
            Yii::app()->cache->set('webmaster_' . Yii::app()->user->id . '_balance', $balance, 60);
        }
        $hold = $currentUser->calculateWebmasterHold();
        
        
        if (isset($_POST['PartnerTransaction'])) {
            $transaction->attributes = $_POST['PartnerTransaction'];
            $transaction->partnerId = Yii::app()->user->id;
            $transaction->status = PartnerTransaction::STATUS_PENDING;
            
            $transaction->validate();
            
            if(abs($transaction->sum) > ($balance - $hold)) {
                $transaction->addError('sum', 'Недостаточно средств');
            }
            
            if(!$transaction->hasErrors()) {
                
                $transaction->sum = 0 - abs($transaction->sum);
                if($transaction->save()) {
                    $this->redirect(array('/webmaster/transaction/index', 'created' => 1));
                }
            } 
        }
        
        if($_GET['created'] == 1) {
            $justCreated = true;
        } else {
            $justCreated = false;
        }
        
        echo $this->render('index', array(
                'dataProvider'  =>  $dataProvider,
                'balance'       =>  $balance,
                'hold'          =>  $hold,
                'transaction'   =>  $transaction,
                'justCreated'   =>  $justCreated,
                'requestsDataProvider'  =>  $requestsDataProvider,
            ));
    }
    
    /**
     * Страница успешной отправки запроса на вывод средств
     */
    public function actionCreateSuccess()
    {
        echo $this->render('createSuccess');
    }
}