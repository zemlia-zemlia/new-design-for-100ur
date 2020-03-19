<?php

use App\helpers\DateHelper;
use App\helpers\StringHelper;
use App\models\Comment;
use App\models\Lead;
use App\models\PartnerTransaction;
use App\models\Question;
use App\models\QuestionCategory;
use App\models\RestorePasswordForm;
use App\models\Town;
use App\models\User;
use App\models\User2category;
use App\models\UserFile;
use App\models\YuristSettings;

class UserController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//admin/main';
    public $defaultAction = 'profile';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
            ['allow',
                'actions' => ['restorePassword', 'captcha', 'confirm', 'registerInCrm'],
                'users' => ['*'],
            ],
            ['allow', // действия, разрешенные для всех авторизованных пользователей
                'actions' => ['profile', 'update', 'view', 'removeAvatar', 'changePassword'],
                'users' => ['@'],
            ],
            ['allow', // действия, разрешенные для всех пользователей типа менеджер
                'actions' => ['create', 'ConfirmationSent', 'index', 'delete', 'stats', 'verifyFile', 'requests'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
            ],
            ['allow', // действия, разрешенные для всех пользователей типа менеджер
                'actions' => ['mystats'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->role == ' . User::ROLE_JURIST,
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
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

    /**
     * Displays a particular model.
     *
     * @param int $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = User::model()->findByPk($id);

        $transactionsDataProvider = new CArrayDataProvider($model->transactions);

        $leadsStats = null;

        $leadSearchModel = new Lead();
        $leadSearchModel->scenario = 'search';

        $commentModel = new Comment();

        if (User::ROLE_BUYER == $model->role) {
            $leadSearchModel->attributes = $_GET['App\models\Lead'];

            // по умолчанию собираем статистику по проданным лидам за последние 30 дней
            $dateTo = ('' != $leadSearchModel->date2) ? DateHelper::invertDate($leadSearchModel->date2) : date('Y-m-d');
            $dateFrom = ('' != $leadSearchModel->date1) ? DateHelper::invertDate($leadSearchModel->date1) : date('Y-m-d', time() - 86400 * 30);
            $leadsStats = Lead::getStatsByPeriod($dateFrom, $dateTo, $model->id);
        }

        if (User::ROLE_PARTNER == $model->role) {
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(['partnerId' => $model->id]);
            $criteria->order = 'id DESC';

            $partnerTransactionsDataProvider = new CActiveDataProvider(PartnerTransaction::class, [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            $mySources = $model->sources;
            $mySourcesIds = [];
            foreach ($mySources as $source) {
                $mySourcesIds[] = $source->id;
            }

            $leadsCriteria = new CDbCriteria();
            $leadsCriteria->order = 'id DESC';
            $leadsCriteria->addInCondition('sourceId', $mySourcesIds);
            $leadsDataProvider = new CActiveDataProvider(Lead::class, [
                'criteria' => $leadsCriteria,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        }

        if (User::ROLE_CLIENT == $model->role) {
            $questionCriteria = new CDbCriteria();
            $questionCriteria->addColumnCondition(['authorId' => $model->id]);
            $questionCriteria->addInCondition('status', [Question::STATUS_PUBLISHED, Question::STATUS_CHECK]);
            $questions = Question::model()->findAll($questionCriteria);
        } else {
            $questions = [];
        }

        // если был отправлен комментарий
        if (isset($_POST['Comment'])) {
            // отправлен ответ, сохраним его
            $commentModel->attributes = $_POST['Comment'];
            $commentModel->authorId = Yii::app()->user->id;

            $commentModel->status = Comment::STATUS_CHECKED;

            // проверим, является ли данный комментарий дочерним для другого комментария
            if (isset($commentModel->parentId) && $commentModel->parentId > 0) {
                // является, сохраним его как дочерний комментарий
                $rootComment = Comment::model()->findByPk($commentModel->parentId);
                $commentModel->appendTo($rootComment);
            } else {
                // не является, ниже сохраним его как комментарий верхнего уровня
            }

            // сохраняем комментарий с учетом его иерархии и перенаправляем на текущую страницу
            if ($commentModel->saveNode()) {
                $this->redirect(['/admin/user/view', 'id' => $model->id]);
            }
        }

        $this->render('view', [
            'transactionsDataProvider' => $transactionsDataProvider,
            'model' => $model,
            'leadsStats' => $leadsStats,
            'searchModel' => $leadSearchModel,
            'commentModel' => $commentModel,
            'partnerTransactionsDataProvider' => $partnerTransactionsDataProvider,
            'leadsDataProvider' => $leadsDataProvider,
            'questions' => $questions,
        ]);
    }

    public function actionProfile()
    {
        $userId = Yii::app()->user->id;
        $model = User::model()->findByPk($userId);

        $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario('create');
        $yuristSettings = new YuristSettings();
        $allDirections = QuestionCategory::getDirections();

        // массив кодов и названий ролей пользователей
        $rolesNames = User::getRoleNamesArray();

        // массив пользователей  ролью Менеджер
        $allManagersNames = User::getManagersNames();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            // генерируем пароль для пользователя
            $newPassword = User::generatePassword(8);
            $model->password = $newPassword;
            $model->confirm_code = md5($model->email . mt_rand(100000, 999999));

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
            }

            if ($model->save()) {
                /*
                 * после сохранения модели, в свойстве password хранится зашифрованный пароль
                 * а в переменной $newPassword - незашифрованный
                 */
                if (User::ROLE_EXECUTOR == $model->role) {
                    // при регистрации исполнителя не отправляем ему письмо
                    $this->redirect(['ConfirmationSent']);
                }

                // если мы добавили юриста
                if (User::ROLE_JURIST == $model->role && isset($_POST['App\models\YuristSettings'])) {
                    $yuristSettings->attributes = $_POST['App\models\YuristSettings'];
                    $yuristSettings->yuristId = $model->id;
                    $yuristSettings->save();
                }

                if ($model->sendConfirmation($newPassword)) {
                    $this->redirect(['ConfirmationSent']);
                } else {
                    throw new CHttpException(500, 'Что-то пошло не так. Мы не смогли отправить пользователю письмо с подтверждением регистрации на сайте. Не беспокойтесь, с аккаунтом все в порядке, просто письмо с подтверждением не отправилось.');
                }
            }
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('create', [
            'model' => $model,
            'rolesNames' => $rolesNames,
            'allManagersNames' => $allManagersNames,
            'yuristSettings' => $yuristSettings,
            'townsArray' => $townsArray,
            'allDirections' => $allDirections,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->setScenario('update');

        $allDirections = QuestionCategory::getDirections();

        if ($model->settings) {
            $yuristSettings = $model->settings;
        } else {
            $yuristSettings = new YuristSettings();
            $yuristSettings->yuristId = $model->id;
        }

        if (!Yii::app()->user->checkAccess(User::ROLE_MANAGER) && Yii::app()->user->id !== $model->id) {
            throw new CHttpException(403, 'У вас нет прав редактировать этот профиль');
            //echo "У вас нет прав редактировать этот профиль";
        }

        // массив кодов и названий ролей пользователей
        $rolesNames = User::getRoleNamesArray();

        // массив пользователей  ролью Менеджер
        $allManagersNames = User::getManagersNames();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            // присваивание атрибутов пользователя
            $model->attributes = $_POST['User'];
            $yuristSettings->attributes = $_POST['App\models\YuristSettings'];

            // если мы редактировали юриста
            if (isset($_POST['App\models\YuristSettings'])) {
                $yuristSettings->attributes = $_POST['App\models\YuristSettings'];

                $yuristSettings->save();
            }

            if (isset($_POST['User']['categories'])) {
                // удалим старые привязки пользователя к категориям
                User2category::model()->deleteAllByAttributes(['uId' => $model->id]);
                // привяжем пользователя к категориям
                foreach ($_POST['User']['categories'] as $categoryId) {
                    $u2cat = new User2category();
                    $u2cat->uId = $model->id;
                    $u2cat->cId = $categoryId;
                    if (!$u2cat->save()) {
                    }
                }
            }

            // загрузка аватарки
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
            }
            if ($model->save() && false == $yuristSettings->hasErrors()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->password = '';
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('update', [
            'model' => $model,
            'rolesNames' => $rolesNames,
            'allManagersNames' => $allManagersNames,
            'yuristSettings' => $yuristSettings,
            'townsArray' => $townsArray,
            'allDirections' => $allDirections,
        ]);
    }

    public function actionChangePassword($id)
    {
        // если пользователь не админ, он может менять пароль только у себя
        if (Yii::app()->user->id !== $id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
            throw new CHttpException(403, 'У вас нет прав менять пароль другого пользователя');
        }

        $model = User::model()->findByPk($id);
        $model->password = '';

        // если была заполнена форма
        if ($_POST['User']) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                // если данные пользователя прошли проверку (пароль не слишком короткий)
                // шифруем пароль перед сохранением в базу
                $model->password = User::hashPassword($model->password);
                $model->password2 = $model->password;
                if ($model->save()) {
                    $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.active100 DESC, t.id DESC';
        $criteria->with = 'town';

        // если не задано, каких пользователей выводить, выводим юристов

        $role = (isset($_GET['role'])) ? (int) $_GET['role'] : User::ROLE_JURIST;

        $roles = User::getRoleNamesArray();
        $roleName = $roles[$role];

        if (User::ROLE_BUYER == $role) {
            $criteria->with = ['town', 'campaignsCount'];
        }
        $criteria->addColumnCondition(['t.role' => $role]);

        $usersDataProvider = new CActiveDataProvider(User::class, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('index', [
            'usersDataProvider' => $usersDataProvider,
            'role' => $role,
            'roleName' => $roleName,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function actionConfirmationSent()
    {
        $this->render('confirmationSent');
    }

    public function actionConfirm()
    {
        $email = CHtml::encode($_GET['email']);
        $code = CHtml::encode($_GET['code']);

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['email' => $email]);
        $criteria->addColumnCondition(['confirm_code' => $code]);
        $criteria->addColumnCondition(['active100' => 0]);
        $criteria->limit = 1;

        //находим пользователя с данным мейлом и кодом подтверждения
        $user = User::model()->find($criteria);

        if (!empty($user)) {
            $user->setScenario('confirm');
            if (0 == $user->active100) {
                $user->activate();
            }

            if ($user->save()) {
                if (Yii::app()->user->isGuest) {
                    $this->render('activationSuccess');
                } else {
                    $this->redirect('/user');
                }
            } else {
                if (!empty($user->errors)) {
                    echo '<pre>';
                    print_r($user->errors);
                    echo '</pre>';
                }
                $this->render('activationFailed', ['message' => 'Ошибка - не удалось активировать аккаунт из-за ошибки в программе.<br />
                      Обратитесь, пожалуйста, к администратору сайта через E-mail ' . Yii::app()->params['adminEmail']]);
            }
        } else {
            $this->render('activationFailed', ['message' => 'Пользователь с данным мейлом не найден или уже активирован']);
        }
    }

    // восстановление пароля пользователя
    public function actionRestorePassword()
    {
        $this->layout = '//frontend/login';

        // $model - модель с формой восстановления пароля
        $model = new RestorePasswordForm();

        if (isset($_POST['App\models\RestorePasswordForm'])) {
            // получили данные из формы восстановления пароля
            $model->attributes = $_POST['App\models\RestorePasswordForm'];
            $email = CHtml::encode($model->email);
            // ищем пользователя по введенному Email, если не найден, получим NULL
            $user = User::model()->findByAttributes(['email' => $email]);
            if ($user) {
                // если пользователь существует, генерируем ему новый пароль
                $newPassword = User::generatePassword(6);
                $user->setScenario('restorePassword');
                if ($user->changePassword($newPassword)) {
                    // если удалось изменить пароль
                    $message = 'Пароль изменен и отправлен на E-mail';
                } else {
                    // если не удалось изменить пароль
                    $message = 'Ошибка! Не удалось изменить пароль';
                }
                $this->render('restorePassword', ['model' => $model, 'message' => $message]);
            }
        } else {
            // форма не была отправлена, отображаем форму
            $this->render('restorePassword', ['model' => $model]);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return User the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'user-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionRemoveAvatar($id)
    {
        $user = User::model()->findByPk($id);
        $user->scenario = 'removeAvatar';

        if ($user->id != Yii::app()->user->id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
            throw new CHttpException(403, 'Отказано в доступе к удалению аватара');
        }

        @unlink(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . '/' . $user->avatar);
        @unlink(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $user->avatar);
        $user->avatar = '';

        if ($user->save()) {
            $this->redirect(['user/view', 'id' => $user->id]);
        } else {
            StringHelper::printr($user->errors);
            throw new CHttpException(500, 'Не удалось удалить аватар');
        }
    }

    // показывает юристу его показатели
    public function actionMystats()
    {
        if ($_GET['month']) {
            $month = (int) $_GET['month'];
        } else {
            $month = date('n');
        }
        if ($_GET['year']) {
            $year = (int) $_GET['year'];
        } else {
            $year = date('Y');
        }

        // найдем все годы, в которые есть контакты
        $yearsRows = Yii::app()->db->cache(600)->createCommand()
                ->select('DISTINCT(YEAR(question_date)) y')
                ->from('{{contact}}')
                ->where('YEAR(question_date)!=0')
                ->queryColumn();
        $yearsArray = [];
        foreach ($yearsRows as $k => $v) {
            $yearsArray[$v] = $v;
        }

        // находим все контакты заданных юристов, появившиеся в заданный месяц
        $leadsRows = Yii::app()->db->createCommand()
                ->select('COUNT(*) counter, c.status, u.id userId')
                ->from('{{contact}} c')
                ->join('{{user}} u', 'c.employeeId = u.id')
                ->where(['and', 'u.id = :uid', 'MONTH(question_date)=:month', 'YEAR(question_date)=:year'], [':month' => $month, ':year' => $year, ':uid' => Yii::app()->user->id])
                ->group('c.status')
                ->queryAll();

        $leadsArray = [];

        foreach ($leadsRows as $leadsRow) {
            if (Contact::STATUS_LEAD == $leadsRow['status'] || Contact::STATUS_CLIENT == $leadsRow['status'] || Contact::STATUS_CONSULT == $leadsRow['status']) {
                $leadsArray['active'] += $leadsRow['counter'];
            } elseif (Contact::STATUS_BRAK == $leadsRow['status']) {
                $leadsArray['brak'] += $leadsRow['counter'];
            } elseif (Contact::STATUS_NABRAK == $leadsRow['status']) {
                $leadsArray['nabrak'] += $leadsRow['counter'];
            } elseif (Contact::STATUS_OTKAZ == $leadsRow['status']) {
                $leadsArray['otkaz'] += $leadsRow['counter'];
            }
            $leadsArray['total'] += $leadsRow['counter'];
        }

        // находим договоры, заключенные в выбранный месяц с разбивкй по каналам
        $agreementsRows = Yii::app()->db->cache(60)->createCommand()
                ->select('COUNT(*) counter, SUM(a.totalPrice) sum, a.status agreement_status')
                ->from('{{agreement}} a')
                ->join('{{contact}} c', 'a.contactId = c.id')
                ->where(['and', 'a.employeeId = :uid', 'MONTH(a.create_date)=:month', 'YEAR(a.create_date)=:year'], [':month' => $month, ':year' => $year, ':uid' => Yii::app()->user->id])
                ->group('agreement_status')
                ->queryAll();

        $agreementsArray = [];

        foreach ($agreementsRows as $agreementsRow) {
            // отдельно посчитаем данные по расторгнутым договорам
            if (Agreement::STATUS_ABORTED == $agreementsRow['agreement_status']) {
                $agreementsArray['aborted']['counter'] += $agreementsRow['counter'];
                $agreementsArray['aborted']['sum'] += $agreementsRow['sum'];
            } else {
                // а отдельно - по всем остальным
                $agreementsArray['counter'] += $agreementsRow['counter'];
                $agreementsArray['sum'] += $agreementsRow['sum'];
            }
        }

        $this->render('myStats', [
            'month' => $month,
            'year' => $year,
            'yearsArray' => $yearsArray,
            'leadsArray' => $leadsArray,
            'agreementsArray' => $agreementsArray,
        ]);
    }

    // верифицирует или не верифицирует скан
    public function actionVerifyFile()
    {
        $fileId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $isVerified = (isset($_POST['verified'])) ? (int) $_POST['verified'] : false;
        $reason = (isset($_POST['reason'])) ? $_POST['reason'] : '';

        if (!$fileId || false === $isVerified) {
            throw new CHttpException(400, 'Ошибка: недостаточно данных');
        }

        $file = UserFile::model()->findByPk($fileId);

        if (!$file) {
            throw new CHttpException(404, 'Ошибка: файл, который вы пытаетесь верифицировать, не найден');
        }

        $file->isVerified = $isVerified;
        if ($reason) {
            $file->reason = $reason;
        }

        if ($file->save()) {
            echo json_encode(['code' => 0, 'fileId' => $file->id]);
        } else {
            StringHelper::printr($file->errors);
            echo json_encode(['code' => 500, 'fileId' => $file->id, 'message' => 'При верификации файла произошла ошибка']);
        }
    }

    // вывод списка пользователей, у которых есть загруженные файлы в статусе На проверке
    public function actionRequests()
    {
        // SELECT * FROM `crm_userFile` f LEFT JOIN `crm_user` u ON f.userId=u.id where f.`isVerified`=0 ORDER BY f.datetime DESC
        $users = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{userFile}} f')
                ->leftJoin('{{user}} u', 'f.userId = u.id')
                ->where('f.`isVerified`=' . UserFile::STATUS_REVIEW)
                ->order('f.datetime DESC')
                ->queryAll();

        $this->render('requests', [
            'users' => $users,
        ]);
    }

    /**
     * Регистрация пользователя в CRM через API клиент
     *
     * @param int $id
     *
     * @throws CHttpException
     *
     * @return string JSON ответ
     */
    public function actionRegisterInCrm($id)
    {
        header('Content-type: application/json');
        $user = User::model()->findByPk($id);

        if (!$user) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        $crmResponse = $user->createUserInYurcrm(User::generatePassword(10));
        $user->getYurcrmDataFromResponse($crmResponse);

        $crmResponseDecoded = json_decode($crmResponse->getResponse(), true);
        if (200 == (int) $crmResponseDecoded['status']) {
            $user->save();
        }

        echo json_encode($crmResponse);
        Yii::app()->end();
    }
}
