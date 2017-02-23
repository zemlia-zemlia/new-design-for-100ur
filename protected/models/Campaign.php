<?php

/**
 * This is the model class for table "{{campaign}}".
 *
 * The followings are the available columns in table '{{campaign}}':
 * @property integer $id
 * @property integer $regionId
 * @property integer $timeFrom
 * @property integer $timeTo
 * @property integer $price
 * @property integer $balance
 * @property integer $leadsDayLimit
 * @property integer $brakPercent
 * @property integer $buyerId
 * @property integer $active
 * @property integer $sendEmail
 */
class Campaign extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{campaign}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('timeFrom, timeTo, price, balance, leadsDayLimit, brakPercent, buyerId, active', 'required'),
			array('regionId, townId, timeFrom, timeTo, price, sendEmail, balance, leadsDayLimit, brakPercent, buyerId, active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, regionId, timeFrom, timeTo, price, balance, leadsDayLimit, brakPercent, buyerId, active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'buyer'     =>  array(self::BELONGS_TO, 'User', 'buyerId'),
                    'region'    =>  array(self::BELONGS_TO, 'Region', 'regionId'),
                    'town'      =>  array(self::BELONGS_TO, 'Town', 'townId'),
                    'leads'     =>  array(self::HAS_MANY, 'Lead100', 'campaignId'),
                    'leadsToday'    =>  array(self::HAS_MANY, 'Lead100', 'campaignId', 
                        'condition' =>  'DATE(leadsToday.deliveryTime)="' . date('Y-m-d'). '"',
                        ),
                    'leadsCount'     =>  array(self::STAT, 'Lead100', 'campaignId'),
                    'leadsTodayCount'    =>  array(self::STAT, 'Lead100', 'campaignId', 
                        'condition' =>  'DATE(t.deliveryTime)="' . date('Y-m-d'). '" AND leadStatus IN('.Lead100::LEAD_STATUS_SENT.', '.Lead100::LEAD_STATUS_NABRAK.', ' . Lead100::LEAD_STATUS_RETURN . ')',
                        ),
                    'transactions'     =>  array(self::HAS_MANY, 'TransactionCampaign', 'campaignId', 'order'=>'transactions.id DESC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'regionId' => 'ID региона',
                        'townId'    =>  'ID города',
                        'region' => 'Регион',
                        'town'    =>  'Город',
			'timeFrom' => 'Время работы от',
			'timeTo' => 'Время работы до',
			'price' => 'Цена лида',
			'balance' => 'Баланс',
			'leadsDayLimit' => 'Дневной лимит лидов',
			'brakPercent'   => 'Процент брака',
			'buyerId'       => 'ID покупателя',
			'active'        => 'Активность',
			'sendEmail'     => 'Отправлять лиды на Email',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('regionId',$this->regionId);
		$criteria->compare('timeFrom',$this->timeFrom);
		$criteria->compare('timeTo',$this->timeTo);
		$criteria->compare('price',$this->price);
		$criteria->compare('balance',$this->balance);
		$criteria->compare('leadsDayLimit',$this->leadsDayLimit);
		$criteria->compare('brakPercent',$this->brakPercent);
		$criteria->compare('buyerId',$this->buyerId);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Campaign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
         * находит список кампаний, подходящих для отправки заданного лида
         */
        public static function getCampaignsForLead($leadId)
        {
            // ограничим число кампаний, которые ищем
            $limit = 10;
            
            $lead = Lead100::model()->findByPk($leadId);
            
            if(!$lead) {
                return false;
            }
            
//            CustomFuncs::printr($lead->attributes);
            
            //echo $lead->town->id . ", " . $lead->town->regionId . ', ' . (int)date('h') . '<br />'; 
            
            $campaigns = array();
            
            /**
             * Выбираем из базы активные кампании, настроенные на данный регион, город и время работы (время NOW())
             * сортировка по цене
             */
            // SELECT * FROM `crm_campaign` WHERE (`townId`=563 OR `regionId`=57) AND `timeFrom`<=16 AND `timeTo`>=16 AND active=1
            $campaignsRows = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from("{{campaign}} c")
                    ->where("(`townId`=:townId OR `regionId`=:regionId) AND `timeFrom`<=:hour AND `timeTo`>=:hour AND active=1", array(
                        ':townId'       =>  $lead->town->id,
                        ':regionId'     =>  $lead->town->regionId,
                        ':hour'         =>  (int)date('H'),
                        ))
                    ->order('price DESC')
                    ->limit($limit)
                    ->queryAll();
            
//            echo "Кампании без учета дневных лимитов:";
//            CustomFuncs::printr($campaignsRows);            
            
            foreach($campaignsRows as $campaign) {
                
                // находим дневной лимит кампании
                $dayLimit = $campaign['leadsDayLimit'];
                
                // находим, сколько лидов сегодня уже отправлено в кампанию
                $campaignTodayLeads = Yii::app()->db->createCommand()
                    ->select('COUNT(*) counter')
                    ->from("{{lead100}} l")
                    ->where("DATE(deliveryTime)=:todayDate AND campaignId=:campaignId AND leadStatus IN(:status1, :status2, :status3)", array(
                        ':todayDate'    =>  date('Y-m-d'),
                        ':campaignId'   =>  $campaign['id'],
                        ':status1'     =>  Lead100::LEAD_STATUS_SENT,
                        ':status2'     =>  Lead100::LEAD_STATUS_NABRAK,
                        ':status3'     =>  Lead100::LEAD_STATUS_RETURN,
                    ))
                    ->queryRow();
                
                //echo $campaign['id'] . ": " . $campaignTodayLeads['counter'] . 'лидов сегодня <br />';
                
//                $campaign['todayLeads'] = (int)$campaignTodayLeads['counter'];
//                $campaign['todayLeadsPercent'] = ($dayLimit>0)?((int)$campaignTodayLeads['counter']/$dayLimit)*100:100;
                
                // если в кампанию сегодня отправлено лидов меньше, чем дневной лимит, добавляем в список кампаний
                if($campaignTodayLeads['counter']<$dayLimit) {
                    $campaigns[] = $campaign;
                }
            }
            
            //echo "Кампании с учетом дневных лимитов:";
            //CustomFuncs::printr($campaigns);
            
            if(sizeof($campaigns)) {
                /** 
                 * получили список кампаний, подходящих данному лиду
                 * теперь нужно выбрать ту единственную, которая ему подходит
                 * выберем рандомно, шанс пропорционален цене
                 * 
                 * если кампания найдена одна, возвращаем ее id и не паримся
                 */
                
                if(sizeof($campaigns) == 1) {
                    return $campaigns[0]['id'];
                }
                
                /**
                 * складываем цены всех найденных кампаний, чтобы рандомным числом определить счастливчика
                 */
                $pricesSum = 0;
                foreach($campaigns as $c) {
                    $pricesSum += $c['price'];
                }
                $rnd = mt_rand(1,$pricesSum);
                
//                echo "SUM = " . $pricesSum . ", RND = " . $rnd;
                // определяем счастливчика и возвращаем его id
                $sum = 0;
                foreach($campaigns as $c) {
                    $sum += $c['price'];
                    if($sum >= $rnd) {
                        
//                        CustomFuncs::printr($c['id']);
                        //exit;
                        return $c['id'];
                    }
                }
                
                //return $campaigns[0]['id'];
            } else {
                return false;
            }
            
            //CustomFuncs::printr($campaigns);
        }
        
        
        public static function getCampaignsForBuyer($buyerId)
        {
            $criteria = new CDbCriteria;
            $criteria->order = "active DESC";
            $criteria->addColumnCondition(array('buyerId'=>(int)$buyerId));
            
            $campaigns = self::model()->cache(600)->findAll($criteria);
            //CustomFuncs::printr($campaigns);
            
            return $campaigns;
        }
        
        public static function getCampaignNameById($id)
        {
            $campaigns = self::model()->cache(600)->with('town', 'region')->findAll();
            
            foreach ($campaigns as $campaign) {
                if($campaign->id == $id) {
                    return $campaign->town->name . '' . $campaign->region->name;
                }
            }
            
        }
}
