<?php

/**
 * Модель для работы с записями кассы
 *
 * Поля, доступные в таблице '{{money}}':
 * @property integer $id
 * @property integer $accountId
 * @property string $datetime
 * @property integer $type
 * @property string $value
 * @property string $comment
 * @property integer $direction
 * @property integer $isInternal
 */
class Money extends CActiveRecord
{
	
    const TYPE_INCOME = 0; // доход
    const TYPE_EXPENCE = 1; // расход
    
    const DIRECTION_INTERNAL = 900; // код направления для внутренних транзакций

    public $date1;
    public $date2; // диапазон дат для поиска


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
            return '{{money}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('accountId, datetime, type, value, comment, direction', 'required'),
            array('accountId, type, direction, isInternal', 'numerical', 'integerOnly'=>true),
            array('value', 'length', 'max'=>10),
            array('comment', 'length', 'max'=>255),
            array('date1, date2','match','pattern'=>'/^([0-9\-\.])$/u', 'message'=>'В датах могут присутствовать только цифры, дефисы и точки'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, accountId, datetime, type, value, comment, direction', 'safe', 'on'=>'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'accountId'     => 'Счет',
            'datetime'      => 'Дата',
            'type'          => 'Доход/расход',
            'value'         => 'Сумма',
            'comment'       => 'Комментарий',
            'direction'     => 'Статья',
            'date1'         => 'от',
            'date2'         => 'до',
            'isInternal'    => 'Внутренняя транзакция',
        );
    }

    /**
     * возвращает массив, ключами которого являются коды счетов, а значениями - названия
     * @return array массив счетов (код => название) 
     */
    static public function getAccountsArray()
    {
        return array(
            0   =>  "Яндекс",
            1   =>  "Карта В",
            2   =>  "Карта М",
            3   =>  "Наличные", 
            4   =>  "Р/сч", 
        );
    }

    /**
     * Возвращает название счета по его коду
     * @return string название счета
     */
    public function getAccount()
    {
        $accounts = self::getAccountsArray();
        return ($accounts[$this->accountId])?$accounts[$this->accountId]:false;
    }


    /**
     * возвращает массив, ключами которого являются коды статей расходов/доходов, а значениями - названия
     * @return array 
     * 1-100 - операционные расходы
     * 101-200 - капитальные расходы
     * 501-600 - доходы
     */
    static public function getDirectionsArray()
    {
        return array(
            1   =>  "Аренда",
            2   =>  "Хозрасходы",
            3   =>  "Контекстная реклама",
            4   =>  "Фонд оплаты труда", 
            5   =>  "Телефония",
            6   =>  "Покупка заявок",
            7   =>  "Реклама",
			8   =>  "Выплаты вебмастерам",
            101 =>  "SEO",
            500 =>  "Другое",
            501 => "Пополнение баланса пользователя",
            502 => "Взносы учредителей",
            503 => "Размещение рекламы",
            504 => "VIP вопросы",
            505 => "Благодарность юристам",
            900 => "Внутренние транзакции",
        );
    }

    /**
     * Возвращает название статьи для объекта Money по коду
     * @return string название статьи
     */
    public function getDirection()
    {
        $directions = self::getDirectionsArray();
        return ($directions[$this->direction])?$directions[$this->direction]:false;
    }
    
    /**
     * Статический метод, возвращающий название направления по коду
     * 
     * @param int $code Код направления
     * @return string Название направления
     */
    public static function getDirectionByCode($code)
    {
        $directions = self::getDirectionsArray();
        return $directions[$code];
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
        $criteria->compare('accountId',$this->accountId);
        $criteria->compare('datetime',$this->datetime,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('value',$this->value,true);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('direction',$this->direction);

        if($this->date1){
                $criteria->addCondition('datetime>="' . CustomFuncs::invertDate($this->date1) . '"');
        }
        if($this->date2){
                $criteria->addCondition('datetime<="' . CustomFuncs::invertDate($this->date2) . '"');
        }
        $criteria->order = 'datetime DESC, id DESC';

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'    =>  array(
                        'pageSize'  =>  20,
                    ),
        ));
    }


    /**
    * Возвращает массив записей, соответствующих условию
    * 
    * @return array массив записей кассы
    */
    public function getReportSet()
    {

        $command = Yii::app()->db->createCommand()
        ->select('*')
        ->from('{{money}}');

        if($this->date1) {
                $command->andWhere('`datetime`>=:date1', array(':date1'=>CustomFuncs::invertDate($this->date1)));
        }

        if($this->date2) {
                $command->andWhere('`datetime`<=:date2', array(':date2'=>CustomFuncs::invertDate($this->date2)));
        }
        
        $command->andWhere('isInternal = 0');
        /*
        echo CustomFuncs::invertDate($this->date1);
        echo CustomFuncs::invertDate($this->date2);
        
        echo $command->text;*/
        return $command->queryAll();

    }
    
    /**
     * Сортирует набор записей о доходах и расходах в массив, сгруппированный по типам и статьям
     * 
     * @param array $reportDataSet сырой набор записей кассы
     * @return array отсортированный набор
     */
    public static function filterReportSet($reportDataSet)
    {
        $dataSetFiltered = array();
        
        foreach($reportDataSet as $setRow) {
            switch($setRow['type']) {
                case self::TYPE_INCOME:
                    if($setRow['direction']>500) {
                        $dataSetFiltered['income']['sum'] += $setRow['value'];
                        $dataSetFiltered['income']['directions'][$setRow['direction']] += $setRow['value'];
                    }
                    break;
                    
                case self::TYPE_EXPENCE:
                    if($setRow['direction'] == 500) {
                        break;
                    }
                    $expenceType = NULL;
                    $dataSetFiltered['expences']['sum'] += $setRow['value'];
                    if($setRow['direction']<101) {
                        $expenceType = 'opex';
                    } elseif ($setRow['direction']<201 && $setRow['direction']>=101) {
                        $expenceType = 'capex'; 
                    }
                    if($expenceType) {
                        $dataSetFiltered['expences'][$expenceType]['sum'] += $setRow['value'];
                        $dataSetFiltered['expences'][$expenceType]['directions'][$setRow['direction']] += $setRow['value'];
                    }
                    break;
                    
                default :
                    break;
            }
        }
        
        $dataSetFiltered['ebitda'] = $dataSetFiltered['income']['sum'] - $dataSetFiltered['expences']['opex']['sum'];
        $dataSetFiltered['net_profit'] = $dataSetFiltered['income']['sum'] - $dataSetFiltered['expences']['sum'];
        
        return $dataSetFiltered;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Money the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
