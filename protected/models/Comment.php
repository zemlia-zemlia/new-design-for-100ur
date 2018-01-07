<?php

/**
 * Модель для работы с комментариями
 *
 * Поля, доступные в таблице '{{comment}}':
 * @property string $id
 * @property integer $type
 * @property integer $authorId
 * @property integer $objectId
 * @property integer $rating
 * @property integer $status
 * @property string $text
 * @property string $dateTime
 * @property string $authorName
 */
class Comment extends CActiveRecord
{
	
        const TYPE_CONTACT = 1;
        const TYPE_AGREEMENT = 2;
        const TYPE_EVENT = 3;
        const TYPE_ANSWER = 4;
        const TYPE_COMPANY = 5;
        const TYPE_USER = 6;
        const TYPE_RESPONSE = 7; // комментарии к откликам на заказы документов
        const TYPE_ORDER = 8; // комментарии к заказам документов
        const TYPE_POST = 9; // комментарии к заказам документов
        
        const STATUS_NEW = 0;
        const STATUS_CHECKED = 1;
        const STATUS_SPAM = 2;
        
        // используется в иерархии комментариев
        public $parentId;




        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
         * Определение поведения для работы иерархичных комментариев
         * @return type
         */
        public function behaviors()
        {
            return array(
                'nestedSetBehavior' =>  array(
                    'class'             =>  'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                    'leftAttribute'     =>  'lft',
                    'rightAttribute'    =>  'rgt',
                    'levelAttribute'    =>  'level',
                    'hasManyRoots'      =>  true, 
                ),
            );
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text', 'required'),
			array('type, authorId, objectId, rating, status, parentId', 'numerical', 'integerOnly'=>true),
                        array('authorName','length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, authorId, objectId, text, dateTime', 'safe', 'on'=>'search'),
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
                    'author'    =>  array(self::BELONGS_TO, 'User', 'authorId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'        => 'ID',
			'type'      => 'Тип',
			'authorId'  => 'ID автора',
			'objectId'  => 'ID связанного объекта',
			'text'      => 'Комментарий',
			'dateTime'  => 'Дата и время',
                        'author'    =>  'Автор',
                        'rating'    =>  'Оценка',
                        'authorName'=>  'Имя автора',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('authorId',$this->authorId);
		$criteria->compare('objectId',$this->objectId);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('dateTime',$this->dateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        /**
         * возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
         * 
         * @return array (код статуса => название)
         */
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_NEW        =>  'Новый, не проверен',
                self::STATUS_CHECKED    =>  'Опубликован',
                self::STATUS_SPAM       =>  'Спам',
            );
        }
        
        /**
         * возвращает название статуса для объекта
         * 
         * @return string название статуса
         */
        public function getCommentStatusName()
        {
            $statusesArray = self::getStatusesArray();
            return $statusesArray[$this->status];
        }
        
        /**
         * Статический метод, возвращает название статуса по коду
         * 
         * @param int $status код статуса
         * @return string Название статуса 
         */
        static public function getStatusName($status)
        {
            $statusesArray = self::getStatusesArray();
            return $statusesArray[$status];
        }
        
        /**
         * Возвращает количество новых комментариев заданного типа
         * 
         * @param int $type Тип комментария
         * @param int $cacheTime Время кеширования (сек.)
         * @return int количество новых комментариев
         */
        public static function newCommentsCount($type, $cacheTime = 0) 
        {
            $counterRow = Yii::app()->db->cache($cacheTime)->createCommand()
                    ->select("COUNT(*) counter")
                    ->from("{{comment}}")
                    ->where("type=:type AND status=:status", array(':type'=>(int)$type, ':status'=>self::STATUS_NEW))
                    ->queryRow();
            
            return ($counterRow!== false)?$counterRow['counter']:0;
        }
        
        
        /**
         * Метод, вызываемый после сохранения комментария
         */
        
        protected function afterSave()
        {
            /**
             * после сохранения коментария, если это был комментарий к ответу юриста, 
             * отправим юристу уведомление
             * а если это комментарий на комментарий, уведомим автора родительского комментария
             */
            if($this->type == static::TYPE_ANSWER && $this->objectId && $this->isNewRecord === true) {
                $answer = Answer::model()->with('question')->findByPk($this->objectId);
                
                if($this->level>1) {
                    // это комментарий на комментарий
                    $parentComment = $this->parent()->find();
                    if($parentComment && $parentComment->author) {
                        $parentComment->author->sendCommentNotification($answer->question, $this, true);
                    }
                } else {
                    // это комментарий на ответ
                    if($answer && $answer->question) {
                        $answerAuthor = $answer->author;
                        if($answerAuthor && $answerAuthor->active100 == 1) {
                            $answerAuthor->sendCommentNotification($answer->question, $this, false);
                        }
                    }
                }
                  
            }
            
            if($this->type == self::TYPE_RESPONSE && !($this instanceof OrderResponse)) {
                if($this->level == 1) {
                    // это комментарий к отклику
                    $object = OrderResponse::model()->findByPk($this->objectId);
                    $order = $object->order;
                } else {
                    // это комментарий на комментарий к отклику
                    $object = $this->parent()->find();
                    $response = OrderResponse::model()->findByPk((int)$object->objectId);
                    $order = $response->order;
                    
                }
                
                $commentAuthor = $this->author;
                /*
                 * $object - это объект, к которому привязывается комментарий
                 * $author - автор объекта
                 */
                $author = $object->author;
                
//                CustomFuncs::printr($this->attributes);
//                CustomFuncs::printr($object->attributes);
//                CustomFuncs::printr($author->attributes);
//                exit;
                
                // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
                $questionLink = Yii::app()->createUrl('order/view', ['id'=>$order->id]);


                /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
                 *  генерируем код для автоматического логина при переходе из письма
                 * если есть, вставляем существующее значение
                 * это сделано, чтобы не создавать новую строку autologin при наличии старой
                 * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
                 */
                $autologinString = (isset($author->autologin) && $author->autologin != '') ? $author->autologin : $author->generateAutologinString();

                if(!$author->autologin) {
                    if ($author->save()) {
                        // пытаемся сохранить пользователя (обновив поле autologin)
                        
                    } else {
                        Yii::log("Не удалось сохранить строку autologin пользователю " . $author->email . " с уведомлением о комментарии на заказ " . $order->id, 'error', 'system.web.User');
                    }
                }
                $questionLink .= "?autologin=" . $autologinString;

                $mailer = new GTMail;
                $mailer->subject = CHtml::encode($author->name) . ", новое сообщение в заказе";
                $mailer->message = "<h1>Новое сообщение в заказе</h1>
                    <p>Здравствуйте, " . CHtml::encode($author->name) . "<br /><br />
                    На " . CHtml::link("заказ", $questionLink) . " получен новый комментарий от пользователя " . CHtml::encode($commentAuthor->lastName . ' ' . $commentAuthor->name);

                $mailer->message .= "<br /><br />Посмотреть его можно по ссылке: " . CHtml::link($questionLink, $questionLink);

                $mailer->message .= ".<br /><br />
                    Будем держать Вас в курсе поступления других комментариев. 
                    <br /><br />
                    </p>";

                // отправляем письмо на почту пользователя
                $mailer->email = $author->email;

                if ($mailer->sendMail(true, '100yuristov')) {
                    Yii::log("Отправлено письмо пользователю " . $author->email . " с уведомлением о комментарии на заказ " . $order->id, 'info', 'system.web.User');
                    return true;
                } else {
                    // не удалось отправить письмо
                    Yii::log("Не удалось отправить письмо пользователю " . $author->email . " с уведомлением о комментарии на заказ " . $order->id, 'error', 'system.web.User');
                    return false;
                }
                
            }
            
            parent::afterSave();
        }
        
    /**
     * Отправка уведомления автору комментария или отклика, на который оставлен комментарий
     */
    public function sendNotification()
    {
        // функция работает только для комментариев к откликам на заказы
        if($this->type != self::TYPE_RESPONSE) {
            return false;
        }
        $order = Order::model()->findByPk($this->objectId);
        $client = $this->author;
        
        if ($client->active100 == 0 || !$order) {
            return false;
        }
        
        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        $questionLink = Yii::app()->createUrl('order/view', ['id'=>$order->id]);


        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($client->autologin) && $client->autologin != '') ? $client->autologin : $client->generateAutologinString();

        if ($client->save()) {
            /*
             * пытаемся сохранить пользователя (обновив поле autologin)
             */
            $questionLink .= "?autologin=" . $autologinString;
        } else {
            Yii::log("Не удалось сохранить строку autologin пользователю " . $client->email . " с уведомлением об отклике на заказ " . $order->id, 'error', 'system.web.User');
        }


        $mailer = new GTMail;
        $mailer->subject = CHtml::encode($client->name) . ", новое предложение по Вашему заказу";
        $mailer->message = "<h1>Новое предложение по Вашему заказу</h1>
            <p>Здравствуйте, " . CHtml::encode($client->name) . "<br /><br />
            На " . CHtml::link("Ваш заказ", $questionLink) . " получено новое предложение от юриста " . CHtml::encode($jurist->lastName . ' ' . $jurist->name);
        
        $mailer->message .= "<br /><br />Посмотреть его можно по ссылке: " . CHtml::link($questionLink, $questionLink);
        
        $mailer->message .= ".<br /><br />
            Будем держать Вас в курсе поступления других предложений. 
            <br /><br />
            " . CHtml::link("Посмотреть предложение", $questionLink, array('class' => 'btn')) . "
            </p>";

        // отправляем письмо на почту пользователя
        $mailer->email = $client->email;

        if ($mailer->sendMail(true, '100yuristov')) {
            Yii::log("Отправлено письмо пользователю " . $client->email . " с уведомлением об ответе на заказ " . $order->id, 'info', 'system.web.User');
            return true;
        } else {
            // не удалось отправить письмо
            Yii::log("Не удалось отправить письмо пользователю " . $client->email . " с уведомлением об ответе на заказ " . $order->id, 'error', 'system.web.User');
            return false;
        }
        
    }
}