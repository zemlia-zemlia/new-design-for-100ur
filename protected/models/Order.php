<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $status
 * @property string $createDate
 * @property integer $itemType
 * @property integer $price
 * @property string $description
 * @property integer $userId
 */
class Order extends CActiveRecord {
    
    // Статусы заказа
    const STATUS_NEW = 0; // новый
    const STATUS_CONFIRMED = 6; // подтвержден
    const STATUS_AWAITING_PAYMENT = 1; // ожидает оплаты
    const STATUS_PAYED = 2; // оплачен
    const STATUS_DONE = 3; // выполнен
    const STATUS_REWORK = 4; // на доработке
    const STATUS_CLOSED = 5; // закрыт

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('itemType, description, userId', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('status, itemType, price, userId', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, status, createDate, itemType, price, description, userId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author'    => array(self::BELONGS_TO, 'User', 'userId'),
            'docType'   => array(self::BELONGS_TO, 'DocType', 'itemType'),
            'comments'  => array(self::HAS_MANY, 'Comment', 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_ORDER, 'order' => 'comments.root, comments.lft'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id'            => 'ID',
            'status'        => 'Статус',
            'createDate'    => 'Дата создания',
            'itemType'      => 'Тип',
            'price'         => 'Стоимость',
            'description'   => 'Описание',
            'userId'        => 'Клиент',
            'author'        => 'Клиент',
        );
    }
    
    /**
     * возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
     * @return Array массив статусов
     */
    static public function getStatusesArray() {
        return array(
            self::STATUS_NEW                => 'новый',
            self::STATUS_AWAITING_PAYMENT   => 'ожидает оплаты',
            self::STATUS_PAYED              => 'оплачен',
            self::STATUS_DONE               => 'выполнен',
            self::STATUS_REWORK             => 'на доработке',
            self::STATUS_CLOSED             => 'закрыт',
        );
    }
    
    /**
     * возвращает название статуса для объекта
     * 
     * @return string название статуса
     */
    public function getStatusName() {
        $statusesArray = self::getStatusesArray();
        return $statusesArray[$this->status];
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('status', $this->status);
        $criteria->compare('createDate', $this->createDate, true);
        $criteria->compare('itemType', $this->itemType);
        $criteria->compare('price', $this->price);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('userId', $this->userId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Order the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
