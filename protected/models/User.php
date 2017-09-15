<?php

/**
 * Модель для работы с пользователями
 *
 * Поля в таблице '{{user}}':
 * @property string $id
 * @property string $name
 * @property string $name2
 * @property string $lastName
 * @property integer $role
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property integer $active100
 * @property string $confirm_code
 * @property string $townId
 * @property string $registerDate
 * @property integer $isSubscribed
 * @property integer $karma
 * @property string $autologin
 * @property string $lastActivity
 * @property float $balance
 * @property string $lastTransactionTime
 * @property float $priceCoeff
 * @property integer $lastAnswer
 */
class User extends CActiveRecord {

    public $password2; // поле подтверждения пароля для формы создания пользователя и смены пароля
    public $verifyCode; // код капчи

    const UNSUBSCRIBE_SALT = 'Kvadrat Malevi4a'; // используется при генерации и проверке кода для ссылки "отписаться от новостей"
    // константы для ролей пользователей
    const ROLE_SECRETARY = 0;
    const ROLE_OPERATOR = 2;
    const ROLE_CALL_MANAGER = 21;
    const ROLE_CLIENT = 3;
    const ROLE_EDITOR = 5;
    const ROLE_BUYER = 6;
    const ROLE_PARTNER = 7;
    const ROLE_EXECUTOR = 8;
    const ROLE_JURIST = 10;
    const ROLE_MANAGER = 20;
    const ROLE_ROOT = 100;
    // пути хранения картинок пользователей
    const USER_PHOTO_PATH = "/upload/userphoto";
    const USER_PHOTO_THUMB_FOLDER = "/thumbs";
    // аватар по умолчанию
    const DEFAULT_AVATAR_FILE = "/pics/yurist.png";
    // события, связанные с покупателем
    const BUYER_EVENT_CONFIRM = 1; // одобрение кампании модератором
    const BUYER_EVENT_TOPUP = 2; // пополнение счета
    const BUYER_EVENT_LOW_BALANCE = 3; // снижение баланса
    // значения баланса, при достижении которых покупателю отправляется уведомление о снижении баланса
    const BALANCE_STEPS = array(500, 1000, 5000, 10000);

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, email', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('name2, lastName', 'required', 'message' => 'Поле {attribute} должно быть заполнено', 'on' => 'createJurist, updateJurist'),
            array('phone', 'required', 'message' => 'Поле {attribute} должно быть заполнено', 'on' => 'register, update'),
            array('email', 'unique', 'message' => 'Пользователь с таким Email уже зарегистрирован'),
            array('townId', 'required', 'except' => 'unsubscribe, confirm', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('role, active100, townId, karma', 'numerical', 'integerOnly' => true),
            array('balance, priceCoeff', 'numerical'),
            array('name, email, phone', 'length', 'max' => 255),
            array('name2, lastName, birthday', 'safe'),
            array('townId', 'match', 'not' => true, 'except' => 'unsubscribe, confirm, changePassword, restorePassword', 'pattern' => '/^0$/', 'message' => 'Поле Город не заполнено'),
            array('password', 'length', 'min' => 5, 'max' => 128, 'tooShort' => 'Минимальная длина пароля 5 символов', 'allowEmpty' => ($this->scenario == 'update' || $this->scenario == 'register')),
            array('password2', 'compare', 'compareAttribute' => 'password', 'except' => 'confirm, create, register, update, updateJurist, unsubscribe, balance', 'message' => 'Пароли должны совпадать', 'allowEmpty' => ($this->scenario == 'update' || $this->scenario == 'register')),
            array('email', 'email', 'message' => 'В Email допускаются латинские символы, цифры, точка и дефис'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, role, email, phone, password', 'safe', 'on' => 'search, balance'),
        );
    }

    /** возвращает асоциативный массив, ключами которого являются 
     * коды ролей пользователей, а значениями - названия ролей
     * 
     * @return array массив ролей пользователей (код => название)
     */
    static public function getRoleNamesArray() {
        return array(
            self::ROLE_SECRETARY => 'секретарь',
            self::ROLE_OPERATOR => 'оператор call-центра',
            self::ROLE_CLIENT => 'пользователь',
            self::ROLE_EDITOR => 'контент-менеджер',
            self::ROLE_JURIST => 'юрист',
            self::ROLE_MANAGER => 'руководитель',
            self::ROLE_ROOT => 'администратор',
            self::ROLE_BUYER => 'покупатель лидов',
            self::ROLE_PARTNER => 'поставщик лидов',
        );
    }

    /**
     * Возвращает название роли пользователя
     * 
     * @return string роль пользователя
     */
    public function getRoleName() {
        $rolesNames = self::getRoleNamesArray();
        $roleName = $rolesNames[(int) $this->role];
        return $roleName;
    }

    /**
     * возвращает массив активных объектов класса User, у которых роль Менеджер
     * 
     * @return array массив активных объектов класса User
     */
    static public function getManagers() {
        $managers = self::model()->findAllByAttributes(array(
            'role' => self::ROLE_MANAGER,
            'active100' => 1,
        ));
        return $managers;
    }

    /**
     * возвращает массив, ключами которого являются id менеджеров
     *  а значениями - их имена
     * 
     * @return array массив менеджеров (id => name)
     */
    static public function getManagersNames() {
        $managers = self::getManagers();
        $managersNames = array('0' => 'нет руководителя');
        foreach ($managers as $manager) {
            $managersNames[$manager->id] = $manager->name;
        }
        return $managersNames;
    }

    /**
     * возвращает массив, ключами которого являются id активных юристов, а значениями - их имена
     * 
     * @return array Массив активных юристов (id => name) 
     */
    public static function getAllJuristsIdsNames() {
        $allJurists = array();
        $jurists = User::model()->findAllByAttributes(array(
            'role' => self::ROLE_JURIST,
            'active100' => 1,
        ));
        foreach ($jurists as $jurist) {
            $allJurists[$jurist->id] = $jurist->name;
        }
        return $allJurists;
    }

    /**
     * возвращает массив, ключами которого являются id активных покупателей, 
     * а значениями - их имена
     * 
     * @return array Массив активных покупателей (id => name)
     */
    public static function getAllBuyersIdsNames() {
        $allBuyers = array();
        $buyers = User::model()->findAllByAttributes(array(
            'role' => self::ROLE_BUYER,
            'active100' => 1,
        ));
        foreach ($buyers as $buyer) {
            $allBuyers[$buyer->id] = $buyer->lastName . ' ' . $buyer->name;
        }
        return $allBuyers;
    }

    /**
     *  активация пользователя
     */
    public function activate() {
        $this->active100 = 1;
    }

    /**
     * Отношения с другими моделями
     * 
     * @return array массив отношений
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'manager' => array(self::BELONGS_TO, 'User', 'managerId'),
            'settings' => array(self::HAS_ONE, 'YuristSettings', 'yuristId'),
            'files' => array(self::HAS_MANY, 'UserFile', 'userId', 'order' => 'files.id DESC'),
            'karmaChanges' => array(self::HAS_MANY, 'KarmaChange', 'userId'),
            'town' => array(self::BELONGS_TO, 'Town', 'townId'),
            'answersCount' => array(self::STAT, 'Answer', 'authorId', 'condition' => 'status IN (' . Answer::STATUS_NEW . ', ' . Answer::STATUS_PUBLISHED . ')'),
            'categories' => array(self::MANY_MANY, 'QuestionCategory', '{{user2category}}(uId, cId)'),
            'campaigns' => array(self::HAS_MANY, 'Campaign', 'buyerId'),
            'campaignsCount' => array(self::STAT, 'Campaign', 'buyerId'),
            'campaignsActiveCount' => array(self::STAT, 'Campaign', 'buyerId', 'condition' => 'active=1'),
            'campaignsModeratedCount' => array(self::STAT, 'Campaign', 'buyerId', 'condition' => 'active!=2'),
            'transactions' => array(self::HAS_MANY, 'TransactionCampaign', 'buyerId', 'order' => 'transactions.id DESC'),
            'comments' => array(self::HAS_MANY, 'Comment', 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_USER, 'order' => 'comments.id DESC, comments.root, comments.lft'),
            'commentsCount' => array(self::STAT, 'Comment', 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_USER),
            'sources' => array(self::HAS_MANY, 'Leadsource100', 'userId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Имя',
            'name2' => 'Отчество',
            'lastName' => 'Фамилия',
            'role' => 'Роль',
            'position' => 'Должность',
            'email' => 'Email',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'password2' => 'Пароль еще раз',
            'active100' => 'Активность на сайте',
            'managerId' => 'Руководитель',
            'birthday' => 'Дата рождения',
            'townId' => 'Город',
            'town' => 'Город',
            'avatarFile' => 'Фотография',
            'registerDate' => 'Дата регистрации',
            'karma' => 'Карма',
            'lastActivity' => 'Время последней активности',
            'balance' => 'Баланс',
            'priceCoeff' => 'Коэффициент цены лида у вебмастера',
            'lastAnswer'    =>  'Время последнего ответа',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('role', $this->role);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('active', $this->active);
        $criteria->compare('active100', $this->active100);
        $criteria->compare('managerId', $this->manager, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Метод, вызываемый перед сохранением объекта
     * 
     * @return boolean
     */
    protected function beforeSave() {
        if (parent::beforeSave()) {
            // записываем название города, чтобы не дергать его джойном лишний раз
            if($this->townId) {
                $townNameRow = Yii::app()->db->createCommand()
                        ->select('name')
                        ->from('{{town}}')
                        ->where('id=:id', array(':id' => $this->townId))
                        ->limit(1)
                        ->queryRow();
                $townName = $townNameRow['name'];
                $this->townName = $townName;
            }
            if ($this->isNewRecord || (strlen($this->password) < 128)) {
                // при регистрации пользователя и смене пароля перед сохранением пароль необходимо зашифровать
                $this->password = self::hashPassword($this->password);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Отправляет пользователю письмо со ссылкой на подтверждение email.
     *  Если указан параметр $newPassword, он будет выслан в письме  как новый пароль
     * 
     * @param string $newPassword Новый пароль, который необходимо отправить в письме
     * @return boolean true - письмо отправлено, false - не отправлено
     */
    public function sendConfirmation($newPassword = null) {

        $mailer = new GTMail;

        $confirmLink = CHtml::decode(Yii::app()->createUrl('user/confirm', array(
                            'email' => $this->email,
                            'code' => $this->confirm_code,
                ))) . "?utm_source=100yuristov&utm_medium=mail&utm_campaign=user_registration";

        $mailer->subject = "100 Юристов - Подтверждение Email";


        $mailer->message = "
            <h1>Пожалуйста подтвердите Email</h1>
            <p>Здравствуйте!<br />";

        if ($this->role == self::ROLE_JURIST) {
            $mailer->message .= "Вы зарегистрировались в качестве юриста на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                    "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } elseif ($this->role == self::ROLE_BUYER) {
            $mailer->message .= "Вы зарегистрировались в качестве покупателя лидов на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                    "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } elseif ($this->role == self::ROLE_PARTNER) {
            $mailer->message .= "Вы зарегистрировались в качестве вебмастера на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                    "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } else {
            $mailer->message .= "Вы задали вопрос на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                    "<p>Для того, чтобы юристы увидели Ваш вопрос, необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        }

        $mailer->message .= "<p><strong>" . CHtml::link("Подтвердить Email", $confirmLink, array('style' => ' padding: 10px;
            width: 150px;
            display: block;
            text-decoration: none;
            border: 1px solid #84BEEB;
            text-align: center;
            font-size: 18px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            color: #000;
            background: linear-gradient(to bottom, #ffc154 0%,#e88b0f 100%);
            border: 1px solid #EF9A27;
            border-radius: 4px;
            line-height: 17px;
            margin:0 auto;
        ')) . "</strong></p>";

        if ($newPassword) {
            $mailer->message .= "<h2>Ваш временный пароль</h2>
            <p>После подтверждения Email вы сможете войти на сайт, используя временный пароль <strong>" . $newPassword . "</strong></p>";
        }
        $mailer->email = $this->email;

        if ($mailer->sendMail()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  изменяет пароль пользователя на $newPassword, высылает ему на 
     *  почту новый пароль. Если пароль не задан, генерируется произвольный пароль
     * 
     * @param string $newPassword Новый пароль
     */
    public function changePassword($newPassword) {
        if (empty($newPassword)) {
            $newPassword = self::generatePassword(6);
        }

        $this->password = $newPassword;
        $this->password2 = $newPassword;
        if ($this->save()) {
            if ($this->sendChangedPassword($newPassword)) {
                return true;
            } else {
                // не удалось отправить письмо с новым паролем пользователю
                return false;
            }
        } else {
            // не удалось сохранить объект пользователя с новым паролем
            if (YII_DEBUG === true) {
                CustomFuncs::printr($this->errors);
            }
            return false;
        }
    }

    /**
     * Отправляет пользователю его пароль по почте (используем после активации аккаунта)
     * 
     * @param string $newPassword новый пароль
     * @return boolean true - удалось отправить письмо, false - не удалось
     */
    public function sendNewPassword($newPassword) {
        $mailer = new GTMail;
        $mailer->subject = CHtml::encode($this->name) . ", Ваш пароль для личного кабинета 100 юристов";
        $mailer->message = "Здравствуйте!<br />
            Вы упешно зарегистрировались на портале 100 юристов.<br /><br />
            Ваш логин: " . CHtml::encode($this->email) . "<br />
            Ваш временный пароль: " . $newPassword . "<br /><br />
            Вы всегда можете поменять его на любой другой, зайдя в " . CHtml::link("личный кабинет", Yii::app()->createUrl('site/login')) . " на нашем сайте.<br /><br />
            <br /><br />";
        $mailer->email = $this->email;

        if ($mailer->sendMail()) {
            return true;
        } else {
            // не удалось отправить письмо
            return false;
        }
    }

    /**
     * Высылает на email пользователю ссылку на смену пароля
     * 
     * @return boolean true - удалось отправить письмо, false - не удалось
     */
    public function sendChangePasswordLink() {
        $this->scenario = 'confirm';
        if ($this->confirm_code == '') {
            $this->confirm_code = $this->generateAutologinString();
            if (!$this->save()) {
                //CustomFuncs::printr($this->errors);
                throw new CHttpException(400, "Не удалось отправить ссылку на смену пароля");
            }
        }
        $changePasswordLink = Yii::app()->createUrl("user/setNewPassword", array('email' => $this->email, 'code' => $this->confirm_code));
        $mailer = new GTMail;
        $mailer->subject = "Смена пароля пользователя";
        $mailer->message = "Здравствуйте!<br />
            Ваша ссылка для смены пароля на портале 100 Юристов:<br />" .
                CHtml::link($changePasswordLink, $changePasswordLink) .
                "<br />";

        $mailer->email = $this->email;

        if ($mailer->sendMail()) {
            return true;
        } else {
            // не удалось отправить письмо
            return false;
        }
    }

    /**
     * Высылает пароль $newPassword на email пользователю
     * 
     * @param string $newPassword Новый пароль
     * @return boolean true - удалось отправить письмо, false - не удалось
     */
    public function sendChangedPassword($newPassword) {
        $mailer = new GTMail;
        $mailer->subject = "Смена пароля пользователя";
        $mailer->message = "Здравствуйте!<br />
            Вы или кто-то, указавший ваш E-mail, запросил восстановление пароля на портале 100 юристов.<br /><br />
            Ваш временный пароль: " . $newPassword . "<br /><br />
            Вы всегда можете поменять его на любой другой, зайдя в " . CHtml::link("личный кабинет", Yii::app()->createUrl('site/login')) . " на нашем сайте.<br /><br />
            Если Вы не запрашивали восстановление пароля, обратитесь, пожалуйста, к администратору сайта. <br /><br />";
        $mailer->email = $this->email;

        if ($mailer->sendMail()) {
            return true;
        } else {
            // не удалось отправить письмо
            return false;
        }
    }

    /**
     * генерирует пароль длиной $len символов
     * 
     * @param int $len Длина пароля
     * @return string Сгенерированный пароль
     */
    public static function generatePassword($len = 6) {
        return mb_substr(md5(mt_rand() . mt_rand()), mt_rand(1, 15), $len, 'utf-8');
    }

    /**
     * Шифрует пароль
     * 
     * @param string $password Незашифрованный пароль
     * @return string Зашифрованный пароль
     */
    public static function hashPassword($password) {
        $salt = bin2hex(mcrypt_create_iv(32)); //get 256 random bits in hex
        $hash = hash("sha256", $salt . $password); //prepend the salt, then hash
        //store the salt and hash in the same string, so only 1 DB column is needed
        $final = $salt . $hash;
        return $final;
    }

    /**
     * Проверка пароля
     * 
     * @param string $password Введенный пользователем незашифрованный пароль
     * @return boolean true - пароль верный, false - неверный 
     */
    public function validatePassword($password) {
        $salt = substr($this->password, 0, 64); //get the salt from the front of the hash
        $validHash = substr($this->password, 64, 64); //the SHA256

        $testHash = hash("sha256", $salt . $password); //hash the password being tested
        //if the hashes are exactly the same, the password is valid
        return $testHash === $validHash;
    }

    /**
     * возвращает массив объектов класса User, которые являются подчиненными менеджера
     * 
     * @return array Массив пользователей 
     */
    public function myEmployees() {
        $myEmployeesArray = User::model()->findAllByAttributes(array('active' => 1, 'managerId' => $this->id));
        return $myEmployeesArray;
    }

    /**
     * возвращает массив id подчиненных данного пользователя
     * 
     * @return type 
     */
    public function myEmployeesIds() {
        $myEmployees = array();
        $myEmployeesArray = $this->myEmployees();
        foreach ($myEmployeesArray as $myEmployee) {
            $myEmployees[] = $myEmployee['id'];
        }
        return $myEmployees;
    }

    /**
     * возвращает URL аватара текущего пользователя. Если аватар не задан, 
     * возвращает URL аватара по умолчанию
     * 
     * @param string $size размер картинки: thumb - маленькая, по умолчанию - большая
     * @return type 
     */
    public function getAvatarUrl($size = 'thumb') {
        if (!$this->avatar) {
            return self::DEFAULT_AVATAR_FILE;
        }

        $avatarFolder = User::USER_PHOTO_PATH;
        if ($size == 'thumb') {
            $avatarFolder .= User::USER_PHOTO_THUMB_FOLDER;
        }

        return $avatarFolder . "/" . $this->avatar;
    }

    /**
     * Возвращает фамилию и инициалы пользователя, например, Путин В.В.
     * 
     * @return string фамилия и инициалы
     */
    public function getShortName() {
        return $this->lastName . '&nbsp;' . mb_substr($this->name, 0, 1, 'utf-8') . '.' . mb_substr($this->name2, 0, 1, 'utf-8') . '.';
    }

    /**
     * Переводит вопросы, автором которых является данный пользователь, из статуса "Новый"
     * в статус "Предварительно опубликован"
     * При этом, если у вопроса указан источник, создаем транзакции вебмастера
     * @return integer Количество опубликованных вопросов
     */
    public function publishNewQuestions() {
        
        $questions = Question::model()->findAll('authorId!=0 AND authorId=:authorId AND status=:statusOld', array(':authorId' => $this->id, ':statusOld' => Question::STATUS_NEW));
        $publishedQuestionsNumber = 0;
        
        foreach ($questions as $question) {
            $question->status = Question::STATUS_CHECK;
            $question->publishDate = date('Y-m-d H:i:s');
            
            // при публикации вопроса автоматически присваиваем ему категории по ключевым словам
            $question->autolinkCategories();
            
            if($question->save() && $question->sourceId !== 0) {
                $publishedQuestionsNumber++;
                $webmasterTransaction = new PartnerTransaction();
                $webmasterTransaction->sum = $question->buyPrice;
                $webmasterTransaction->sourceId = $question->sourceId;
                $webmasterTransaction->partnerId = $question->source->userId;
                $webmasterTransaction->questionId = $question->id;
                $webmasterTransaction->comment = "Начисление за вопрос #" . $question->id;
                $webmasterTransaction->save();
            }
        }
        
        return $publishedQuestionsNumber;
    }

    /**
     * отправка письма пользователю, на вопрос которого дан ответ
     * 
     * @param Question $question Вопрос
     * @param Answer $answer Ответ
     * @return boolean Результат отправки: true - успешно, false - ошибка
     */
    public function sendAnswerNotification($question, $answer) {
        if ($this->active100 == 0) {
            return false;
        }

        if (!$question) {
            return false;
        }

        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        $questionLink = "https://100yuristov.com/q/" . $question->id . "/?utm_source=100yuristov&utm_medium=mail&utm_campaign=answer_notification&utm_term=" . $question->id;


        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($this->autologin) && $this->autologin != '') ? $this->autologin : $this->generateAutologinString();

        if ($this->save()) {
            /*
             * пытаемся сохранить пользователя (обновив поле autologin)
             */
            $questionLink .= "&autologin=" . $autologinString;
        } else {
            Yii::log("Не удалось сохранить строку autologin пользователю " . $this->email . " с уведомлением об ответе на вопрос " . $question->id, 'error', 'system.web.User');
        }


        $mailer = new GTMail;
        $mailer->subject = CHtml::encode($this->name) . ", новый ответ на Ваш вопрос!";
        $mailer->message = "<h1>Новый ответ на Ваш вопрос</h1>
            <p>Здравствуйте, " . CHtml::encode($this->name) . "<br /><br />
            Спешим сообщить, что на " . CHtml::link("Ваш вопрос", $questionLink) . " получен новый ответ юриста ";
        if (!$answer->videoLink) {
            $mailer->message .= CHtml::encode($answer->author->name . ' ' . $answer->author->lastName);
        }
        $mailer->message .= ".<br /><br />
            Будем держать Вас в курсе поступления других ответов. 
            <br /><br />
            " . CHtml::link("Посмотреть ответ", $questionLink, array('class' => 'btn')) . "
            </p>";

        // отправляем письмо на почту пользователя
        $mailer->email = $this->email;

        if ($mailer->sendMail(true, '100yuristov')) {
            Yii::log("Отправлено письмо пользователю " . $this->email . " с уведомлением об ответе на вопрос " . $question->id, 'info', 'system.web.User');
            return true;
        } else {
            // не удалось отправить письмо
            Yii::log("Не удалось отправить письмо пользователю " . $this->email . " с уведомлением об ответе на вопрос " . $question->id, 'error', 'system.web.User');
            return false;
        }
    }

    /**
     * функция отправки уведомления юристу или клиенту о новом комментарии на его ответ / комментарий
     * 
     * @param Question $question Вопрос
     * @param Comment $comment Комментарий
     * @param boolean $isChildComment Является ли комментарий дочерним для другого
     * @return boolean Результат отправки: true - успешно, false - ошибка
     */
    public function sendCommentNotification(Question $question, Comment $comment, $isChildComment = false) {
        if ($this->active100 == 0) {
            return false;
        }

        if (!$question) {
            return false;
        }

        if (!$comment) {
            return false;
        }

        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        //$questionLink = "https://100yuristov.com/q/" . $question->id . "/?utm_source=100yuristov&utm_medium=mail&utm_campaign=answer_notification&utm_term=" . $question->id;
        $questionLink = Yii::app()->createUrl('question/view', array('id' => $question->id)) . "/?utm_source=100yuristov&utm_medium=mail&utm_campaign=answer_notification&utm_term=" . $question->id;


        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($this->autologin) && $this->autologin != '') ? $this->autologin : $this->generateAutologinString();

        if ($this->save()) {
            /*
             * пытаемся сохранить пользователя (обновив поле autologin)
             */
            $questionLink .= "&autologin=" . $autologinString;
        } else {
            Yii::log("Не удалось сохранить строку autologin пользователю " . $this->email . " с уведомлением об ответе на вопрос " . $question->id, 'error', 'system.web.User');
        }


        $mailer = new GTMail;
        $mailer->subject = CHtml::encode($this->name) . ", обновление в переписке по вопросу!";
        $mailer->message = "<h1>Обновление в переписке по вопросу</h1>
            <p>Здравствуйте, " . CHtml::encode($this->name) . "<br /><br />
            Спешим сообщить, что в переписке по вопросу " . CHtml::link(CHtml::encode($question->title), $questionLink) . " появился новый комментарий от " . CHtml::encode($comment->author->name . ' ' . $comment->author->lastName) . ".
            <br /><br />
            Будем держать Вас в курсе поступления других комментариев. 
            <br /><br />
            " . CHtml::link("Посмотреть комментарий", $questionLink, array('class' => 'btn')) . "
            </p>";

        // отправляем письмо на почту пользователя
        $mailer->email = $this->email;

        if ($mailer->sendMail(true)) {
            Yii::log("Отправлено письмо пользователю " . $this->email . " с уведомлением о комментарии " . $comment->id, 'info', 'system.web.User');
            return true;
        } else {
            // не удалось отправить письмо
            Yii::log("Не удалось отправить письмо пользователю " . $this->email . " с уведомлением о комментарии " . $comment->id, 'error', 'system.web.User');
            return false;
        }
    }

    /**
     * функция проверки кода в ссылке "отписаться от рассылок"
     * 
     * @param string $code Код из ссылки "отписаться"
     * @param string $email Email из ссылки "отписаться"
     * @return boolean true, если код верный, false - если неверный
     */
    public static function verifyUnsubscribeCode($code, $email) {
        if ($code === md5(self::UNSUBSCRIBE_SALT . $email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * генерирует строку для возможности автологина пользователя
     * 
     * @return string Строка для автологина 
     */
    public function generateAutologinString() {
        $this->autologin = md5($this->id . $this->email . self::UNSUBSCRIBE_SALT);

        return $this->autologin;
    }

    /**
     * Автологин пользователя
     * 
     * @param array $params Массив параметров
     * @return boolean Результат: true - успех, false - ошибка
     */
    public static function autologin($params = array()) {
        if (!isset($params['autologin'])) {
            return false;
        }

        $autologinString = $params['autologin'];

        if ($identity === null) {
            $identity = new UserIdentity('', '');
            $identity->autologinString = $autologinString;
            $identity->autologin();
        }

        if ($identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($identity, $duration);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Отправляет покупателю письмо с уведомлением по его кампании
     * 
     * @param Campaign $campaign кампания, к которой относится уведомление
     */
    public function sendBuyerNotification($eventType, $campaign = NULL) {
        $mailer = new GTMail;
        $cabinetLink = Yii::app()->createUrl('/cabinet');

        switch ($eventType) {
            case self::BUYER_EVENT_CONFIRM:
                $mailer->subject = CHtml::encode($this->name) . ", Ваша кампания одобрена";
                $mailer->message = "<h1>Ваша кампания одобрена модератором</h1>
                    <p>Здравствуйте, " . CHtml::encode($this->name) . "<br /><br />
                    Ваша кампания по покупке лидов одобрена. Параметры кампании Вы можете увидеть в ее настройках
                    в <a href='" . $cabinetLink . "'>личном кабинете</a>. Для получения лидов Вам необходимо пополнить баланс. Способы пополнения
                    также доступны в личном кабинете.
                    </p>";
                break;
            case self::BUYER_EVENT_TOPUP:
                $mailer->subject = CHtml::encode($this->name) . ", Ваш баланс пополнен";
                $mailer->message = "<h1>Ваш баланс пополнен</h1>
                    <p>Здравствуйте, " . CHtml::encode($this->name) . "<br /><br />
                    Ваш баланс пополнен и составляет " . $this->balance . " руб. "
                        . "Информация о списаниях и зачислениях доступна в <a href='" . $cabinetLink . "'>личном кабинете</a>.
                    </p>";
                break;
            case self::BUYER_EVENT_LOW_BALANCE:
                $mailer->subject = CHtml::encode($this->name) . ", уведомление о расходе средств";
                $mailer->message = "<h1>Уведомление о расходе средств</h1>
                    <p>Здравствуйте, " . CHtml::encode($this->name) . "<br /><br />
                    Ваш баланс составляет " . $this->balance . " руб. "
                        . "Пополнить баланс, увидеть информацию о списаниях и зачислениях можно в <a href='" . $cabinetLink . "'>личном кабинете</a>.
                        
                    </p>";
                break;

            default:
                return false;
        }


        // отправляем письмо на почту покупателю
        $mailer->email = $this->email;

        if ($mailer->sendMail(true)) {
            Yii::log("Отправлено письмо покупателю " . $this->email, 'info', 'system.web.User');
            return true;
        } else {
            // не удалось отправить письмо
            Yii::log("Не удалось отправить письмо покупателю " . $this->email, 'error', 'system.web.User');
            return false;
        }
    }
    
    
    /**
     * Вычисление баланса вебмастера
     */
    public function calculateWebmasterBalance()
    {
        // если пользователь не вебмастер, сразу возвращаем его баланс
        if($this->role != self::ROLE_PARTNER) {
            return $this->balance;
        }
        
        $transactionsSumRow = Yii::app()->db->createCommand()
                ->select("SUM(t.`sum`) balance")
                ->from("{{partnertransaction}} t")
                ->where("t.partnerId=:userId AND t.status=:status", array(':userId' => $this->id, ':status' => PartnerTransaction::STATUS_COMPLETE))
                ->queryRow();
                
        return $transactionsSumRow['balance'];
    }
    
    /**
     * Вычисление холд вебмастера
     */
    public function calculateWebmasterHold()
    {
        // если пользователь не вебмастер, сразу возвращаем его баланс
        if($this->role != self::ROLE_PARTNER) {
            return 0;
        }
        
        $transactionsSumRow = Yii::app()->db->createCommand()
                ->select("SUM(t.`sum`) hold")
                ->from("{{partnertransaction}} t")
                ->where("t.partnerId=:userId AND t.leadId!=0 AND t.datetime>=NOW()-INTERVAL :interval DAY", array(':userId' => $this->id, ':interval' => Yii::app()->params['leadHoldPeriodDays']))
                ->queryRow();
                
        return $transactionsSumRow['hold'];
    }

}
