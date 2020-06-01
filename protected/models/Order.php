<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use CHtml;
use GTMail;
use Yii;

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 *
 * @property int    $id
 * @property int    $status
 * @property string $createDate
 * @property int    $itemType
 * @property int    $price
 * @property string $description
 * @property int    $userId
 * @property int    $juristId
 * @property string $term
 */
class Order extends CActiveRecord
{
    // Статусы заказа
    const STATUS_NEW = 0; // новый (черновик)
    const STATUS_CONFIRMED = 6; // подтвержден (активный)
    const STATUS_JURIST_SELECTED = 1; // выбран юрист
    const STATUS_JURIST_CONFIRMED = 2; // в работе. юрист подтвердил принятие заказа
    const STATUS_DONE = 3; // выполнен
    const STATUS_REWORK = 4; // на доработке
    const STATUS_CLOSED = 5; // закрыт
    const STATUS_ARCHIVE = 7; // архив, брошенный заказ

    public $termDays; // количество дней на исполнение (используется в форме выбора юриста)
    public $agree = 1; // согласие на обработку персональных данных

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{order}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'order';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['itemType, description, userId', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['status, itemType, price, userId, juristId, termDays', 'numerical', 'integerOnly' => true, 'message' => 'Поле {attribute} должно быть целым числом'],
            ['term', 'date', 'format' => 'yyyy-mm-dd'],
            ['agree', 'compare', 'compareValue' => 1, 'on' => ['create'], 'message' => 'Вы должны согласиться на обработку персональных данных'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, status, createDate, itemType, price, description, userId', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        return [
            'author' => [self::BELONGS_TO, User::class, 'userId'],
            'jurist' => [self::BELONGS_TO, User::class, 'juristId'],
            'docType' => [self::BELONGS_TO, DocType::class, 'itemType'],
            'comments' => [self::HAS_MANY, Comment::class, 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_ORDER, 'order' => 'comments.root, comments.lft'],
            'commentsCount' => [self::STAT, Comment::class, 'objectId', 'condition' => 't.type=' . Comment::TYPE_ORDER],
            'responses' => [self::HAS_MANY, OrderResponse::class, 'objectId', 'condition' => 'responses.type=' . Comment::TYPE_RESPONSE, 'order' => 'responses.id ASC'],
            'responsesCount' => [self::STAT, OrderResponse::class, 'objectId', 'condition' => 't.type=' . Comment::TYPE_RESPONSE],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'createDate' => 'Дата создания',
            'itemType' => 'Тип',
            'price' => 'Стоимость',
            'description' => 'Описание',
            'userId' => 'Клиент',
            'author' => 'Клиент',
            'term' => 'Срок',
            'termDays' => 'Срок в днях',
            'agree' => 'Согласие на обработку персональных данных',
        ];
    }

    /**
     * возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов.
     *
     * @return array массив статусов
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_NEW => 'новый (черновик)',
            self::STATUS_JURIST_SELECTED => 'выбран юрист',
            self::STATUS_JURIST_CONFIRMED => 'в работе',
            self::STATUS_DONE => 'выполнен',
            self::STATUS_REWORK => 'на доработке',
            self::STATUS_CLOSED => 'закрыт',
            self::STATUS_CONFIRMED => 'подтвержден',
            self::STATUS_ARCHIVE => 'архив',
        ];
    }

    /**
     * возвращает массив, ключами которого являются коды статусов, а значениями - описания статусов.
     *
     * @return array массив статусов
     */
    public static function getStatusesNotes()
    {
        return [
            self::STATUS_NEW => '',
            self::STATUS_JURIST_SELECTED => 'Пожалуйста, подождите, пока юрист подтвердит принятие заказа',
            self::STATUS_JURIST_CONFIRMED => 'Юрист работает над Вашим документом. Вы получите уведомление на Email о готовности документа',
            self::STATUS_DONE => 'Юрист подготовил Ваш документ, пожалуйста, проверьте его',
            self::STATUS_REWORK => 'Заказ отправлен на доработку. Вы получите уведомление на Email о готовности документа',
            self::STATUS_CLOSED => '',
            self::STATUS_CONFIRMED => 'Ваш заказ открыт, ожидайте предложений услуг от юристов',
        ];
    }

    /**
     * возвращает название статуса для объекта.
     *
     * @return string название статуса
     */
    public function getStatusName()
    {
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
     *                             based on the search/filter conditions
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('status', $this->status);
        $criteria->compare('createDate', $this->createDate, true);
        $criteria->compare('itemType', $this->itemType);
        $criteria->compare('price', $this->price);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('userId', $this->userId);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name
     *
     * @return Order the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     *  Возвращает количество заказов в формате Подтвержден.
     *
     * @param int $cacheTime Длительность хранения результата запроса в кеше
     *
     * @return int количество заказов
     */
    public static function calculateNewOrders($cacheTime = 600)
    {
        $countRow = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{order}}')
            ->where('status=:status', [':status' => self::STATUS_CONFIRMED])
            ->queryRow();
        if ($countRow && $countRow['counter']) {
            return $countRow['counter'];
        } else {
            return 0;
        }
    }

    /**
     * Отправляем юристу уведомление о том, что он выбран исполнителем по заказу документа.
     */
    public function sendJuristNotification()
    {
        $jurist = $this->jurist;
        if (!$jurist) {
            return false;
        }

        $orderLink = Yii::app()->createUrl('order/view', ['id' => $this->id]);

        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($jurist->autologin) && '' != $jurist->autologin) ? $jurist->autologin : $jurist->generateAutologinString();

        if (!$jurist->autologin) {
            $jurist->autologin = $autologinString;
            if (!$jurist->save()) {
                Yii::log('Не удалось сохранить строку autologin пользователю ' . $jurist->email . ' с уведомлением об отклике на заказ ' . $this->id, 'error', 'system.web.User');
            }
        }

        $orderLink .= '?autologin=' . $autologinString;

        $mailer = new GTMail();
        $mailer->subject = CHtml::encode($jurist->name) . ', Вас выбрали исполнителем по заказу документа';
        $mailer->message = '<h1>Вас выбрали исполнителем по заказу документа</h1>
            <p>Здравствуйте, ' . CHtml::encode($jurist->name) . '<br /><br />
            Создатель ' . CHtml::link('заказа документа', $orderLink) . ' назначил Вас исполнителем';

        $mailer->message .= '<br /><br />Посмотреть заказ можно по ссылке: ' . CHtml::link($orderLink, $orderLink);

        // отправляем письмо на почту пользователя
        $mailer->email = $jurist->email;

        if ($mailer->sendMail(true, '100yuristov')) {
            Yii::log('Отправлено письмо пользователю ' . $jurist->email . ' с уведомлением о назначении исполнителем заказа ' . $this->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $jurist->email . ' с уведомлением о назначении исполнителем заказа ' . $this->id, 'error', 'system.web.User');

            return false;
        }
    }

    /**
     * Отправка заказа в архив с уведомлением автора заказа.
     *
     * @return bool Результат сохранения записи
     */
    public function sendToArchive()
    {
        $this->status = self::STATUS_ARCHIVE;

        if ($this->save()) {
            if ($this->sendArchiveNotification()) {
                return true;
            }
        } else {
            Yii::log('Ошибка при архивации заказов документов #' . $this->id, 'error', 'system.web');
        }

        return false;
    }

    /**
     * Отправка уведомления пользователю о том, что его заказ отправлен в архив.
     */
    public function sendArchiveNotification()
    {
        $client = $this->author;

        if (0 == $client->active100) {
            return false;
        }

        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        $questionLink = Yii::app()->createUrl('order/view', ['id' => $this->id]);

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
                Yii::log('Не удалось сохранить строку autologin пользователю ' . $client->email . ' с уведомлением об отклике на заказ ' . $this->id, 'error', 'system.web.User');
            }
        }

        $questionLink .= '&autologin=' . $autologinString;

        $mailer = new GTMail();
        $mailer->subject = CHtml::encode($client->name) . ', Ваш заказ документа отправлен в архив';
        $mailer->message = '<h1>Ваш заказ документа отправлен в архив</h1>
            <p>Здравствуйте, ' . CHtml::encode($client->name) . '<br /><br />' .
            CHtml::link('Ваш заказ', $questionLink) . ' отправлен в архив, так как по нему не совершалось никаких действий несколько дней.</p>';

        $mailer->message .= '<p><strong>Помогите нам улучшить сервис</strong><br />'
            . 'Будем рады получить от Вас обратную связь. Что не устроило Вас в предложениях юристов? '
            . 'Может быть, у Вас возникли трудности в пользовании сайтом?<br />'
            . '<strong>Просто ответьте на это письмо.</strong>'
            . '</p>';

        // отправляем письмо на почту пользователя
        $mailer->email = $client->email;

        if ($mailer->sendMail(true, '100yuristov')) {
            Yii::log('Отправлено письмо пользователю ' . $client->email . ' с уведомлением об ответе на заказ ' . $this->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $client->email . ' с уведомлением об ответе на заказ ' . $this->id, 'error', 'system.web.User');

            return false;
        }
    }
}
