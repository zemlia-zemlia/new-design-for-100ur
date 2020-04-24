<?php

namespace App\models;

use App\notifiers\UserNotifier;
use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use CHtml;
use CHttpException;
use CLogger;
use Exception;
use App\extensions\Logger\LoggerFactory;
use PDO;
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use UserIdentity;
use Yii;
use YurcrmClient\YurcrmClient;
use YurcrmClient\YurcrmResponse;

/**
 * Модель для работы с пользователями.
 *
 * Поля в таблице '{{user}}':
 *
 * @property string $id
 * @property string $name
 * @property string $name2
 * @property string $lastName
 * @property int $role
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property int $active100
 * @property string $confirm_code
 * @property string $townId
 * @property string $registerDate
 * @property int $isSubscribed
 * @property int $karma
 * @property string $autologin
 * @property string $lastActivity
 * @property float $balance
 * @property string $lastTransactionTime
 * @property float $priceCoeff
 * @property int $lastAnswer
 * @property int $refId
 * @property string $yurcrmToken
 * @property int $yurcrmSource
 */
class User extends CActiveRecord
{
    public $password2; // поле подтверждения пароля для формы создания пользователя и смены пароля
    public $verifyCode; // код капчи
    public $agree = 1; // согласие на обработку персональных данных

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
    const USER_PHOTO_PATH = '/upload/userphoto';
    const USER_PHOTO_THUMB_FOLDER = '/thumbs';
    // аватар по умолчанию
    const DEFAULT_AVATAR_FILE = '/pics/yurist.png';
    // события, связанные с покупателем
    const BUYER_EVENT_CONFIRM = 1; // одобрение кампании модератором
    const BUYER_EVENT_TOPUP = 2; // пополнение счета
    const BUYER_EVENT_LOW_BALANCE = 3; // снижение баланса
    // значения баланса, при достижении которых покупателю отправляется уведомление о снижении баланса
    const BALANCE_STEPS = [500, 1000, 5000, 10000];

    /** @var UserNotifier */
    protected $notifier;

    /** @var YurcrmClient */
    protected $yurcrmClient;

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'user';
    }

    /**
     * @return UserNotifier
     */
    public function getNotifier(): UserNotifier
    {
        return $this->notifier;
    }

    /**
     * @param UserNotifier $notifier
     */
    public function setNotifier(UserNotifier $notifier): void
    {
        $this->notifier = $notifier;
    }

    /**
     * @return YurcrmClient
     */
    public function getYurcrmClient(): YurcrmClient
    {
        return $this->yurcrmClient;
    }

    /**
     * @param YurcrmClient $yurcrmClient
     *
     * @return User
     */
    public function setYurcrmClient(YurcrmClient $yurcrmClient): User
    {
        $this->yurcrmClient = $yurcrmClient;

        return $this;
    }

    public function init()
    {
        $this->notifier = new UserNotifier(Yii::app()->mailer, $this);
        $this->yurcrmClient = new YurcrmClient(
            'user/create',
            'POST',
            Yii::app()->params['yurcrmToken'],
            Yii::app()->params['yurcrmApiUrl']
        );
    }

    /**
     * @return QuestionCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, email', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['name2, lastName', 'required', 'message' => 'Поле {attribute} должно быть заполнено', 'on' => 'createJurist, updateJurist'],
            ['lastName', 'required', 'message' => 'Поле {attribute} должно быть заполнено', 'on' => 'createBuyer'],
            ['phone', 'required', 'message' => 'Поле {attribute} должно быть заполнено', 'on' => 'register, update, createJurist, updateJurist'],
            ['email', 'unique', 'message' => 'Пользователь с таким Email уже зарегистрирован'],
            ['townId', 'required', 'except' => 'unsubscribe, confirm', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['role, active100, townId, karma, refId, yurcrmSource', 'numerical', 'integerOnly' => true],
            ['balance, priceCoeff', 'numerical'],
            ['name, email, phone', 'length', 'max' => 255],
            ['yurcrmToken', 'length', 'max' => 32],
            ['name2, lastName, birthday', 'safe'],
            ['agree', 'compare', 'compareValue' => 1, 'on' => ['register', 'createJurist'], 'message' => 'Вы должны согласиться на обработку персональных данных'],
            ['townId', 'match', 'not' => true, 'except' => 'unsubscribe, confirm, changePassword, restorePassword', 'pattern' => '/^0$/', 'message' => 'Поле Город не заполнено'],
            ['password', 'length', 'min' => 6, 'max' => 128, 'tooShort' => 'Минимальная длина пароля 6 символов', 'allowEmpty' => ('update' == $this->scenario || 'register' == $this->scenario)],
            ['password2', 'compare', 'compareAttribute' => 'password', 'except' => 'confirm, create, register, update, updateJurist, unsubscribe, balance', 'message' => 'Пароли должны совпадать', 'allowEmpty' => ('update' == $this->scenario || 'register' == $this->scenario)],
            ['email', 'email', 'message' => 'В Email допускаются латинские символы, цифры, точка и дефис'],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, role, email, phone, password', 'safe', 'on' => 'search, balance'],
            ['id, avatar', 'safe', 'on' => 'test'],
        ];
    }

    /** возвращает асоциативный массив, ключами которого являются
     * коды ролей пользователей, а значениями - названия ролей.
     *
     * @return array массив ролей пользователей (код => название)
     */
    public static function getRoleNamesArray()
    {
        return [
            self::ROLE_SECRETARY => 'секретарь',
            self::ROLE_OPERATOR => 'оператор call-центра',
            self::ROLE_CLIENT => 'пользователь',
            self::ROLE_EDITOR => 'контент-менеджер',
            self::ROLE_JURIST => 'юрист',
            self::ROLE_MANAGER => 'руководитель',
            self::ROLE_ROOT => 'администратор',
            self::ROLE_BUYER => 'покупатель лидов',
            self::ROLE_PARTNER => 'поставщик лидов',
        ];
    }

    /**
     * Возвращает название роли пользователя.
     *
     * @return string|null роль пользователя
     */
    public function getRoleName(): ?string
    {
        $rolesNames = self::getRoleNamesArray();
        $roleName = (isset($rolesNames[(int)$this->role])) ? $rolesNames[(int)$this->role] : null;

        return $roleName;
    }

    /**
     * возвращает массив активных объектов класса User, у которых роль Менеджер
     *
     * @return array массив активных объектов класса User
     */
    public static function getManagers(): array
    {
        $managers = self::model()->findAllByAttributes([
            'role' => self::ROLE_MANAGER,
            'active100' => 1,
        ]);

        return $managers;
    }

    /**
     * возвращает массив, ключами которого являются id менеджеров
     *  а значениями - их имена.
     *
     * @return array массив менеджеров (id => name)
     */
    public static function getManagersNames()
    {
        $managers = self::getManagers();
        $managersNames = ['0' => 'нет руководителя'];
        foreach ($managers as $manager) {
            $managersNames[$manager->id] = $manager->name;
        }

        return $managersNames;
    }

    /**
     * возвращает массив, ключами которого являются id активных юристов, а значениями - их имена.
     *
     * @return array Массив активных юристов (id => name)
     */
    public static function getAllJuristsIdsNames()
    {
        $allJurists = [];

        $jurists = Yii::app()->db->createCommand()
            ->select('id, name')
            ->from('{{user}}')
            ->where('role = :role AND active100 = 1', [
                ':role' => self::ROLE_JURIST,
            ])
            ->queryAll();

        foreach ($jurists as $jurist) {
            $allJurists[$jurist['id']] = $jurist['name'];
        }

        return $allJurists;
    }

    /**
     * возвращает массив, ключами которого являются id активных покупателей,
     * а значениями - их имена.
     *
     * @return array Массив активных покупателей (id => name)
     */
    public static function getAllBuyersIdsNames()
    {
        $allBuyers = [];

        $buyers = Yii::app()->db->createCommand()
            ->select('id, name, lastName')
            ->from('{{user}}')
            ->where('role = :role AND active100 = 1', [
                ':role' => self::ROLE_BUYER,
            ])
            ->queryAll();

        foreach ($buyers as $buyer) {
            $allBuyers[$buyer['id']] = $buyer['lastName'] . ' ' . $buyer['name'];
        }

        return $allBuyers;
    }

    /**
     *  активация пользователя.
     */
    public function activate()
    {
        $this->active100 = 1;
    }

    /**
     * Отношения с другими моделями.
     *
     * @return array массив отношений
     */
    public function relations(): array
    {
        return [
            'manager' => [self::BELONGS_TO, User::class, 'managerId'],
            'referer' => [self::BELONGS_TO, User::class, 'refId'],
            'settings' => [self::HAS_ONE, YuristSettings::class, 'yuristId'],
            'files' => [self::HAS_MANY, UserFile::class, 'userId', 'order' => 'files.id DESC'],
            'karmaChanges' => [self::HAS_MANY, KarmaChange::class, 'userId'],
            'town' => [self::BELONGS_TO, Town::class, 'townId'],
            'answersCount' => [self::STAT, Answer::class, 'authorId', 'condition' => 'status IN (' . Answer::STATUS_NEW . ', ' . Answer::STATUS_PUBLISHED . ')'],
            'questionsCount' => [self::STAT, Question::class, 'authorId', 'condition' => 'status IN (' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED . ')'],
            'categories' => [self::MANY_MANY, QuestionCategory::class, '{{user2category}}(uId, cId)'],
            'campaigns' => [self::HAS_MANY, Campaign::class, 'buyerId'],
            'campaignsCount' => [self::STAT, Campaign::class, 'buyerId'],
            'campaignsActiveCount' => [self::STAT, Campaign::class, 'buyerId', 'condition' => 'active=1'],
            'campaignsModeratedCount' => [self::STAT, Campaign::class, 'buyerId', 'condition' => 'active!=2'],
            'transactions' => [self::HAS_MANY, TransactionCampaign::class, 'buyerId', 'order' => 'transactions.id DESC'],
            'comments' => [self::HAS_MANY, Comment::class, 'objectId', 'condition' => 'comments.type=' . Comment::TYPE_USER, 'order' => 'comments.id DESC, comments.root, comments.lft'],
            'adminComments' => [self::HAS_MANY, Comment::class, 'objectId', 'condition' => 'adminComments.type=' . Comment::TYPE_ADMIN, 'order' => 'adminComments.id DESC, adminComments.root, adminComments.lft'],
            'commentsCount' => [self::STAT, Comment::class, 'objectId', 'condition' => 'type=' . Comment::TYPE_USER . ' AND status!=' . Comment::STATUS_SPAM],
            'sources' => [self::HAS_MANY, Leadsource::class, 'userId'],
            'ulogin' => [self::HAS_MANY, UloginModel::class, 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels(): array
    {
        return [
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
            'lastAnswer' => 'Время последнего ответа',
            'refId' => 'ID пригласившего пользователя',
            'agree' => 'Согласие на обработку персональных данных',
            'yurcrmToken' => 'Токен для YurCRM',
            'yurcrmSource' => 'id источника YurCRM',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('role', $this->role);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('active100', $this->active100);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Метод, вызываемый перед сохранением объекта.
     *
     * @return bool
     */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            // записываем название города, чтобы не дергать его джойном лишний раз
            if ($this->townId) {
                $townNameRow = Yii::app()->db->createCommand()
                    ->select('name')
                    ->from('{{town}}')
                    ->where('id=:id', [':id' => $this->townId])
                    ->limit(1)
                    ->queryRow();
                $townName = $townNameRow['name'];
                $this->townName = $townName;
            }

            // если при создании пользователя в сессии есть id пригласившего, запишем его
            if ($this->isNewRecord && Yii::app()->user->getState('ref') > 0) {
                $this->refId = Yii::app()->user->getState('ref');
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     *  Отправляет пользователю письмо со ссылкой на подтверждение email.
     *  Если указан параметр $newPassword, он будет выслан в письме  как новый пароль.
     *
     * @param string $newPassword Новый пароль, который необходимо отправить в письме
     * @param bool $useSMTP Использовать ли SMTP
     *
     * @return bool true - письмо отправлено, false - не отправлено
     */
    public function sendConfirmation($newPassword = null, $useSMTP = false)
    {
        return $this->notifier->sendConfirmation($newPassword, $useSMTP);
    }

    /**
     *  изменяет пароль пользователя на $newPassword, высылает ему на
     *  почту новый пароль. Если пароль не задан, генерируется произвольный пароль.
     *
     * @param string $newPassword Новый пароль
     */
    public function changePassword($newPassword)
    {
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
            Yii::log('Не удалось сменить пароль пользователю ' . $this->id . ': ' . print_r($this->errors, true), CLogger::LEVEL_ERROR, 'system.web.User');

            return false;
        }
    }

    /**
     * Отправляет пользователю его пароль по почте (используем после активации аккаунта).
     *
     * @param string $newPassword новый пароль
     *
     * @return bool true - удалось отправить письмо, false - не удалось
     */
    public function sendNewPassword($newPassword)
    {
        return $this->notifier->sendNewPassword($newPassword);
    }

    /**
     * Высылает на email пользователю ссылку на смену пароля.
     *
     * @return bool true - удалось отправить письмо, false - не удалось
     *
     * @throws CHttpException
     */
    public function sendChangePasswordLink()
    {
        $this->scenario = 'confirm';
        if ('' == $this->confirm_code) {
            $this->confirm_code = $this->generateAutologinString();
            if (!$this->save()) {
                throw new CHttpException(400, 'Не удалось отправить ссылку на смену пароля');
            }
        }
        $changePasswordLink = $this->getChangePasswordLink();

        return $this->notifier->sendChangePasswordLink($changePasswordLink);
    }

    /**
     * Высылает пароль $newPassword на email пользователю.
     *
     * @param string $newPassword Новый пароль
     *
     * @return bool true - удалось отправить письмо, false - не удалось
     */
    public function sendChangedPassword($newPassword)
    {
        return $this->notifier->sendChangedPassword($newPassword);
    }

    /**
     * генерирует пароль длиной $len символов.
     *
     * @param int $len Длина пароля
     *
     * @return string Сгенерированный пароль
     */
    public static function generatePassword($len = 6)
    {
        return mb_substr(md5(mt_rand() . mt_rand()), mt_rand(1, 15), $len, 'utf-8');
    }

    /**
     * Шифрует пароль.
     *
     * @param string $password Незашифрованный пароль
     *
     * @return string Зашифрованный пароль
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Проверка пароля.
     *
     * @param string $password Введенный пользователем незашифрованный пароль
     *
     * @return bool true - пароль верный, false - неверный
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    public static function getChatsMessagesCnt()
    {
        $result = '';
        $cnt = ChatMessages::model()->count('user_id = :id AND is_read = 0', [':id' => Yii::app()->user->id]);
        if ($cnt) {
            return '<strong class="red">(' . $cnt . ')</strong>';
        }
    }
    /**
     * возвращает URL аватара текущего пользователя. Если аватар не задан,
     * возвращает URL аватара по умолчанию.
     *
     * @param string $size размер картинки: по умолчанию thumb - маленькая, по умолчанию - большая
     *
     * @return string
     */
    public function getAvatarUrl($size = 'thumb')
    {
        if (!$this->avatar) {
            return self::DEFAULT_AVATAR_FILE;
        }

        $avatarFolder = User::USER_PHOTO_PATH;
        if ('thumb' == $size) {
            $avatarFolder .= User::USER_PHOTO_THUMB_FOLDER;
        }

        return $avatarFolder . '/' . $this->avatar;
    }

    /**
     * Возвращает фамилию и инициалы пользователя, например, Путин В.В.
     *
     * @return string фамилия и инициалы
     */
    public function getShortName()
    {
        // Если не указана фамилия, вернем имя
        if ('' == $this->lastName && '' != $this->name) {
            return $this->name;
        }

        $shortName = $this->lastName . ' ';
        if ('' != $this->name) {
            $shortName .= mb_substr($this->name, 0, 1, 'utf-8') . '.';
        }
        if ('' != $this->name2) {
            $shortName .= mb_substr($this->name2, 0, 1, 'utf-8') . '.';
        }

        return trim($shortName);
    }

    /**
     * Возвращает фамилию и имя  пользователя, или название компании
     *
     * @return string
     */
    public function getNameOrCompany()
    {
        if ($this->settings && $this->settings->status == YuristSettings::STATUS_COMPANY) {
            $name = $this->settings->companyName;
        } else {
            $name = $this->name . ' ' . $this->name2 . ' ' . $this->lastName;
        }


        return $name;
    }

    /**
     * Переводит вопросы, автором которых является данный пользователь, из статуса "Новый"
     * в статус "Предварительно опубликован"
     * При этом, если у вопроса указан источник, создаем транзакции вебмастера.
     *
     * @return int Количество опубликованных вопросов
     */
    public function publishNewQuestions()
    {
        $questions = Question::getQuestionsByAuthor($this->id, [Question::STATUS_NEW]);
        $publishedQuestionsNumber = 0;

        foreach ($questions as $question) {
            if ($question->publish()) {
                ++$publishedQuestionsNumber;
            }
        }

        return $publishedQuestionsNumber;
    }

    /**
     * отправка письма пользователю, на вопрос которого дан ответ
     *
     * @param Question|null $question Вопрос
     * @param Answer $answer Ответ
     *
     * @return bool Результат отправки: true - успешно, false - ошибка
     */
    public function sendAnswerNotification(?Question $question, Answer $answer)
    {
        if (0 == $this->active100 || !$question) {
            return false;
        }

        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        $questionLinkParams = [
            'id' => $question->id,
            'utm_source' => '100yuristov',
            'utm_medium' => 'mail',
            'utm_campaign' => 'answer_notification',
            'utm_term' => $question->id,
        ];

        $testimonialLink = Yii::app()->createUrl('user/testimonial', ['id' => $answer->authorId]);

        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($this->autologin) && '' != $this->autologin) ? $this->autologin : $this->generateAutologinString();

        if ($this->save()) {
            $questionLinkParams['autologin'] = $autologinString;
            $testimonialLink .= '?autologin=' . $autologinString;
        } else {
            Yii::log('Не удалось сохранить строку autologin пользователю ' . $this->email . ' с уведомлением об ответе на вопрос ' . $question->id, 'error', 'system.web.User');
        }

        $questionLink = Yii::app()->createUrl('question/view', $questionLinkParams);

        return $this->notifier->sendAnswerNotification($answer, $question, $questionLink, $testimonialLink);
    }

    /**
     * функция отправки уведомления юристу или клиенту о новом комментарии на его ответ / комментарий.
     *
     * @param Question|null $question Вопрос
     * @param Comment|null $comment Комментарий
     * @param bool $isChildComment Является ли комментарий дочерним для другого
     *
     * @return bool Результат отправки: true - успешно, false - ошибка
     */
    public function sendCommentNotification(?Question $question, ?Comment $comment, $isChildComment = false)
    {
        if (0 == $this->active100) {
            return false;
        }

        if (!$question) {
            return false;
        }

        if (!$comment) {
            return false;
        }

        // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
        $questionLink = Yii::app()->createUrl('question/view', ['id' => $question->id]) . '/?utm_source=100yuristov&utm_medium=mail&utm_campaign=answer_notification&utm_term=' . $question->id;

        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($this->autologin) && '' != $this->autologin) ? $this->autologin : $this->generateAutologinString();

        if ($this->save()) {
            $questionLink .= '&autologin=' . $autologinString;
        } else {
            Yii::log('Не удалось сохранить строку autologin пользователю ' . $this->email . ' с уведомлением об ответе на вопрос ' . $question->id, 'error', 'system.web.User');
        }

        return $this->notifier->sendCommentNotification($question, $comment, $questionLink);
    }

    /**
     * функция проверки кода в ссылке "отписаться от рассылок".
     *
     * @param string $code Код из ссылки "отписаться"
     * @param string $email Email из ссылки "отписаться"
     *
     * @return bool true, если код верный, false - если неверный
     */
    public static function verifyUnsubscribeCode($code, $email)
    {
        return $code === md5(self::UNSUBSCRIBE_SALT . $email);
    }

    /**
     * генерирует строку для возможности автологина пользователя.
     *
     * @return string Строка для автологина
     */
    public function generateAutologinString()
    {
        $this->autologin = md5($this->id . $this->email . self::UNSUBSCRIBE_SALT);

        return $this->autologin;
    }

    /**
     * Автологин пользователя.
     *
     * @param array $params Массив параметров
     *
     * @return bool Результат: true - успех, false - ошибка
     */
    public static function autologin($params = [])
    {
        $identity = null;
        if (!isset($params['autologin'])) {
            return false;
        }

        $autologinString = $params['autologin'];

        $identity = new UserIdentity('', '');
        $identity->autologinString = $autologinString;
        $identity->autologin();

        if (UserIdentity::ERROR_NONE === $identity->errorCode) {
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($identity, $duration);

            return true;
        }

        return false;
    }

    /**
     * Отправляет покупателю письмо с уведомлением по его кампании.
     *
     * @param int $eventType
     *
     * @return bool
     */
    public function sendBuyerNotification($eventType)
    {
        return $this->notifier->sendBuyerNotification($eventType);
    }

    /**
     * Вычисление баланса вебмастера.
     *
     * @param int $cacheTime время кеширования
     *
     * @return float Баланс
     */
    public function calculateWebmasterBalance($cacheTime = 0)
    {
        $transactionsSumRow = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('SUM(t.`sum`) balance')
            ->from('{{partnerTransaction}} t')
            ->where('t.partnerId=:userId AND t.status=:status', [':userId' => $this->id, ':status' => PartnerTransaction::STATUS_COMPLETE])
            ->queryRow();

        return $transactionsSumRow['balance'];
    }

    /**
     * Вычисление холд вебмастера.
     *
     * @param int $cacheTime время кеширования
     *
     * @return float Баланс
     */
    public function calculateWebmasterHold($cacheTime = 0)
    {
        // если пользователь не вебмастер, его холд всегда 0
        if (self::ROLE_PARTNER != $this->role) {
            return 0;
        }

        $transactionsSumRow = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('SUM(t.`sum`) hold')
            ->from('{{partnerTransaction}} t')
            ->where('t.partnerId=:userId AND t.leadId!=0 AND t.datetime>=NOW()-INTERVAL :interval DAY', [':userId' => $this->id, ':interval' => Yii::app()->params['leadHoldPeriodDays']])
            ->queryRow();

        return $transactionsSumRow['hold'];
    }

    /**
     * Отмечает все новые заказы пользователя как подтвержденные.
     */
    public function confirmOrders()
    {
        Yii::app()->db->createCommand()
            ->update('{{order}}', ['status' => Order::STATUS_CONFIRMED], 'status=' . Order::STATUS_NEW . ' AND userId=' . $this->id);
    }

    /**
     * Добавление пользователя в список рассылки Sendpulse через API
     * Не используется 03.11.2019.
     */
    public function addToSendpulse()
    {
        // на локальной версии не отправляем пользователей в Sendpulse
        if (YII_DEBUG === true) {
            return false;
        }

        // API credentials from https://login.sendpulse.com/settings/#api
        define('API_USER_ID', Yii::app()->params['sendPulseApiId']);
        define('API_SECRET', Yii::app()->params['sendPulseApiSecret']);
        define('PATH_TO_ATTACH_FILE', __FILE__);

        // Инициализируем апи клиент
        $SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

        // добавляем инфо о пользователе в почтовый сервис
        if (array_key_exists($this->role, Yii::app()->params['sendpulseBooks'])) {
            $bookID = Yii::app()->params['sendpulseBooks'][$this->role];
            $emails = [
                [
                    'email' => $this->email,
                    'variables' => [
                        'Телефон' => $this->phone,
                        'Имя' => CHtml::encode($this->name),
                        'lastName' => CHtml::encode($this->lastName),
                        'townId' => $this->townId,
                    ],
                ],
            ];
            $SPApiClient->addEmails($bookID, $emails);
        }
    }

    /**
     * Возвращает массив непросмотренных комментариев, написанных к ответам юриста.
     *
     * @param int $days За сколько дней искать комментарии
     * @param bool $returnCount Вернуть количество элементов
     *
     * @return array|int Массив с комментариями или количество комментариев
     */
    public function getFeed($days = 30, $returnCount = false)
    {
//        SELECT q.title, c.text, u.name
//        FROM `100_question` q
//        LEFT JOIN `100_answer` a ON a.questionId = q.id
//        LEFT JOIN `100_comment` c ON c.objectId = a.id
//        LEFT JOIN `100_user` u ON u.id = c.authorId
//        WHERE c.type=4 AND c.seen=0 AND a.authorId = 8 AND c.dateTime>NOW()-INTERVAL 30 DAY
//        ORDER BY c.id DESC

        $feedArray = [];

        $feed = Yii::app()->db->createCommand()
            ->select('q.id, c.type, q.title, c.text, u.name, COUNT(*) counter')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'a.questionId = q.id')
            ->leftJoin('{{comment}} c', 'c.objectId = a.id')
            ->leftJoin('{{user}} u', 'u.id = c.authorId')
            ->where('c.type=4 AND c.seen=0 AND a.authorId = :userId AND c.dateTime>NOW()-INTERVAL :days DAY', [':userId' => $this->id, ':days' => $days])
            ->group('q.id')
            ->order('c.id DESC')
            ->queryAll();

        if (true == $returnCount) {
            return sizeof($feed);
        }

        foreach ($feed as $row) {
            $feedArray[] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'title' => $row['title'],
                'text' => $row['text'],
                'name' => $row['name'],
                'counter' => $row['counter'],
            ];
        }

        return $feedArray;
    }

    /**
     * Возвращает сумму бонуса, которая причитается за приглашенного пользователя.
     */
    public function referalOk()
    {
        // Если пользователя никто не пригласил, бонуса нет
        if (!$this->refId) {
            return 0;
        }

        // для разных ролей действуют разные правила расчета бонусов
        if (User::ROLE_JURIST == $this->role) {
            $answersCount = $this->answersCount;
            $isVerified = $this->settings->isVerified;

            if (1 == $isVerified && $answersCount >= 25) {
                return Yii::app()->params['bonuses'][User::ROLE_JURIST];
            }
        }

        if (User::ROLE_CLIENT == $this->role) {
            $questionsCount = $this->questionsCount;
            if (1 == $this->active100 && $questionsCount > 0) {
                return Yii::app()->params['bonuses'][User::ROLE_CLIENT];
            }
        }
    }

    /**
     * Возвращает текст сообщения для юриста с советом заполнить поле в профиле.
     *
     * @return string Сообщение для пользователя
     */
    public function getProfileNotification()
    {
        if (self::ROLE_JURIST != $this->role || !$this->settings) {
            return false;
        }

        // найдем последний запрос на смену статуса
        $lastRequest = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{userStatusRequest}}')
            ->where('yuristId=:id AND isVerified=0', [':id' => $this->id])
            ->order('id DESC')
            ->limit(1)
            ->queryAll();

        $editProfilePage = CHtml::link('Обновить профиль', Yii::app()->createUrl('user/update', ['id' => $this->id]), ['class' => 'yellow-button']);
        $editQualificationPage = CHtml::link('Подтвердить', Yii::app()->createUrl('userStatusRequest/create'), ['class' => 'yellow-button']);

        // если не подтверждена квалификация
        if (!$this->settings->isVerified && 0 == sizeof($lastRequest)) {
            return 'Пожалуйста, подтвердите свою квалификацию. ' . $editQualificationPage;
        }

        // если не загружен аватар
        if (!$this->avatar) {
            return 'Пожалуйста, загрузите свою фотографию. Юристы с фотографией вызывают больше доверия и учавствуют в рейтингах. ' . $editProfilePage;
        }

        // если не заполнено приветствие
        if (!$this->settings->hello) {
            return 'Пожалуйста, заполните текст приветствия в своем профиле. ' . $editProfilePage;
        }

        // если не заполнены контакты (должен быть либо телефон, либо мейл)
        if (!$this->settings->phoneVisible && !$this->settings->emailVisible) {
            return 'Пожалуйста, укажите телефон или Email в своем профиле чтобы клиенты могли с вами связаться. ' . $editProfilePage;
        }

        // если не заполнены специализации
        if (!$this->settings->description) {
            return 'Пожалуйста, напишите немного о себе в своем профиле, это увеличит доверие со стороны клиента. ' . $editProfilePage;
        }

        // если не заполнены специализации
        if (!sizeof($this->categories)) {
            return 'Пожалуйста, укажите свои специализации в профиле. ' . $editProfilePage;
        }

        return '';
    }

    /**
     * Возвращает ссылку на смену пароля пользователя.
     *
     * @return mixed
     */
    public function getChangePasswordLink()
    {
        $changePasswordLink = Yii::app()->createUrl('user/setNewPassword', ['email' => $this->email, 'code' => $this->confirm_code]);

        return $changePasswordLink;
    }

    /**
     * Создает пользователя в Yurcrm через запрос к API.
     *
     * @param string $passwordRaw Нешифрованный пароль
     *
     * @return YurcrmResponse
     */
    public function createUserInYurcrm($passwordRaw)
    {
        if (!in_array($this->role, [self::ROLE_BUYER, self::ROLE_JURIST])) {
            return null;
        }

        $tariff = Yii::app()->params['yurcrmDefaultTariff'];

        $this->yurcrmClient->setRoute('user/create');
        $this->yurcrmClient->setData([
            'tariff' => $tariff,
            'user[name]' => $this->name,
            'user[lastName]' => $this->lastName,
            'user[email]' => $this->email,
            'user[phone]' => $this->phone,
            'user[password1]' => $passwordRaw,
            'user[password2]' => $passwordRaw,
        ]);
        $createUserResult = $this->yurcrmClient->send();

        return $createUserResult;
    }

    /**
     * Вытаскивает из ответа Yurcrm данные созданного пользователя: yurcrmToken, yurcrmSource.
     *
     * @param YurcrmResponse $crmResponse
     */
    public function getYurcrmDataFromResponse(YurcrmResponse $crmResponse)
    {
        if ($crmResponse->getResponse()) {
            $crmResponseDecoded = json_decode($crmResponse->getResponse(), true);

            if ($crmResponseDecoded['data'] && $crmResponseDecoded['data']['company'] && $crmResponseDecoded['data']['company']['token']) {
                $this->yurcrmToken = $crmResponseDecoded['data']['company']['token'];
            }
            if ($crmResponseDecoded['data'] && $crmResponseDecoded['data']['source100yuristov']) {
                $this->yurcrmSource = $crmResponseDecoded['data']['source100yuristov'];
            }
        }
    }

    /**
     * Получение статистики лидов вебмастера.
     */
    public function getWebmasterLeadsStats($periodDays = 10)
    {
        $statsArray = [];

        $query = 'SELECT DATE(l.question_date) create_date, l.leadStatus, COUNT(l.id) counter, r.name region_name
            FROM {{lead}} l
            LEFT JOIN {{town}} t ON t.id = l.townId
            LEFT JOIN {{region}} r ON r.id = t.regionId
            WHERE sourceId IN (
                SELECT id from {{leadsource}} WHERE userId = :userId
            )
            AND DATE(l.question_date) >= NOW() - INTERVAL :days DAY
            GROUP BY DATE(l.question_date), l.leadStatus, region_name
            ORDER BY l.id DESC';

        $statsRows = Yii::app()->db->createCommand($query)
            ->bindParam(':userId', $this->id, PDO::PARAM_INT)
            ->bindParam(':days', $periodDays, PDO::PARAM_INT)
            ->queryAll();

        foreach ($statsRows as $row) {
            $statsArray[$row['create_date']][$row['region_name']]['total_leads'] += $row['counter'];

            if (in_array($row['leadStatus'], [Lead::LEAD_STATUS_BRAK, Lead::LEAD_STATUS_NABRAK])) {
                $statsArray[$row['create_date']][$row['region_name']]['brak_leads'] += $row['counter'];
            }
        }

        return $statsArray;
    }

    /**
     * Отправка юристу уведомления о зачислении благодарности за консультацию.
     *
     * @param Answer $answer
     * @param int $yuristBonus В копейках
     *
     * @return bool
     */
    public function sendDonateNotification(Answer $answer, $yuristBonus)
    {
        return $this->notifier->sendDonateNotification($answer, $yuristBonus);
    }

    public function sendDonateChatNotification(Chat $answer, $yuristBonus)
    {
        return $this->notifier->sendDonateChatNotification($answer, $yuristBonus);
    }
    /**
     * Отправка юристу уведомления о новом отзыве.
     */
    public function sendTestimonialNotification()
    {
        return $this->notifier->sendTestimonialNotification();
    }

    public function getLastOnline()
    {
        $data = Yii::app()->db->createCommand('select created from 100_chat_messages where user_id =:id order by created DESC')->bindParam(':id', $this->id)->queryScalar();
        if ($data) {
            return date('d.m.Y H:i', $data);
        }
        return 'Была давно';
    }
    /**
     * Рейтинг пользователя как среднее арифметическое оценок из неспамных отзывов.
     *
     * @return float
     * @TODO А не проще получить это число SQL-запросом?
     */
    public function getRating()
    {
        $rating = 0;
        $ratingsSum = 0;
        $commentsNumber = 0;

        foreach ($this->comments as $comment) {
            if (Comment::STATUS_SPAM != $comment->status) {
                $ratingsSum += $comment->rating;
                ++$commentsNumber;
            }
        }

        if ($commentsNumber > 0) {
            $rating = round($ratingsSum / $commentsNumber, 1);
        }

        return $rating;
    }

    /**
     * @param int|null $limit Если null, то без лимита
     * @param bool|array $pagination
     *
     * @return CActiveDataProvider
     */
    public function getTestimonialsDataProvider($limit = 5, $pagination = false)
    {
        $testimonialsCriteria = new CDbCriteria();
        $testimonialsCriteria->order = 'id DESC';
        if (!is_null($limit)) {
            $testimonialsCriteria->limit = $limit;
        }

        $testimonialsCriteria->addColumnCondition([
            'type' => Comment::TYPE_USER,
            'status!' => Comment::STATUS_SPAM,
            'objectId' => $this->id,
        ]);

        $testimonialsDataProvider = new CActiveDataProvider(Comment::class, [
            'criteria' => $testimonialsCriteria,
            'pagination' => $pagination,
        ]);

        return $testimonialsDataProvider;
    }

    /**
     * Возвращает название ранга пользователя.
     *
     * @return string|null
     *
     * @throws Exception
     */
    public function getRangName()
    {
        $rangName = null;
        if ($this->settings) {
            $yuristRangs = new YuristRang(Yii::app()->params['rangs']);
            $rangsInfo = $yuristRangs->getRangInfo($this->settings->rang);
        }
        $rangName = (isset($rangsInfo['name'])) ? $rangsInfo['name'] : null;

        return $rangName;
    }

    /**
     * Проверяет, какого ранга достоин пользователь.
     * Если большего, чем сейчас, увеличиваем ранг и отправляем уведомление.
     */
    public function detectRang()
    {
        $yuristSetting = $this->settings;
        $yuristRangs = new YuristRang(Yii::app()->params['rangs']);
        $newRang = $yuristRangs->detectRang($this);

        if ($yuristSetting->rang != $newRang) {
            $yuristSetting->rang = $newRang;

            if ($yuristSetting->save()) {
                $newRangInfo = $yuristRangs->getRangInfo($newRang);
                $this->sendNewRangNotification($newRangInfo);
            } else {
                LoggerFactory::getLogger()->log('Не удалось сменить ранг пользователя', 'User', $this->id);
            }
        }
    }

    /**
     * Отправляет письмо юристу с уведомлением о смене ранга.
     *
     * @param array $newRangInfo
     *
     * @return bool
     */
    public function sendNewRangNotification($newRangInfo)
    {
        return $this->notifier->sendNewRangNotification($newRangInfo);
    }

    /**
     * Получение числа вопросов, заданных пользователем за последние часы.
     *
     * @param int $intervalHours Количество часов
     *
     * @return mixed
     */
    public function getRecentQuestionCount($intervalHours)
    {
        $myRecentQuestionsCount = Yii::app()->db->createCommand()
            ->select('COUNT(id) counter')
            ->from('{{question}}')
            ->where('authorId=:id AND createDate > NOW() - INTERVAL :hours HOUR', [':id' => $this->id, ':hours' => $intervalHours])
            ->queryScalar();

        return $myRecentQuestionsCount;
    }

    /**
     * Определяет показывать ли кнопку чата
     * @return bool
     */
    public function getIsShowChatButton(){
         $answersCnt = Answer::model()->count('authorId=:id', [':id' => $this->id]);
         $minQnt = Yii::app()->params['MinAnswerQntForChat'];

         return $answersCnt >= $minQnt;

     }



}
