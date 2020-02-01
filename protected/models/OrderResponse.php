<?php

/**
 * Отклик юриста на заказ документа
 */
class OrderResponse extends Comment
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{orderresponse}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'orderresponse';
    }
    
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('text', 'length', 'max' => 10000),
            array('price', 'required'),
            array('price', 'compare', 'compareValue'=>0, 'operator'=>'>', 'message' => 'Цена должна быть больше нуля'),
            array('type, authorId, objectId, rating, status, parentId, price', 'numerical', 'integerOnly' => true),
            array('authorName', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, authorId, objectId, text, dateTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'            => 'ID',
            'type'          => 'Тип',
            'authorId'      => 'ID автора',
            'objectId'      => 'ID связанного объекта',
            'text'          => 'Сообщение к предложению услуг',
            'dateTime'      => 'Дата и время',
            'author'        => 'Автор',
            'rating'        => 'Оценка',
            'authorName'    => 'Имя автора',
            'price'         => 'Стоимость услуг',
        );
    }

    public function relations()
    {
        return array(
            'author'        =>  array(self::BELONGS_TO, 'User', 'authorId'),
            'order'         =>  array(self::BELONGS_TO, 'Order', 'objectId'),
            'comments'      =>  array(self::HAS_MANY, 'Comment', 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_RESPONSE, 'order' => 'comments.root, comments.lft'),
            'commentsCount' =>  array(self::STAT, 'Comment', 'objectId', 'condition' => 't.type=' . Comment::TYPE_RESPONSE, 'order' => 't.root, t.lft'),
        );
    }
    
    /**
     * Отправка уведомления автору заказа, на который создан отклик или оставлен комментарий
     */
    public function sendNotification()
    {
        $jurist = $this->author;
        $client = $this->order->author;
        $order = $this->order;
        
        if ($client->active100 == 0) {
            return false;
        }

        if (!$order) {
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

        if (!$client->autologin) {
            $client->autologin = $autologinString;
            if (!$client->save()) {
                Yii::log("Не удалось сохранить строку autologin пользователю " . $client->email . " с уведомлением об отклике на заказ " . $order->id, 'error', 'system.web.User');
            }
        }
        
        $questionLink .= "?autologin=" . $autologinString;


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
