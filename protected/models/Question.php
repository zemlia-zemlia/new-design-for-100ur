<?php

/**
 * Модель для работы с вопросами
 *
 * Поля таблицы '{{question}}':
 * @property integer $id
 * @property integer $number
 * @property string $questionText
 * @property string $title
 * @property string $townId
 * @property string $authorName
 * @property integer $status
 * @property string $phone
 * @property string $email
 * @property integer $leadStatus
 * @property integer $authorId
 * @property integer $price
 * @property integer $payed
 * @property string $sessionId
 * @property integer $isModerated
 * @property integer $moderatedBy
 * @property integer $moderatedTime
 * @property string $ip
 * @property integer $townIdByIP
 * @property integer $sourceId
 * @property float $buyPrice
 */
class Question extends CActiveRecord {

    const STATUS_NEW = 0; // Новый
    const STATUS_MODERATED = 1; // Ждет публикации
    const STATUS_PUBLISHED = 2; // Опубликован
    const STATUS_SPAM = 3; // Спам
    const STATUS_CHECK = 4; // Предварительно опубликован
    const STATUS_PRESAVE = 5; // Недозаполненный
    const LEAD_STATUS_SENT_CRM = 1;
    const LEAD_STATUS_SENT_LEADIA = 2;
    // Уровни крутости VIP вопросов (уровни цены)
    const LEVEL_1 = 1;
    const LEVEL_2 = 2;
    const LEVEL_3 = 3;
    const LEVEL_4 = 4;
    const LEVEL_5 = 5;

    public $agree = 1; // согласие на обработку персональных данных

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Question the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{question}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('questionText, authorName', 'required', 'message' => 'Поле {attribute} не заполнено'),
            array('phone', 'required', 'on' => 'create', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('townId', 'required', 'except' => array('preSave'), 'message' => 'Поле {attribute} должно быть заполнено'),
            array('number, status, publishedBy, authorId, price, payed', 'numerical', 'integerOnly' => true),
            array('agree', 'compare', 'compareValue' => 1, 'on' => array('create', 'createCall'), 'message' => 'Вы должны согласиться на обработку персональных данных'),
            array('sessionId', 'length', 'max' => 255),
            array('authorName, title', 'match', 'pattern' => '/^([а-яА-Я0-9ёЁA-Za-z\-.,\? ])+$/u', 'message' => 'В {attribute} могут присутствовать русские буквы, цифры, точка, дефис и пробел'),
            array('phone', 'match', 'pattern' => '/^([0-9\+])+$/u', 'message' => 'В номере телефона могут присутствовать только цифры и знак плюса'),
            array('email', 'email', 'message' => 'В Email допускаются латинские символы, цифры, точка и дефис', 'allowEmpty' => true),
            array('townId', 'match', 'not' => true, 'except' => array('preSave'), 'pattern' => '/^0$/', 'message' => 'Поле Город не заполнено'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, number, questionText', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'town' => array(self::BELONGS_TO, 'Town', 'townId'),
            'townByIP' => array(self::BELONGS_TO, 'Town', 'townIdByIP'),
            'source' => array(self::BELONGS_TO, 'Leadsource', 'sourceId'),
            'answers' => array(self::HAS_MANY, 'Answer', 'questionId'),
            'answersCount' => array(self::STAT, 'Answer', 'questionId'),
            'bublishUser' => array(self::BELONGS_TO, 'User', 'publishedBy'),
            'author' => array(self::BELONGS_TO, 'User', 'authorId'),
            'categories' => array(self::MANY_MANY, 'QuestionCategory', '{{question2category}}(qId, cId)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'number' => 'Уникальный номер вопроса',
            'questionText' => 'Вопрос',
            'category' => 'Категория',
            'categories' => 'Направление права',
            'status' => 'Статус',
            'authorName' => 'Ваше имя',
            'town' => 'Город',
            'townId' => 'Город',
            'title' => 'Тема вопроса',
            'phone' => 'Номер телефона',
            'authorId' => 'ID автора',
            'price' => 'Цена',
            'payed' => 'Оплачен',
            'isModerated' => 'Отредактирован (модерирован)',
            'sourceId' => 'id источника',
            'buyPrice' => 'Цена покупки вопроса',
            'agree' => 'Согласие на обработку персональных данных',
        );
    }

    /**
     * возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
     * 
     * @return array массив статусов 
     */
    static public function getStatusesArray() {
        return array(
            self::STATUS_PRESAVE => 'Недозаполненные',
            self::STATUS_NEW => 'Email не указан / не подтвержден',
            self::STATUS_CHECK => 'Предварительно опубликован',
            self::STATUS_MODERATED => 'Ждет публикации',
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_SPAM => 'Спам',
        );
    }

    /**
     * возвращает название статуса для вопроса
     * 
     * @return string название статуса
     */
    public function getQuestionStatusName() {
        $statusesArray = self::getStatusesArray();
        return $statusesArray[$this->status];
    }

    /**
     * статический метод, возвращает название статуса вопроса по коду
     * 
     * @param int $status код статуса
     * @return string название статуса 
     */
    static public function getStatusName($status) {
        $statusesArray = self::getStatusesArray();
        return $statusesArray[$status];
    }

    /**
     * возвращает количество вопросов с определенным статусом
     * 
     * @param int $status код статуса
     * @return int количество вопросов
     */
    static public function getCountByStatus($status) {
        $connection = Yii::app()->db;
        $sqlPublished = "SELECT COUNT(id) AS counter FROM {{question}} WHERE status=:status";
        $command = $connection->cache(600)->createCommand($sqlPublished);
        $command->bindParam(":status", $status, PDO::PARAM_INT);
        $row = $command->queryRow();
        return $row['counter'];
    }

    /**
     * возвращает количество вопросов без ответов
     * @return int количество вопросов
     */
    static public function getCountWithoutAnswers() {
        $connection = Yii::app()->db;
        $sql = "SELECT COUNT(*) counter FROM {{question}} q LEFT OUTER JOIN {{answer}} a ON a.questionId = q.id WHERE a.id IS NULL AND q.status IN (:statusPub,:statusCheck)";
        $command = $connection->createCommand($sql);
        $command->bindValue(":statusCheck", Question::STATUS_CHECK, PDO::PARAM_INT);
        $command->bindValue(":statusPub", Question::STATUS_PUBLISHED, PDO::PARAM_INT);
        $row = $command->queryRow();
        return $row['counter'];
    }

    /**
     * возвращает общее количество вопросов 
     * @return int количество вопросов 
     */
    static public function getCount() {
        $connection = Yii::app()->db;
        $sqlPublished = "SELECT COUNT(id) AS counter FROM {{question}}";
        $command = $connection->cache(600)->createCommand($sqlPublished);
        $row = $command->queryRow();
        return $row['counter'];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('number', $this->number);
        $criteria->compare('questionText', $this->questionText, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Метод, вызываемый перед сохранением модели в базе
     * @return boolean
     */
    protected function beforeSave() {
        if (!parent::beforeSave()) {
            return false;
        }


        if ($this->title == '') {
            $this->formTitle();
        }

        return true;
    }
    
    /**
     * Метод, вызываемый после сохранения вопроса
     */
    protected function afterSave()
    {
        if($this->status == self::STATUS_CHECK || $this->status == self::STATUS_PUBLISHED) {
            LoggerFactory::getLogger('db')->log('Сохранен опубликованный вопрос #' . $this->id, 'Question', $this->id);
        }
        parent::afterSave();
    }

    /**
     * Присваивает полю title первые $wordsCount слов из текста вопроса, отфильтровывая стоп-слова
     * 
     * @param int $wordsCount лимит на количество слов в заголовке
     */
    public function formTitle($wordsCount = 12) {
        $text = trim(preg_replace("/[^a-zA-Zа-яА-ЯёЁ0-9 ]/ui", ' ', $this->questionText));

        preg_match("/(\w+\s+){0," . $wordsCount . "}/u", $text, $matches);
        $this->title = $matches[0];
        
        $this->title = preg_replace('/\s{2,}/', ' ', $this->title);

        $patterns = array();
        $patterns[0] = '/Здравствуйте/ui';
        $patterns[1] = '/Добрый день/ui';
        $patterns[2] = '/[!,\.\?:]/ui';
        $patterns[3] = '/quote/ui';

        $replacements = array();
        $replacements[3] = '';
        $replacements[2] = ' ';
        $replacements[1] = '';
        $replacements[0] = '';

        $this->title = preg_replace($patterns, $replacements, $this->title);
        $this->title = trim($this->title);
        $this->title = mb_strtoupper(mb_substr($this->title, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($this->title, 1, mb_strlen($this->title, 'UTF-8'), 'UTF-8');
    }

    /**
     * возвращает цену вопроса (руб.) по уровню вопроса
     * в базе хранится уровень вопроса (целое число), т.к. цена каждого уровня может со временем меняться
     * 
     * @param int $level уровень вопроса
     * @return int Цена вопроса (руб.)
     */
    public static function getPriceByLevel($level = self::LEVEL_1) {
        switch ($level) {
            case self::LEVEL_1:
                return 142;
                break;
            case self::LEVEL_2:
                return 265;
                break;
            case self::LEVEL_3:
                return 385;
                break;
            case self::LEVEL_4:
                return 515;
                break;
            case self::LEVEL_5:
                return 695;
                break;
        }
    }

    /**
     *  сохраняет в базу вопрос, который не был полностью заполнен 
     * (имеет только имя и текст вопроса)
     * лид из такого вопроса не создать, но уникальный контент для публикации можно получить
     */
    public function preSave() {
        if ($this->sessionId == '') {
            $this->sessionId = '' . time() . '_' . mt_rand(100, 999);

            $this->setScenario('preSave');

            // особый статус "предварительно сохранен"
            $this->status = self::STATUS_PRESAVE;

            //Если город явно не указан и телефон не указан, возьмем город из IP адреса
            if (!$this->townId && !$this->phone && Yii::app()->user->getState('currentTownId')) {
                $this->ip = (isset($_SERVER['HTTP_X_REAL_IP'])) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
                $this->townIdByIP = Yii::app()->user->getState('currentTownId');
            }


            if (!$this->save()) {
                return false;
                //echo "Ошибки при предсохранении вопроса";
                //CustomFuncs::printr($this->errors);
            }
        }

        return true;
    }

    /**
     * Создает нового пользователя, сохраняет в базе, присваивает его id
     * как id автора вопроса
     * Нужно, чтобы в дальнейшем пользователь мог получать уведомления, писать комментарии к ответам, etc.
     * 
     * @return boolean true - пользователь сохранен, false - не сохранен
     */
    public function createAuthor() {
        // Если вопрос задал существующий пользователь, сразу вернем true
        // проверим, есть ли в базе пользователь с таким мейлом
        $findUserResult = Yii::app()->db->createCommand()
                ->select('id')
                ->from("{{user}}")
                ->where("LOWER(email)=:email AND email!=''", array(":email" => strtolower($this->email)))
                ->queryRow();
        //CustomFuncs::printr($findUserResult);Yii::app()->end();
        if ($findUserResult) {
            // если есть, то запишем id этого пользователя в авторы вопроса
            $this->authorId = $findUserResult['id'];
            $this->status = self::STATUS_CHECK;
            $this->publishDate = date('Y-m-d H:i:s');
            return true;
        }


        // создаем нового пользователя с атрибутами, которые о себе указал автор вопроса
        $author = new User;
        $author->role = User::ROLE_CLIENT;
        $author->phone = $this->phone;
        $author->email = $this->email;
        $author->townId = $this->townId;
        $author->name = $this->authorName;
        $author->password = $author->password2 = $author->generatePassword();
        $author->confirm_code = md5($this->email . mt_rand(100000, 999999));

        // сохраняем нового пользователя в базе, привязываем к вопросу, 
        // отправляем ссылку на подтверждение профиля
        if ($author->save()) {
            $this->authorId = $author->id;
            $author->sendConfirmation();
            return true;
        } else {
            return false;
        }
    }

    /**
     * возвращает id произвольного вопроса с определенным статусом и из категории, которая привязана 
     * к пользователю (юристу)
     *  Запрос:
     *  SELECT * FROM `crm_question` q 
     *   LEFT JOIN `crm_question2category` q2c ON q.id = q2c.qId
     *   WHERE q2c.cId IN(3,5) AND q.status=2
     *   ORDER BY RAND()
     *   LIMIT 1
     * 
     * @return int id вопроса (0 - ничего не найдено)
     */
    public static function getRandomId() {

        $myCategories = Yii::app()->user->categories;

        $myCategoriesStr = '';
        $myCategoriesIds = array();
        foreach ($myCategories as $cat) {
            $myCategoriesIds[] = $cat->id;
        }
        $myCategoriesStr = implode(',', $myCategoriesIds);

        $questionCommand = Yii::app()->db->createCommand()
                ->select('q.id id')
                ->from("{{question}} q")
                ->leftJoin("{{question2category}} q2c", "q.id = q2c.qId")
                ->where("q.status=:status", array(":status" => self::STATUS_PUBLISHED))
                ->order("RAND()")
                ->limit(1);

        if ($myCategoriesStr != '') {
            $questionCommand->andWhere("q2c.cId IN(" . $myCategoriesStr . ")");
        }
        $questionRow = $questionCommand->queryRow();

        if ($questionRow['id']) {
            return $questionRow['id'];
        } else {
            return 0;
        }
    }

    /**
     * Преобразует телефонные номера в стандартный формат 70000000000
     * @todo заменить вызовом PhoneHelper::normalizePhone()
     * @param type $phone
     * @return type
     */
    public static function normalizePhone($phone) {
        if ($phone == '') {
            return '';
        }
        // удаляем из номера все кроме цифр
        $digitalNumber = preg_replace('/([^0-9])/i', '', $phone);
        // берем последние 10 символов
        $digitalNumber = mb_substr($digitalNumber, -10, 10, 'utf-8');

        if(mb_strlen($digitalNumber, 'utf-8') < 11) {
            $digitalNumber = '7' . $digitalNumber;
        } elseif (mb_substr($digitalNumber, 0, 1, 'utf-8') != '7') {
            $digitalNumber = substr_replace($digitalNumber, '7', 0, 1);
        }

        return $digitalNumber;
    }

    /**
     * После оплаты вопроса отправляет уведомление админу и записывает транзакцию
     * @param float $rateWithoutComission Сумма оплаты за вычетом комисии Яндекса
     */
    public function vipNotification($rateWithoutComission) {
        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'a+');
        fwrite($paymentLog, 'Отправляем уведомление о вип вопросе ' . $this->id . PHP_EOL);
        fwrite($paymentLog, 'На адрес ' . Yii::app()->params['adminNotificationsEmail'] . PHP_EOL);
        fwrite($paymentLog, 'Сумма ' . $rateWithoutComission . PHP_EOL);

        $mailer = new GTMail;
        $mailer->subject = "Добавлен новый VIP вопрос";
        $mailer->email = Yii::app()->params['adminNotificationsEmail'];
        $mailer->message = "На сайт только что добавлен новый VIP вопрос: " .
                CHtml::link(Yii::app()->createUrl('question/view', array('id' => $this->id)), Yii::app()->createUrl('question/view', array('id' => $this->id)));

        fwrite($paymentLog, print_r($mailer, true));

        if ($mailer->sendMail()) {
            fwrite($paymentLog, 'письмо отправлено' . PHP_EOL);
        } else {
            fwrite($paymentLog, 'письмо не отправлено' . PHP_EOL);
        }

        $transaction = new Money;
        $transaction->type = Money::TYPE_INCOME;
        $transaction->direction = 504;
        $transaction->accountId = 4;
        $transaction->datetime = date('Y-m-d');
        $transaction->value = $rateWithoutComission;
        $transaction->comment = "Оплата вопроса id=" . $this->id;

        if ($transaction->save()) {
            fwrite($paymentLog, 'транзакция сохранена' . PHP_EOL);
        } else {
            fwrite($paymentLog, 'транзакция не сохранена' . PHP_EOL);
            fwrite($paymentLog, print_r($transaction->errors, true));
        }
    }

    /**
     * Привязывает к вопросу категории, исходя из ключевых слов в тексте вопроса
     */
    public function autolinkCategories() {
        $keys2categories = QuestionCategory::keys2categories();

        foreach ($keys2categories as $key => $catId) {

            if (stristr($this->questionText, $key)) {
                // если в тексте вопроса нашлось ключевое слово, прикрепляем вопрос к категории
                try {
                    Yii::app()->db->createCommand()
                            ->insert('{{question2category}}', array('cId' => $catId, 'qId' => $this->id));
                } catch (CDbException $e) {
                    // дублирование связей вопрос-категория, не записываем
                }
            }
        }
    }

    /**
     * Отмечает прочитанными все комментарии к данному вопросу, которые были написаны к ответу заданного пользователя
     */
    public function checkCommentsAsRead($userId) {
        if (!$userId) {
            return false;
        }

        $checkResult = Yii::app()->db->createCommand("UPDATE {{question}} q
                    LEFT JOIN {{answer}} a ON a.questionId = q.id
                    LEFT JOIN {{comment}} c ON c.objectId = a.id
                    LEFT JOIN {{user}} u ON u.id = c.authorId
                    SET c.seen=1
                    WHERE c.type=" . Comment::TYPE_ANSWER . " AND c.seen=0 AND a.authorId = " . $userId . " AND q.id=" . $this->id)
                ->execute();

        if ($checkResult) {
            return true;
        }
    }

    /**
     * Рассылает юристам уведомления о вопросах, опубликованных за последние несколько часов
     * @param integer $hours интервал в часах
     * @return boolean
     */
    public static function sendRecentQuestionsNotifications($hours = 12) {
        $questionsCriteria = new CDbCriteria();
        $questionsCriteria->addCondition('publishDate>NOW()-INTERVAL ' . $hours . ' HOUR');
        $questionsCriteria->addInCondition('status', [Question::STATUS_PUBLISHED, Question::STATUS_CHECK]);

        $recentQuestions = Question::model()->findAll($questionsCriteria);

        echo sizeof($recentQuestions) . ' questions found <br />' . PHP_EOL;

        if (!sizeof($recentQuestions)) {
            return false;
        }

        // массив с вопросами, разбитый по регионам и городам
        $questions = [];

        foreach ($recentQuestions as $recentQuestion) {
            if ($recentQuestion->town && $recentQuestion->town->regionId) {
                $questions[$recentQuestion->town->regionId][$recentQuestion->townId][] = $recentQuestion;
                echo 'Регион: ' . $recentQuestion->town->regionId . ', город: ' . $recentQuestion->townId . ', вопрос: ' . $recentQuestion->title . '<br />';
            }
        }
        //CustomFuncs::printr($questions);
        //var_dump($questions);
        $yurists = [];
        $yuristsRows = Yii::app()->db->createCommand()
                ->select('u.id, u.name, u.lastName, u.email, u.autologin, u.townId, t.regionId, s.subscribeQuestions')
                ->from('{{user}} u')
                ->leftJoin('{{yuristSettings}} s', 's.yuristId = u.id')
                ->leftJoin('{{town}} t', 't.id=u.townId')
                ->where('active100 = 1 AND role=:role AND (s.subscribeQuestions=1 OR s.subscribeQuestions=2)', [':role' => User::ROLE_JURIST])
                ->queryAll();

        foreach ($yuristsRows as $yuristsRow) {
            $yurists[$yuristsRow['regionId']][$yuristsRow['townId']][] = $yuristsRow;
        }


        // Обходим массив юристов, проверяем, есть ли вопросы из их городов и регионов

        foreach ($yurists as $regionId => $yuristsByRegion) {
            foreach ($yuristsByRegion as $townId => $yuristsByTown) {
                foreach ($yuristsByTown as $yurist) {

                    if ($yurist['subscribeQuestions'] == 2) {
                        // вариант, когда юрист подписан на вопросы из региона

                        if (!isset($questions[$regionId])) {
                            continue;
                        }
                        if (sizeof($questions[$regionId]) == 0) {
                            continue;
                        }
                        echo 'message to Yurist ' . $yurist['email'] . '<br />' . PHP_EOL;
                        $mailer = new GTMail();
                        $mailer->email = $yurist['email'];
                        $mailer->subject = "Свежие вопросы из вашего региона";
                        $mailer->message = "<h2>" . trim($yurist['name']) . ", подборка свежих вопросов из вашего региона " . "</h2>";

                        foreach ($questions[$regionId] as $questionsByRegion) {
                            foreach ($questionsByRegion as $question) {
                                //CustomFuncs::printr($question);
                                $mailer->message .= '<p>' .
                                        CHtml::link($question->title, Yii::app()->createUrl('question/view', ['id' => $question->id, 'autologin' => $yurist['autologin'], 'utm_medium' => 'mail', 'utm_source' => '100yuristov', 'utm_campaign' => 'fresh_questions_notification', 'utm_term'=>$yurist['id']])) .
                                        '<br />' . CHtml::encode(CustomFuncs::cutString($question->questionText, 200));
                                if (mb_strlen($question->questionText, 'utf-8') > 200) {
                                    $mailer->message .= '...';
                                }
                                $mailer->message .= '</p><hr />';
                            }
                        }
                        
                        $mailer->message .= "<p style='font-size:0.8em'>Если вы не хотите получать уведомления о новых вопросах, вы можете отключить их в личном кабинете на нашем сайте, в редактировании профиля</p>";
                        //echo "<div>" . $mailer->message . "</div><hr />";
                        if ($mailer->sendMail()) {
                            echo 'message sent <br />' . PHP_EOL;
                            Yii::log("Отправлено письмо юристу " . $mailer->email . " с уведомлением о новых вопросах", 'info', 'system.web.User');
                        }
                    } else {
                        // вариант, когда юрист подписан на вопросы из города
                        if (!isset($questions[$regionId])) {
                            continue;
                        }
                        if (!isset($questions[$regionId][$townId])) {
                            continue;
                        }
                        if (!sizeof($questions[$regionId]) || !sizeof($questions[$regionId][$townId])) {
                            // если нет свежих вопросов из заданного города, переходим к следующему юристу
                            continue;
                        }

                        echo 'message to Yurist ' . $yurist['email'] . '<br />' . PHP_EOL;
                        $mailer = new GTMail();
                        $mailer->email = $yurist['email'];
                        $mailer->subject = "Свежие вопросы из вашего города";
                        $mailer->message = "<h2>" . trim($yurist['name']) . ", подборка свежих вопросов из вашего города</h2>";

                        foreach ($questions[$regionId][$townId] as $question) {
                            //CustomFuncs::printr($question);
                            $mailer->message .= '<p>' .
                                    CHtml::link($question->title, Yii::app()->createUrl('question/view', ['id' => $question->id, 'autologin' => $yurist['autologin'], 'utm_medium' => 'mail', 'utm_source' => '100yuristov', 'utm_campaign' => 'fresh_questions_notification', 'utm_term'=>$yurist['id']])) .
                                    '<br />' . CHtml::encode(CustomFuncs::cutString($question->questionText, 200));
                            if (mb_strlen($question->questionText, 'utf-8') > 200) {
                                $mailer->message .= '...';
                            }
                            $mailer->message .= '</p><hr />';
                        }
                        $mailer->message .= "<p style='font-size:0.8em'>Если вы не хотите получать уведомления о новых вопросах, вы можете отключить их в личном кабинете на нашем сайте, в редактировании профиля</p>";

                        //echo "<div>" . $mailer->message . "</div><hr />";
                        if ($mailer->sendMail()) {
                          echo 'message sent <br />' . PHP_EOL;
                          Yii::log("Отправлено письмо юристу " . $mailer->email . " с уведомлением о новых вопросах", 'info', 'system.web.User');
                        }
                    }
                }
            }
        }
    }

    /**
     * Возвращает id следующего вопроса для юриста
     * @param int $userId
     * @return mixed
     */
    public function getNextQuestionIdForYurist($userId)
    {
        $nextQuestionSql = "SELECT q1.id FROM {{question}} q1
                WHERE q1.id NOT IN (
                    SELECT q.id
                    FROM {{question}} q
                    LEFT OUTER JOIN {{answer}} a ON a.questionId = q.id
                    WHERE a.authorId = :yuristId
                ) AND q1.status IN (:status1, :status2) AND q1.id!=:qid AND q1.id < :qid
                ORDER BY q1.id DESC
                LIMIT 1";

        $command = Yii::app()->db->createCommand($nextQuestionSql);
        $command->bindValue(":qid", $this->id, PDO::PARAM_INT);
        $command->bindValue(":yuristId", $userId, PDO::PARAM_INT);
        $command->bindValue(":status1", Question::STATUS_PUBLISHED, PDO::PARAM_INT);
        $command->bindValue(":status2", Question::STATUS_CHECK, PDO::PARAM_INT);
        $nextQuestionId = $command->queryScalar();

        return $nextQuestionId;
    }
}
