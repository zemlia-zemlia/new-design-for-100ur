<?php

use App\extensions\Logger\LoggerFactory;
use App\helpers\StringHelper;
use App\models\Answer;
use App\models\Chat;
use App\models\ChatMessages;
use App\models\Comment;
use App\models\LoginForm;
use App\models\Order;
use App\models\Question;
use App\models\QuestionCategory;
use App\models\RestorePasswordForm;
use App\models\Town;
use App\models\User;
use App\models\User2category;
use App\models\UserActivity;
use App\models\UserFile;
use App\models\YaPayConfirmRequest;
use App\models\YuristSettings;
use App\repositories\QuestionRepository;
use App\repositories\UserRepository;

class UserController extends Controller
{
    public $layout = '//frontend/question';
    public $defaultAction = 'profile';

    /** @var QuestionRepository */
    protected $questionRepository;

    /** @var UserRepository */
    protected $userRepository;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow', // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view', 'create', 'balanceAddRequest', 'confirmationSent', 'restorePassword', 'setNewPassword', 'captcha', 'unsubscribe'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['chats', 'chat', 'update', 'profile', 'changePassword', 'updateAvatar', 'invites', 'deleteAvatar', 'clearInfo', 'requestConfirmation', 'karmaPlus', 'stats', 'sendAnswerNotification', 'testimonial', 'testimonials'],
                'users' => ['@'],
            ],
            ['allow',
                'actions' => ['confirm'],
                'users' => ['?'],
            ],
            ['allow',
                'actions' => ['feed'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->role == App\models\User::ROLE_JURIST',
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionChats($chatId = null, $layerId = null)
    {
        $this->layout = '//frontend/chat';
        if ($chatId == 'new') {
            $chatId = uniqid('', true) . '_' . $layerId;
            $model = new Chat();
            $model->user_id = Yii::app()->user->id;
            $model->chat_id = $chatId;
            $model->lawyer_id = $layerId;
            $model->created = time();
            $model->save();
            $this->redirect('/user/chats?chatId=' . $chatId);
        }
        $criteria = new CDbCriteria();
        $criteria->condition = (Yii::app()->user->role == User::ROLE_CLIENT) ? 'user_id =:id' : 'lawyer_id=:id';
        $criteria->params = [':id' => Yii::app()->user->id];
        $criteria->order = 'is_closed ASC';
        $chats = Chat::model()->findAll($criteria);
        $messages = [];
        if (!$chatId and $chats) {
            $room = $chats[0]->chat_id;
        } else {
            $room = $chatId;
        }

        $chat = Chat::model()->find('chat_id = :id', [':id' => $room]);

        if ($chat && $chatId != null) {

            $mess = ChatMessages::model()->findAll('chat_id = :id', [':id' => $chat['id']]);
            foreach ($mess as $row) {
                $messages[] = [
                    'is_read' => $row->is_read,
                    'username' => $row->user->getShortName(),
                    'avatar' => $row->user->getAvatarUrl(),
                    'message' => $row->message,
                    'token' => $row->user->chatToken,
                    'date' => date('H:i', $row->created),
                ];
            }
            ChatMessages::model()->updateAll(
                ['is_read' => 1],
                'chat_id = :id and user_id != :user_id',
                [':id' => $chat['id'], ':user_id' => Yii::app()->user->id]
            );
        }

        $this->render('chats', [
                'role' => Yii::app()->user->role,
                'chats' => $chats,
                'curChat' => $chat,
                'messages' => $messages,
                'room' => $chatId,
                'user' => User::model()->findByPk(Yii::app()->user->id),
            ]
        );
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'CCaptchaAction',
                'foreColor' => 0xff0000,
                'minLength' => 6,
                'maxLength' => 8,
            ],
        ];
    }

    public function init()
    {
        parent::init();

        $this->questionRepository = new QuestionRepository();
        $this->userRepository = new UserRepository();
    }

    public function actionProfile()
    {
        $this->layout = '//frontend/lp';

        $user = $this->loadModel(Yii::app()->user->id);
        $this->questionRepository->setCacheTime(600)->setLimit(10);

        $ordersCriteria = new CDbCriteria(); // мои заказы документов

        if (User::ROLE_CLIENT == Yii::app()->user->role) {
            $questions = $this->questionRepository->findRecentQuestionsByClient($user);
            $ordersCriteria->addColumnCondition(['t.userId' => Yii::app()->user->id]);
            $ordersCriteria->order = 't.id DESC';

            $ordersDataProvider = new CActiveDataProvider(Order::class, [
                'criteria' => $ordersCriteria,
            ]);
        } else {
            $questions = $this->questionRepository->findRecentQuestionsByJuristAnswers($user);
            $ordersDataProvider = null;
        }

        // найдем последний запрос на смену статуса
        $lastRequest = Yii::app()->db->createCommand()
            ->select('isVerified, status')
            ->from('{{userStatusRequest}}')
            ->where('yuristId=:id', [':id' => $user->id])
            ->order('id DESC')
            ->limit(1)
            ->queryRow();

        $testimonialsDataProvider = $user->getTestimonialsDataProvider(5, false);

        $this->render('profile', [
            'questions' => $questions,
            'user' => $user,
            'lastRequest' => $lastRequest,
            'ordersDataProvider' => $ordersDataProvider,
            'testimonialsDataProvider' => $testimonialsDataProvider,
        ]);
    }

    // creating a new user by registration form
    public function actionCreate()
    {
        $this->layout = '//frontend/smart';
        $model = new User();
        $yuristSettings = new YuristSettings();
        $model->setScenario('register');

        $model->role = (isset($_GET['role'])) ? (int)$_GET['role'] : 0;

        // при регистрации юриста действуют отдельные правила проверки полей
        if (User::ROLE_JURIST == $model->role) {
            $model->setScenario('createJurist');
        }
        if (User::ROLE_BUYER == $model->role) {
            $model->setScenario('createBuyer');
        }

        $rolesNames = [
            User::ROLE_CLIENT => 'Пользователь',
            User::ROLE_JURIST => 'Юрист',
        ];

        if (isset($_POST['App_models_User'])) {
            $model->attributes = $_POST['App_models_User'];

            // можно зарегистрироваться с ролью Юрист, Пользователь, покупатель
            // все, кто не юристы и покупатели - пользователи
            if (User::ROLE_JURIST != $model->role && User::ROLE_BUYER != $model->role && User::ROLE_PARTNER != $model->role) {
                $model->role = User::ROLE_CLIENT;
            }

            $model->confirm_code = md5($model->email . mt_rand(100000, 999999));
            $model->password = $model->password2 = User::hashPassword(User::generatePassword(6));

            if ($model->save()) {
                // после сохранения юриста сохраним запись о его настройках
                if (User::ROLE_JURIST == $model->role) {
                    $yuristSettings->yuristId = $model->id;
                    $yuristSettings->save();
                }
                if ($model->sendConfirmation()) {
                    $this->redirect(['ConfirmationSent', 'role' => $model->role]);
                } else {
                    throw new CHttpException(500, 'Что-то пошло не так. Мы не смогли отправить Вам письмо с подтверждением регистрации на сайте. Не беспокойтесь, с вашим аккаунтом все в порядке, просто письмо с подтверждением придет немного позже.');
                }
            } else {
                var_dump($model->getErrors());
            }
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('create', [
            'model' => $model,
            'yuristSettings' => $yuristSettings,
            'townsArray' => $townsArray,
            'rolesNames' => $rolesNames,
        ]);
    }

    // страница редактирования пользователя
    public function actionUpdate($id)
    {
        ini_set('upload_max_filesize', '10M');
        $this->layout = '//frontend/smart';
        $model = $this->loadModel($id);

        $allDirections = QuestionCategory::getDirections(true, true);

        if (!$model) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        if ($model->id != Yii::app()->user->id && User::ROLE_ROOT != Yii::app()->user->role) {
            throw new CHttpException(403, 'Ошибка доступа: вы не можете редактировать чужой профиль');
        }

        if (User::ROLE_JURIST == $model->role) {
            $model->setScenario('updateJurist');
        } else {
            $model->setScenario('update');
        }

        $newUser = (isset($_GET['newUser'])) ? true : false;

        // модель для работы со сканом
        $userFile = new UserFile();

        if (User::ROLE_JURIST == $model->role) {
            if ($model->settings) {
                $yuristSettings = $model->settings;
            } else {
                $yuristSettings = new YuristSettings();
                $yuristSettings->yuristId = $model->id;
            }
        } else {
            $yuristSettings = new YuristSettings();
        }

        $rolesNames = [
            User::ROLE_CLIENT => 'Пользователь',
            User::ROLE_JURIST => 'Юрист',
        ];

        if (isset($_POST['App_models_User'])) {
            // присваивание атрибутов пользователя
            $model->attributes = $_POST['User'];
            $yuristSettings->attributes = $_POST['App_models_YuristSettings'];

            // если мы редактировали юриста
            if (isset($_POST['App_models_YuristSettings'])) {
                $yuristSettings->attributes = $_POST['App_models_YuristSettings'];
                $yuristSettings->priceDoc *= 100;
                $yuristSettings->priceConsult *= 100;
                $yuristSettings->save();
            }

            if (isset($_POST['App_models_User']['categories'])) {
                // удалим старые привязки пользователя к категориям
                User2category::model()->deleteAllByAttributes(['uId' => $model->id]);
                // привяжем пользователя к категориям
                foreach ($_POST['App_models_User']['categories'] as $categoryId) {
                    $u2cat = new User2category();
                    $u2cat->uId = $model->id;
                    $u2cat->cId = $categoryId;
                    if (!$u2cat->save()) {
                    }
                }
            }

            // загрузка аватарки и скана
            if (!empty($_FILES)) {
                $file = CUploadedFile::getInstance($model, 'avatarFile');

                if ($file && 0 == $file->getError()) { // если файл нормально загрузился
                    // определяем имя файла для хранения на сервере
                    $newFileName = md5($file->getName() . $file->getSize() . mt_rand(10000, 100000)) . '.' . $file->getExtensionName();
                    Yii::app()->ih
                        ->load($file->tempName)
                        ->resize(600, 600, true)
                        ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . '/' . $newFileName)
                        ->reload()
                        ->adaptiveThumb(120, 120, [255, 255, 255])
                        ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $newFileName);

                    $model->avatar = $newFileName;
                }

                $scan = CUploadedFile::getInstance($userFile, 'userFile');
                if ($scan && 0 == $scan->getError()) { // если файл нормально загрузился
                    $scanFileName = md5($scan->getName() . $scan->getSize() . mt_rand(10000, 100000)) . '.' . $scan->getExtensionName();
                    Yii::app()->ih
                        ->load($scan->tempName)
                        ->save(Yii::getPathOfAlias('webroot') . UserFile::USER_FILES_FOLDER . '/' . $scanFileName);

                    $userFile->userId = Yii::app()->user->id;
                    $userFile->name = $scanFileName;
                    $userFile->type = $yuristSettings->status;

                    if (!$userFile->save()) {
                        echo 'Не удалось сохранить скан';
                        StringHelper::printr($userFile->errors);
                        Yii::app()->end();
                    }
                }
            }

            if ($model->save()) {
                LoggerFactory::getLogger('db')->log('Пользователь ' . $model->getShortName() . ' обновил свой профиль', 'User', $model->id);
                (new UserActivity())->logActivity($model, UserActivity::ACTION_PROFILE_UPDATE);

                if (User::ROLE_JURIST == $model->role && false == $yuristSettings->hasErrors()) {
                    $this->redirect(['profile']);
                }
                if (User::ROLE_BUYER == $model->role) {
                    $this->redirect(['/buyer']);
                } else {
                    $this->redirect(['profile']);
                }
            } else {
            }
        } else {
            $model->password = '';
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('update', [
            'model' => $model,
            'yuristSettings' => $yuristSettings,
            'userFile' => $userFile,
            'townsArray' => $townsArray,
            'rolesNames' => $rolesNames,
            'allDirections' => $allDirections,
            'newUser' => $newUser,
        ]);
    }

    public function actionChangePassword($id)
    {
        $isNewUser = (bool)Yii::app()->request->getParam('newUser');
        $userPostData = Yii::app()->request->getParam('App_models_User');
        $this->layout = '//frontend/question';

        // если пользователь не админ, он может менять пароль только у себя
        if (Yii::app()->user->id !== $id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
            throw new CHttpException(403, 'У вас нет прав менять пароль другого пользователя');
        }

        $model = $this->loadModel($id);
        $model->password = '';
        $model->setScenario('changePassword');

        // если была заполнена форма
        if ($userPostData) {
            $model->attributes = $userPostData;
            if ($model->validate()) {
                // если данные пользователя прошли проверку (пароль не слишком короткий)
                // шифруем пароль перед сохранением в базу
                $model->password = User::hashPassword($model->password);
                $model->password2 = $model->password;
                if ($model->save()) {
                    $this->redirect(['profile']);
                }
            }
        }

        $this->render('changePassword', [
            'model' => $model,
            'isNewUser' => $isNewUser,
        ]);
    }

    /**
     * Отображение страницы с результатом отправки ссылки на подтверждение профиля.
     */
    public function actionConfirmationSent()
    {
        $this->layout = '//frontend/smart';

        $role = (User::ROLE_JURIST == $_GET['role']) ? User::ROLE_JURIST : User::ROLE_CLIENT;
        $this->render('confirmationSent', ['role' => $role]);
    }

    /**
     * Подтверждение Email пользователя.
     * Пользователь переходит по ссылке из письма, проверяем его код из ссылки, активируем
     *
     * @throws CHttpException
     */
    public function actionConfirm()
    {
        $this->layout = '//frontend/question';

        $email = CHtml::encode(Yii::app()->request->getParam('email'));
        $code = CHtml::encode(Yii::app()->request->getParam('code'));

        // находим пользователя с данным мейлом и кодом подтверждения
        $user = $this->userRepository->getUserByEmailAndConfirmationCode($email, $code);

        if ($user instanceof User) {
            $user->setScenario('confirm');

            $user->activate();
            $user->registerDate = date('Y-m-d');
            // при активации пользователя заменяем у него confirm_code, чтобы он смог сменить пароль, перейдя по ссылке в письме
            $user->confirm_code = $user->generateAutologinString();
            // задаем пользователю некий произвольный пароль, который на следующем шаге попросим сменить. Пароль в открытом виде не отсылаем пользователю
            $newPassword = $user->generatePassword(10);
            $user->password = $user->password2 = User::hashPassword($newPassword);
            // публикуем вопросы и заказы пользователя
            $publishedQuestionsNumber = $user->publishNewQuestions();
            $user->confirmOrders();

            if ($user->save()) {

                // после активации и сохранения пользователя, отправим ему на почту ссылку на смену временного пароля
                if ($newPassword) {
                    $user->sendChangePasswordLink();
                }

                // логиним пользователя
                $loginModel = new LoginForm();
                $loginModel->email = $email;
                $loginModel->password = $newPassword;

                if ($loginModel->login()) {

                    $question = $this->questionRepository->getLastQuestionOfUser($user);

                    if ($question) {
                        if ($publishedQuestionsNumber) {
                            $this->redirect(['question/view', 'id' => $question->id, 'justPublished' => 1]);
                        } else {
                            $this->redirect(['question/view', 'id' => $question->id]);
                        }
                    }

                    // если активированный пользователь - юрист, направляем его в форму редактирования профиля
                    if (User::ROLE_JURIST == Yii::app()->user->role) {
                        $this->redirect(['user/changePassword', 'id' => Yii::app()->user->id, 'newUser' => 1]);
                    } elseif (User::ROLE_BUYER == Yii::app()->user->role) {
                        $this->redirect(['/buyer']);
                    } elseif (User::ROLE_PARTNER == Yii::app()->user->role) {
                        $this->redirect(['/webmaster']);
                    }
                    $this->render('activationSuccess', [
                        'user' => $user,
                        'loginModel' => $loginModel,
                        'question' => $question,
                    ]);
                } else {
                    throw new CHttpException(400, 'Не удалось автоматически залогиниться на сайте');
                }
            } else {
                if (!empty($user->errors)) {
                    Yii::log(print_r($user->getErrors(), true), 'error', 'system.web');
                }

                $this->layout = '//frontend/smart';
                $this->render('activationFailed', ['message' => 'Ошибка - не удалось активировать аккаунт из-за ошибки в программе.<br />
                      Обратитесь, пожалуйста, к администратору сайта через E-mail info@100yuristov.com']);
            }
        }

        $this->layout = '//frontend/smart';
        $this->render('activationFailed', ['message' => 'Пользователь с данным мейлом не найден или уже активирован']);
    }

    /**
     *  восстановление пароля пользователя
     * Страница с формой, где пользователь вводит свою почту, на которую отправляется ссылка для восстановления пароля.
     */
    public function actionRestorePassword()
    {
        $this->layout = '//frontend/smart';
        // $model - модель с формой восстановления пароля
        $model = new RestorePasswordForm();

        if (isset($_POST['App_models_RestorePasswordForm'])) {
            // получили данные из формы восстановления пароля
            $model->attributes = $_POST['App_models_RestorePasswordForm'];
            $email = trim(strtolower(CHtml::encode($model->email)));
            // ищем пользователя по введенному Email, если не найден, получим NULL
            $user = User::model()->find('LOWER(email)=?', [$email]);

            if ($user) {
                // если пользователь существует, отправим ему ссылку на смену пароля
                //$newPassword = User::generatePassword(6);
                $user->setScenario('restorePassword');
                if ($user->sendChangePasswordLink()) {
                    // если удалось изменить пароль
                    $message = 'Ссылка на изменение пароля отправлена на Ваш E-mail';
                } else {
                    // если не удалось изменить пароль
                    $message = 'Ошибка! Не удалось изменить пароль';
                }

                $this->render('restorePassword', ['model' => $model, 'message' => $message]);
            } else {
                // форма не была отправлена, отображаем форму
                $model->addError('email', 'Пользователь не найден');
                $this->render('restorePassword', ['model' => $model]);
            }
        } else {
            // форма не была отправлена, отображаем форму
            $this->render('restorePassword', ['model' => $model]);
        }
    }

    /**
     * Форма установки нового пароля при восстановлении пароля.
     */
    public function actionSetNewPassword()
    {
        // если пользователь уже залогинен, перенаправляем его на страницу смены пароля в его профиле
        if (!Yii::app()->user->isGuest) {
            $this->redirect(['user/changePassword', 'id' => Yii::app()->user->id]);
        }

        $this->layout = '//frontend/smart';

        $email = strtolower(CHtml::encode($_GET['email']));
        $code = CHtml::encode($_GET['code']);

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['email' => $email, 'confirm_code' => $code]);
        // находим пользователя по присланным параметрам
        $user = User::model()->find($criteria);

        if (!$user) {
            throw new CHttpException(404, 'Пользователь не найден');
        }
        $user->setScenario('changePassword');
        $user->password = '';

        // если была заполнена форма
        if ($_POST['App_models_User']) {
            $user->attributes = $_POST['App_models_User'];
            if ($user->validate()) {
                // если данные пользователя прошли проверку (пароль не слишком короткий)
                // шифруем пароль перед сохранением в базу

                $passwordRaw = $user->password;

                $user->password = User::hashPassword($user->password);
                $user->password2 = $user->password;
                $user->confirm_code = '';

                if (User::ROLE_BUYER == $user->role && is_null($user->yurcrmToken)) {
                    // покупателя лидов добавляем в CRM
                    $crmResponse = $user->createUserInYurcrm($passwordRaw);
                    LoggerFactory::getLogger('db')->log('Создание пользователя в YurCRM. Ответ от API:' . $crmResponse->getResponse(), 'User', $user->id);

                    $user->getYurcrmDataFromResponse($crmResponse);
                }

                if ($user->save()) {
                    if ('' != $user->yurcrmToken) {
                        $this->redirect(['site/passwordChanged', 'yurcrm' => 1]);
                    } else {
                        $this->redirect(['site/passwordChanged']);
                    }
                } else {
                    throw new CHttpException(500, 'Ошибка, не удалось изменить пароль');
                }
            }
        }

        $this->render('changePassword', [
            'model' => $user,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->layout = '//frontend/lp';

        $user = $this->loadModel($id);

        if (!$user || User::ROLE_JURIST != $user->role) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        $questions = (new QuestionRepository())->findRecentQuestionsByJuristAnswers($user);

        $testimonialsDataProvider = $user->getTestimonialsDataProvider(5, false);
        $answersCnt = Answer::model()->count('authorId=:id', [':id' => $id]);
        $chat = Chat::model()->findByAttributes(['lawyer_id' => $id, 'is_closed' => null]);
        $minQnt = Yii::app()->params['MinAnswerQntForChat'];
        $this->render('profile', [
            'chat' => $chat,
            'showChatbutton' => $answersCnt >= $minQnt,
            'questions' => $questions,
            'user' => $user,
            'testimonialsDataProvider' => $testimonialsDataProvider,
        ]);
    }

    /**
     * @param $userId
     */
    public function actionTestimonials($id)
    {
        $user = $this->loadModel($id);

        $testimonialsDataProvider = $user->getTestimonialsDataProvider(null, [
            'pageSize' => 20,
        ]);

        $this->render('testimonials', [
            'yurist' => $user,
            'testimonialsDataProvider' => $testimonialsDataProvider,
        ]);
    }

    // отписаться от получения почтовых рассылок
    public function actionUnsubscribe()
    {
        $email = CHtml::encode($_GET['email']);
        $code = CHtml::encode($_GET['code']);

        if (false === User::verifyUnsubscribeCode($code, $email)) {
            throw new CHttpException(403, 'Неверный код проверки адреса электронной почты');
        }

        $model = User::model()->findByAttributes(['email' => $email]);
        if (!$model) {
            throw new CHttpException(400, 'Не удалось отписаться от рассылки, т.к. не найден пользователь с таким Email');
        }
        $model->setScenario('unsubscribe');
        $model->isSubscribed = 0;
        if (!$model->save()) {
            StringHelper::printr($model->errors);
            //throw new CHttpException(400, 'Не удалось отписаться от рассылки. Возможно, ваш профиль не заполнен. Войдите и проверьте заполненность профиля.');
        } else {
            $this->render('unsubscribeSuccess');
        }
    }

    public function actionKarmaPlus()
    {
        // разрешаем только POST запросы
        // параметр - answerId
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Only POST requests allowed');
        }

        $answerId = isset($_POST['answerId']) ? (int)$_POST['answerId'] : false;

        // если не передан id ответа
        if (!$answerId) {
            throw new CHttpException(400, 'Answer id not specified');
        }

        $answer = Answer::model()->findByPk($answerId);

        if (!$answer) {
            throw new CHttpException(404, 'Answer not found');
        }

        // id пользователя, написавшего ответ
        $userId = $answer->authorId;

        // проверим, не ставил ли плюс текущий пользователь заданному ответу
        $existingPluses = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{karmaChange}}')
            ->where('answerId=:answerId AND authorId=:authorId', [':answerId' => $answerId, ':authorId' => Yii::app()->user->id])
            ->queryRow();

        if (0 != $existingPluses['counter']) {
            throw new CHttpException(400, 'You have already voted for this user');
        }

        // делаем запись в таблице karmaChange
        $karmaInsertResult = Yii::app()->db->createCommand()
            ->insert('{{karmaChange}}', [
                'userId' => $userId,
                'answerId' => $answerId,
                'authorId' => Yii::app()->user->id,
            ]);

        // обновляем запись в таблице пользователей
        $userKarmaUpdateResult = Yii::app()->db->createCommand()
            ->update('{{user}}', [
                'karma' => ($answer->author->karma + 1),
            ], 'id=:id', [
                ':id' => $userId,
            ]);
        // обновляем запись в таблице ответов
        $answerKarmaUpdateResult = Yii::app()->db->createCommand()
            ->update('{{answer}}', [
                'karma' => ($answer->karma + 1),
            ], 'id=:id', [
                ':id' => $answerId,
            ]);
        if ($karmaInsertResult && $answerKarmaUpdateResult && $userKarmaUpdateResult) {
            echo CJSON::encode(['answerId' => $answerId, 'status' => 1]);
        } else {
            echo CJSON::encode(['answerId' => $answerId, 'status' => 0, 'message' => 'Ошибка!']);
        }
    }

    public function actionStats()
    {
        if (!Yii::app()->user->id) {
            // запрет доступа для гостей
            throw new CHttpException(403, 'Доступ к этой странице для Вас закрыт');
        }

        $userId = (isset($_GET['userId'])) ? (int)$_GET['userId'] : 0;

        if (!$userId && (User::ROLE_OPERATOR == Yii::app()->user->role || User::ROLE_JURIST == Yii::app()->user->role || User::ROLE_CALL_MANAGER == Yii::app()->user->role)) {
            // без указания id пользователя к странице могут обратиться только роли, отвечающие на вопросы
            $userId = Yii::app()->user->id;
        }

        if (!$userId) {
            // если не определен пользователь, для которого выводим статистику
            throw new CHttpException(400, 'Не задан ID пользователя');
        }

        $user = $this->loadModel($userId);

        if (!(Yii::app()->user->checkAccess(User::ROLE_MANAGER) || User::ROLE_JURIST == Yii::app()->user->role || Yii::app()->user->checkAccess(User::ROLE_OPERATOR))) {
            // запрет всем кроме менеджер+, операторов, юристов
            throw new CHttpException(403, 'Доступ к этой странице для Вас закрыт');
        }

        if (!Yii::app()->user->checkAccess(User::ROLE_MANAGER) && $userId !== Yii::app()->user->id) {
            // запретим операторам и юристам просмотр чужой статистики
            throw new CHttpException(403, 'Доступ к этой странице для Вас закрыт');
        }

        // найдем статистику ответов пользователя с разбивкой по месяцам
        //SELECT COUNT(*), MONTH(`datetime`) month, YEAR(`datetime`) year FROM `crm_answer`
        //WHERE authorId=8 AND status IN (0,1) AND datetime IS NOT NULL
        //GROUP BY year, month
        $statsRows = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter, MONTH(`datetime`) month, YEAR(`datetime`) year')
            ->from('{{answer}}')
            ->where('authorId=:userId AND status IN (:status1, :status2) AND datetime IS NOT NULL', [':userId' => $userId, ':status1' => Answer::STATUS_NEW, 'status2' => Answer::STATUS_PUBLISHED])
            ->group('year, month')
            ->order('datetime DESC')
            ->queryAll();

        $this->render('stats', [
            'statsRows' => $statsRows,
            'user' => $user,
        ]);
    }

    /**
     * Лента новостей пользователя (юриста). Содержит уведомления типа комментариев к его ответам и т.д.
     */
    public function actionFeed()
    {
        $user = $this->loadModel(Yii::app()->user->id);
        if (!$user) {
            throw new CHttpException(404, 'Ошибка: пользователь не найден');
        }

        $feedArray = $user->getFeed();

        $feedDataProvider = new CArrayDataProvider($feedArray, [
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('feed', [
            'feedDataProvider' => $feedDataProvider,
        ]);
    }

    /**
     * На этот адрес будут приходить запросы от Яндекса о пополнении кошелька
     * https://100yuristov.com/user/balanceAddRequest.
     */
    public function actionBalanceAddRequest()
    {
        Yii::log('Пришел запрос от Яндекса с уведомлением о пополнении баланса', 'info', 'system.web');
        Yii::log('POST запрос: ' . print_r($_POST, true), 'info', 'system.web');
        $yandexRequestData = new YaPayConfirmRequest();
        $yandexRequestData->setAttributes($_POST);

        $secret = Yii::app()->params['yandexMoneySecret'];
        $paymentProcessor = new YandexPaymentResponseProcessor($yandexRequestData, $secret);

        if (true != $paymentProcessor->process()) {
            Yii::log('Ошибка при обработке платежа: ' . print_r($paymentProcessor->getErrors(), true), 'error', 'system.web');
            throw new CHttpException(400, 'Cannot process payment');
        }
    }

    /**
     * Создание отзыва на юриста.
     *
     * @param $id
     */
    public function actionTestimonial($id)
    {
        $yurist = $this->loadModel($id);

        if ($yurist->id === Yii::app()->user->id) {
            throw new CHttpException(400, 'Вы не можете оставлять отзывы на себя');
        }
        $questionId = (int)Yii::app()->request->getParam('questionId');

        $commentModel = new Comment();
        $commentModel->setScenario('user');

        if (isset($_POST['App_models_Comment'])) {
            $commentModel->attributes = $_POST['App_models_Comment'];
            $commentModel->authorId = Yii::app()->user->id;
            $commentModel->questionId = $questionId;

            // сохраняем комментарий с учетом его иерархии
            if ($commentModel->saveNode()) {
                $this->redirect(['/user/view', 'id' => $yurist->id]);
            }
        }

        $this->render('testimonial', [
            'yurist' => $yurist,
            'commentModel' => $commentModel,
        ]);
    }

    /**
     * @param $id
     *
     * @return array|mixed|null
     *
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = User::model()->with('settings')->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        return $model;
    }
}
