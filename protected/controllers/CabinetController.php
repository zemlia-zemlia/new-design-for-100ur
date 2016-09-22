<?php

class CabinetController extends Controller
{

	public $layout='//frontend/cabinet';

        
        public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
        
        /**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
            return array(
                
                array('allow',  // разрешаем доступ только авторизованным пользователям
                        'actions'=>array('index', 'leads', 'viewLead', 'campaign', 'brakLead'),
                        'users'=>array('@'),
                ),
                array('deny',  // запрещаем все, что не разрешено
                        'users'=>array('*'),
                ),
            );
	}
        
        
	
	
        // главная страница кабинета
        public function actionIndex()
	{
            // выберем кампании текущего пользователя
            
            $criteriaCampigns = new CDbCriteria;
            
            $criteriaCampigns->addColumnCondition(array('buyerId'=>Yii::app()->user->id));
            
            if(!isset($_GET['show_inactive'])) {
                $criteriaCampigns->addColumnCondition(array('active'=>1));
                $showInactive = false;
            } else {
                $showInactive = true;
            }
            
            $dataProvider=new CActiveDataProvider('Campaign', array(
                    'criteria'  =>  $criteriaCampigns,
                ));
            $this->render('index',array(
                    'dataProvider'  =>  $dataProvider,
                    'showInactive'  =>  $showInactive,
            ));
               
	}
        
        
        public function actionLeads()
        {
            
            $campaignId = (isset($_GET['campaign']))?$_GET['campaign']:0;
            $status = (isset($_GET['status']))?$_GET['status']:false;
            
            if(!$campaignId) {
                throw new CHttpException(400,'Не указана кампания');
            }
            
            $campaign = Campaign::model()->findByPk($campaignId);
            if(!$campaign || $campaign->buyerId != Yii::app()->user->id) {
                throw new CHttpException(403,'Вы не можете просматривать лиды данной кампании');
            }
            
            $criteria = new CDbCriteria;
            
            // найдем лидов, проданных текущему пользователю
            $criteria->order = 'id DESC';
            $criteria->addColumnCondition(array('campaignId'=>$campaignId));
            
            if($status !== false) {
                $criteria->addColumnCondition(array('leadStatus'=>(int)$status));
            }
            
            $dataProvider=new CActiveDataProvider('Lead100', array(
                    'criteria'  =>  $criteria,
                    'pagination'    =>  array(
                        'pageSize'=>50,
                    ),
                ));
            $this->render('leads',array(
                    'dataProvider'  =>  $dataProvider,
                    'campaign'      =>  $campaign,
                    'status'        =>  $status,
            ));
        }
        
        
        public function actionViewLead($id)
        {
            $model = Lead100::model()->with('campaign')->findByPk($id);
            
            if($model->campaign->buyerId != Yii::app()->user->id) {
                throw new CHttpException(403,'Вы не можете просматривать этого лида');
            }
            
            $this->render('viewLead',array(
                    'model' =>  $model,
            ));
        }
        
        
        public function actionCampaign($id)
        {
            $campaign = Campaign::model()->with('transactions')->findByPk($id);
            
            if($campaign && $campaign->buyerId != Yii::app()->user->id) {
                throw new CHttpException(403,'Вы не можете просматривать эту кампанию');
            }
            
            $transactionsDataProvider = new CArrayDataProvider($campaign->transactions);
                        
            $this->render('viewCampaign',array(
                    'model'                     =>  $campaign,
                    'transactionsDataProvider'  =>  $transactionsDataProvider,
            ));
            
        }
        
        
        // отбраковка лида покупателем
        public function actionBrakLead()
        {
            $reason = isset($_POST['reason'])?(int)$_POST['reason']:0;
            $leadId = isset($_POST['leadId'])?(int)$_POST['leadId']:0;
            
            if(!$leadId || !$reason) {
                echo json_encode(array('code'=>400, 'message'=>'Error, not enough data'));
                exit;
            }
            
            $lead = Lead100::model()->findByPk($leadId);
            
            if(!$lead) {
                echo json_encode(array('code'=>404, 'message'=>'Lead not found'));
                exit;
            }
            
            if(!$lead->campaign || $lead->campaign->buyerId != Yii::app()->user->id) {
                echo json_encode(array('code'=>403, 'message'=>'Вы не можете редактировать этого лида'));
                exit;
            }
            
            $lead->leadStatus = Lead100::LEAD_STATUS_NABRAK;
            $lead->brakReason = (int)$reason;
            
            if($lead->save()) {
                echo json_encode(array('code'=>0, 'id'=>$leadId, 'message'=>'Лид отправлен на отбраковку'));
                exit;
            } else {
                echo json_encode(array('code'=>400, 'message'=>'Ошибка: не удалось отправить лид на отбраковку'));
                exit;
            }
            
            
        }
        
        
}