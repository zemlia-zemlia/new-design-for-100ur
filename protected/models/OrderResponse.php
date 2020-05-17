<?php

namespace App\models;

use CHtml;
use GTMail;
use Yii;

/**
 * Отклик юриста на заказ документа.
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
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        return [
            ['text', 'length', 'max' => 10000],
            ['price', 'required'],
            ['price', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Цена должна быть больше нуля'],
            ['type, authorId, objectId, rating, status, parentId, price', 'numerical', 'integerOnly' => true],
            ['authorName', 'length', 'max' => 255],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, type, authorId, objectId, text, dateTime', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'authorId' => 'ID автора',
            'objectId' => 'ID связанного объекта',
            'text' => 'Сообщение к предложению услуг',
            'dateTime' => 'Дата и время',
            'author' => 'Автор',
            'rating' => 'Оценка',
            'authorName' => 'Имя автора',
            'price' => 'Стоимость услуг',
        ];
    }

    public function relations(): array
    {
        return [
            'author' => [self::BELONGS_TO, User::class, 'authorId'],
            'order' => [self::BELONGS_TO, Order::class, 'objectId'],
            'comments' => [self::HAS_MANY, Comment::class, 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_RESPONSE, 'order' => 'comments.root, comments.lft'],
            'commentsCount' => [self::STAT, Comment::class, 'objectId', 'condition' => 't.type=' . Comment::TYPE_RESPONSE, 'order' => 't.root, t.lft'],
        ];
    }

    /**
     * Отправка уведомления автору заказа, на который создан отклик или оставлен комментарий.
     */
    public function sendNotification()
    {
        $jurist = $this->author;
        $client = $this->order->author;
        $order = $this->order;

        if (0 == $client->active100) {
            return false;
        }

        if (!$order) {
            return false;
        }

        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        $questionLink = Yii::app()->createUrl('order/view', ['id' => $order->id]);

        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($client->autologin) && '' != $client->autologin) ? $client->autologin : $client->generateAutologinString();

        if (!$client->autologin) {
            $client->autologin = $autologinString;
            if (!$client->save()) {
                Yii::log('Не удалось сохранить строку autologin пользователю ' . $client->email . ' с уведомлением об отклике на заказ ' . $order->id, 'error', 'system.web.User');
            }
        }

        $questionLink .= '?autologin=' . $autologinString;

        $mailer = new GTMail();
        $mailer->subject = CHtml::encode($client->name) . ', новое предложение по Вашему заказу';
        $mailer->message = '<h1>Новое предложение по Вашему заказу</h1>
            <p>Здравствуйте, ' . CHtml::encode($client->name) . '<br /><br />
            На ' . CHtml::link('Ваш заказ', $questionLink) . ' получено новое предложение от юриста ' . CHtml::encode($jurist->lastName . ' ' . $jurist->name);

        $mailer->message .= '<br /><br />Посмотреть его можно по ссылке: ' . CHtml::link($questionLink, $questionLink);

        $mailer->message .= '.<br /><br />
            Будем держать Вас в курсе поступления других предложений. 
            <br /><br />
            ' . CHtml::link('Посмотреть предложение', $questionLink, ['class' => 'btn']) . '
            </p>';

        // отправляем письмо на почту пользователя
        $mailer->email = $client->email;

        if ($mailer->sendMail(true, '100yuristov')) {
            Yii::log('Отправлено письмо пользователю ' . $client->email . ' с уведомлением об ответе на заказ ' . $order->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $client->email . ' с уведомлением об ответе на заказ ' . $order->id, 'error', 'system.web.User');

            return false;
        }
    }
}
