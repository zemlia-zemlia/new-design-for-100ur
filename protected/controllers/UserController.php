<?php

class UserController extends Controller {

    public $layout = '//frontend/question';
    public $defaultAction = 'profile';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'confirm', 'confirmationSent', 'restorePassword', 'setNewPassword', 'captcha', 'unsubscribe'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'profile', 'changePassword', 'updateAvatar', 'invites', 'deleteAvatar', 'clearInfo', 'requestConfirmation', 'karmaPlus', 'stats', 'sendAnswerNotification'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'foreColor' => 0xff0000,
                'minLength' => 6,
                'maxLength' => 8,
            ),
        );
    }

    public function actionProfile() {
        $this->layout = '//frontend/question';

        $user = User::model()->findByPk(Yii::app()->user->id);

        $questionsCriteria = new CDbCriteria;

        if (Yii::app()->user->role == User::ROLE_CLIENT) {
            $questionsCriteria->addColumnCondition(array('t.authorId' => Yii::app()->user->id));
            $questionsCriteria->addCondition('t.status IN(' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED . ')');
        } else {
            $questionsCriteria->with = array(
                'answers' => array(
                    'condition' => 'answers.authorId = ' . Yii::app()->user->id,
            ));
        }

        $questionsCriteria->order = 't.id DESC';

        $questions = Question::model()->findAll($questionsCriteria);


        $questionsDataProvider = new CArrayDataProvider($questions, array(
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        // найдем последний запрос на смену статуса
        $lastRequest = Yii::app()->db->createCommand()
                ->select('isVerified, status')
                ->from("{{userStatusRequest}}")
                ->where("yuristId=:id", array(':id' => $user->id))
                ->order('id DESC')
                ->limit(1)
                ->queryRow();

        //CustomFuncs::printr($lastRequest);


        $this->render('profile', array(
            'questionsDataProvider' => $questionsDataProvider,
            'questions'             => $questions,
            'user'                  => $user,
            'lastRequest'           => $lastRequest,
        ));
    }

    // creating a new user by registration form 
    public function actionCreate() {
        $this->layout = '//frontend/smart';
        $model = new User;
        $yuristSettings = new YuristSettings;
        $model->setScenario('register');


        $model->role = (isset($_GET['role'])) ? (int) $_GET['role'] : 0;

        // при регистрации юриста действуют отдельные правила проверки полей
        if ($model->role == User::ROLE_JURIST) {
            $model->setScenario('createJurist');
        }

        $rolesNames = array(
            User::ROLE_CLIENT => 'Пользователь',
            User::ROLE_JURIST => 'Юрист',
        );

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];

            // можно зарегистрироваться с ролью Юрист, Пользователь, покупатель
            // все, кто не юристы и покупатели - пользователи
            if ($model->role != User::ROLE_JURIST && $model->role != User::ROLE_BUYER && $model->role != User::ROLE_PARTNER) {
                $model->role = User::ROLE_CLIENT;
            }

            $model->confirm_code = md5($model->email . mt_rand(100000, 999999));
            $model->password = $model->password2 = User::generatePassword(6);

            if ($model->save()) {
                // после сохранения юриста сохраним запись о его настройках
                if ($model->role == User::ROLE_JURIST) {
                    $yuristSettings->yuristId = $model->id;
                    $yuristSettings->save();
                }
                if ($model->sendConfirmation()) {
                    $this->redirect(array('ConfirmationSent', 'role' => $model->role));
                } else {
                    throw new CHttpException(500, 'Что-то пошло не так. Мы не смогли отправить Вам письмо с подтверждением регистрации на сайте. Не беспокойтесь, с вашим аккаунтом все в порядке, просто письмо с подтверждением придет немного позже.');
                }
            }
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('create', array(
            'model' => $model,
            'yuristSettings' => $yuristSettings,
            'townsArray' => $townsArray,
            'rolesNames' => $rolesNames,
        ));
    }

    // страница редактирования пользователя 
    public function actionUpdate($id) {
        $this->layout = '//frontend/smart';
        $model = User::model()->findByPk($id);

        $allDirections = QuestionCategory::getDirections(true, true);

        if (!$model) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        if ($model->id != Yii::app()->user->id && Yii::app()->user->role != User::ROLE_ROOT) {
            throw new CHttpException(403, 'Ошибка доступа: вы не можете редактировать чужой профиль');
        }

        if ($model->role == User::ROLE_JURIST) {
            $model->setScenario('updateJurist');
        } else {
            $model->setScenario('update');
        }

        $newUser = (isset($_GET['newUser'])) ? true : false;

        // модель для работы со сканом
        $userFile = new UserFile;



        if ($model->role == User::ROLE_JURIST) {
            if ($model->settings) {
                $yuristSettings = $model->settings;
            } else {
                $yuristSettings = new YuristSettings();
                $yuristSettings->yuristId = $model->id;
            }
        } else {
            $yuristSettings = new YuristSettings();
        }

        $rolesNames = array(
            User::ROLE_CLIENT => 'Пользователь',
            User::ROLE_JURIST => 'Юрист',
        );

        if (isset($_POST['User'])) {
            // присваивание атрибутов пользователя
            $model->attributes = $_POST['User'];
            $yuristSettings->attributes = $_POST['YuristSettings'];

            // если мы редактировали юриста
            if (isset($_POST['YuristSettings'])) {
                $yuristSettings->attributes = $_POST['YuristSettings'];

                $yuristSettings->save();
            }

            if (isset($_POST['User']['categories'])) {
                // удалим старые привязки пользователя к категориям
                User2category::model()->deleteAllByAttributes(array('uId' => $model->id));
                // привяжем пользователя к категориям
                foreach ($_POST['User']['categories'] as $categoryId) {
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

                if ($file && $file->getError() == 0) { // если файл нормально загрузился
                    // определяем имя файла для хранения на сервере
                    $newFileName = md5($file->getName() . $file->getSize() . mt_rand(10000, 100000)) . "." . $file->getExtensionName();
                    Yii::app()->ih
                            ->load($file->tempName)
                            ->resize(600, 600, true)
                            ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . '/' . $newFileName)
                            ->reload()
                            ->adaptiveThumb(120, 120, array(255, 255, 255))
                            ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $newFileName);

                    $model->avatar = $newFileName;
                }

                $scan = CUploadedFile::getInstance($userFile, 'userFile');
                if ($scan && $scan->getError() == 0) { // если файл нормально загрузился
                    $scanFileName = md5($scan->getName() . $scan->getSize() . mt_rand(10000, 100000)) . "." . $scan->getExtensionName();
                    Yii::app()->ih
                            ->load($scan->tempName)
                            ->save(Yii::getPathOfAlias('webroot') . UserFile::USER_FILES_FOLDER . '/' . $scanFileName);
                    // CustomFuncs::printr($scan);
                    // exit;

                    $userFile->userId = Yii::app()->user->id;
                    $userFile->name = $scanFileName;
                    $userFile->type = $yuristSettings->status;

                    if (!$userFile->save()) {
                        echo "Не удалось сохранить скан";
                        CustomFuncs::printr($userFile->errors);
                        exit;
                    }
                }
            }

            if ($model->save()) {
                if ($model->role == User::ROLE_JURIST && $yuristSettings->hasErrors() == false) {
                    $this->redirect(array('profile'));
                }
                if ($model->role == User::ROLE_BUYER) {
                    $this->redirect(array('/cabinet'));
                } else {
                    $this->redirect(array('profile'));
                }
            } else {
                CustomFuncs::printr($model->errors);
                CustomFuncs::printr($yuristSettings->errors);
                throw new CHttpException(500, 'Что-то пошло не так. Не удалось сохранить данные профиля.');
            }
        } else {
            $model->password = '';
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('update', array(
            'model' => $model,
            'yuristSettings' => $yuristSettings,
            'userFile' => $userFile,
            'townsArray' => $townsArray,
            'rolesNames' => $rolesNames,
            'allDirections' => $allDirections,
            'newUser' => $newUser,
        ));
    }

    public function actionChangePassword($id) {
        $this->layout = '//frontend/question';
        // если пользователь не админ, он может менять пароль только у себя
        if (Yii::app()->user->id !== $id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
            throw new CHttpException(403, 'У вас нет прав менять пароль другого пользователя');
        }

        $model = User::model()->findByPk($id);
        $model->password = '';
        $model->setScenario('changePassword');

        // если была заполнена форма
        if ($_POST['User']) {
            $model->attributes = $_POST['User'];
//                CustomFuncs::printr($model);exit;
            if ($model->validate()) {
                // если данные пользователя прошли проверку (пароль не слишком короткий)
                // шифруем пароль перед сохранением в базу
                $model->password = User::hashPassword($model->password);
                $model->password2 = $model->password;
                if ($model->save()) {

                    $this->redirect(array('profile'));
                }
            }
        }
        $this->render('changePassword', array(
            'model' => $model,
        ));
    }

    public function actionConfirmationSent() {
        $this->layout = '//frontend/smart';

        $role = ($_GET['role'] == User::ROLE_JURIST) ? User::ROLE_JURIST : User::ROLE_CLIENT;
        $this->render('confirmationSent', array('role' => $role));
    }

    public function actionConfirm() {
        $this->layout = '//frontend/question';

        $email = CHtml::encode($_GET['email']);
        $code = CHtml::encode($_GET['code']);

        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array('email' => $email));
        $criteria->addColumnCondition(array('confirm_code' => $code));
        $criteria->limit = 1;

        //находим пользователя с данным мейлом и кодом подтверждения
        $user = User::model()->find($criteria);

        if (!empty($user)) {
            $user->setScenario('confirm');
            if ($user->active100 == 0) {
                $user->activate();
                $user->registerDate = date('Y-m-d');
                // при активации пользователя заменяем у него confirm_code, чтобы он смог сменить пароль, перейдя по ссылке в письме
                $user->confirm_code = $user->generateAutologinString();
                // задаем пользователю некий произвольный пароль, который на следующем шаге попросим сменить. Пароль в открытом виде не отсылаем пользователю
                $newPassword = $user->password = $user->password2 = $user->generatePassword(10);
                $publishedQuestionsNumber = $user->publishNewQuestions();
                $user->confirmOrders();
            }

            if ($user->save()) {
                // после активации и сохранения пользователя, отправим ему на почту ссылку на смену временного пароля
                if ($newPassword) {
                    //$user->sendNewPassword($newPassword);
                    $user->sendChangePasswordLink();
                }

                // логиним пользователя
                $loginModel = new LoginForm;
                $loginModel->email = $email;
                $loginModel->password = $newPassword;

                if ($loginModel->login()) {
                    // если залогинили, находим последний вопрос и перенаправляем на страницу вопроса

                    $questionCriteria = new CDbCriteria;
                    $questionCriteria->addCondition('authorId=' . $user->id);
                    $questionCriteria->order = 'id DESC';
                    $questionCriteria->limit = 1;

                    $question = Question::model()->find($questionCriteria);
                    

                    if ($question) {
                        if($publishedQuestionsNumber) {
                            $this->redirect(array('question/view', 'id' => $question->id, 'justPublished' => 1));
                        } else {
                            $this->redirect(array('question/view', 'id' => $question->id));
                        
                        }
                    }

                    // если активированный пользователь - юрист, направляем его в форму редактирования профиля
                    if (Yii::app()->user->role == User::ROLE_JURIST) {
                        $this->redirect(array('user/update', 'id' => Yii::app()->user->id, 'newUser' => 1));
                    } elseif (Yii::app()->user->role == User::ROLE_BUYER) {
                        $this->redirect(array('/cabinet'));
                    } elseif (Yii::app()->user->role == User::ROLE_PARTNER) {
                        $this->redirect(array('/webmaster'));
                    }
                    $this->render('activationSuccess', array(
                        'user' => $user,
                        'loginModel' => $loginModel,
                        'question' => $question,
                    ));
                } else {
                    throw new CHttpException(400, 'Не удалось автоматически залогиниться на сайте');
                }
                /*
                 * 
                 */
            } else {
                if (!empty($user->errors)) {
                    print "<pre>";
                    print_r($user->errors);
                    print "</pre>";
                }

                $this->render('activationFailed', array('message' => 'Ошибка - не удалось активировать аккаунт из-за ошибки в программе.<br />
                      Обратитесь, пожалуйста, к администратору сайта через E-mail info@100yuristov.com'));
            }
        } else {
            $this->render('activationFailed', array('message' => 'Пользователь с данным мейлом не найден или уже активирован'));
        }
    }

    /**
     *  восстановление пароля пользователя
     * Страница с формой, где пользователь вводит свою почту, на которую отправляется ссылка для восстановления пароля
     */
    public function actionRestorePassword() {
        $this->layout = "//frontend/smart";
        // $model - модель с формой восстановления пароля
        $model = new RestorePasswordForm;

        if (isset($_POST['RestorePasswordForm'])) {
            // получили данные из формы восстановления пароля
            $model->attributes = $_POST['RestorePasswordForm'];
            $email = trim(strtolower(CHtml::encode($model->email)));
            // ищем пользователя по введенному Email, если не найден, получим NULL
            $user = User::model()->find('LOWER(email)=?', array($email));
            //$user = User::model()->find(array('LOWER(email)'=>$email));
            if ($user) {
                // если пользователь существует, отправим ему ссылку на смену пароля
                //$newPassword = User::generatePassword(6);
                $user->setScenario("restorePassword");
                if ($user->sendChangePasswordLink()) {
                    // если удалось изменить пароль
                    $message = "Ссылка на изменение пароля отправлена на Ваш E-mail";
                } else {
                    // если не удалось изменить пароль
                    $message = "Ошибка! Не удалось изменить пароль";
                }

                $this->render('restorePassword', array('model' => $model, 'message' => $message));
            } else {
                // форма не была отправлена, отображаем форму
                $model->addError('email', 'Пользователь не найден');
                $this->render('restorePassword', array('model' => $model));
            }
        } else {
            // форма не была отправлена, отображаем форму
            $this->render('restorePassword', array('model' => $model));
        }
    }

    /**
     * Форма установки нового пароля при восстановлении пароля
     */
    public function actionSetNewPassword() {
        // если пользователь уже залогинен, перенаправляем его на страницу смены пароля в его профиле
        if (!Yii::app()->user->isGuest) {
            $this->redirect(array("user/changePassword", 'id' => Yii::app()->user->id));
        }

        $this->layout = "//frontend/smart";

        $email = strtolower(CHtml::encode($_GET['email']));
        $code = CHtml::encode($_GET['code']);

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('email' => $email, 'confirm_code' => $code));
        // находим пользователя по присланным параметрам
        $user = User::model()->find($criteria);

        if (!$user) {
            throw new CHttpException(404, 'Пользователь не найден');
        }
        $user->setScenario('changePassword');
        $user->password = '';

        // если была заполнена форма
        if ($_POST['User']) {
            $user->attributes = $_POST['User'];
//                CustomFuncs::printr($model);exit;
            if ($user->validate()) {
                // если данные пользователя прошли проверку (пароль не слишком короткий)
                // шифруем пароль перед сохранением в базу
                $user->password = User::hashPassword($user->password);
                $user->password2 = $user->password;
                $user->confirm_code = '';

                if ($user->save()) {
                    $this->redirect(array('site/login'));
                } else {
                    //CustomFuncs::printr($user->errors);
                    throw new CHttpException(500, 'Ошибка, не удалось изменить пароль');
                }
            }
        }

        $this->render('changePassword', array(
            'model' => $user,
        ));
    }

    /**
     * 
     * @param type $id
     * @throws CHttpException
     */
    public function actionView($id) {
        $this->layout = '//frontend/question';

        $user = User::model()->with('settings')->findByPk($id);

        if (!$user || $user->role != User::ROLE_JURIST) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        $questions = Yii::app()->db->cache(600)->createCommand()
                ->select('q.id id, q.publishDate date, q.title title')
                ->from('{{question}} q')
                ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                ->where('a.id IS NOT NULL AND q.status IN (:status1, :status2) AND a.authorId = :authorId', array(':status1' => Question::STATUS_PUBLISHED, ':status2' => Question::STATUS_CHECK, ':authorId' => $user->id))
                ->limit(10)
                ->order('a.datetime DESC')
                ->queryAll();

//        $questionsDataProvider = new CArrayDataProvider($questions, array(
//            'pagination' => array(
//                'pageSize' => 20,
//            ),
//        ));
        
        $this->render('profile', array(
            'questionsDataProvider' => $questionsDataProvider,
            'questions'             =>  $questions,
            'user'                  => $user,
        ));
    }

    // отписаться от получения почтовых рассылок
    public function actionUnsubscribe() {
        $email = CHtml::encode($_GET['email']);
        $code = CHtml::encode($_GET['code']);

        if (User::verifyUnsubscribeCode($code, $email) === false) {
            throw new CHttpException(403, 'Неверный код проверки адреса электронной почты');
        }

        $model = User::model()->findByAttributes(array('email' => $email));
        if (!$model) {
            throw new CHttpException(400, 'Не удалось отписаться от рассылки, т.к. не найден пользователь с таким Email');
        }
        $model->setScenario('unsubscribe');
        $model->isSubscribed = 0;
        if (!$model->save()) {
            CustomFuncs::printr($model->errors);
            //throw new CHttpException(400, 'Не удалось отписаться от рассылки. Возможно, ваш профиль не заполнен. Войдите и проверьте заполненность профиля.');
        } else {
            $this->render('unsubscribeSuccess');
        }
    }

    public function actionKarmaPlus() {
        // разрешаем только POST запросы
        // параметр - answerId
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Only POST requests allowed');
        }


        $answerId = isset($_POST['answerId']) ? (int) $_POST['answerId'] : false;

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
                ->select("COUNT(*) counter")
                ->from("{{karmaChange}}")
                ->where("answerId=:answerId AND authorId=:authorId", array(':answerId' => $answerId, ':authorId' => Yii::app()->user->id))
                ->queryRow();

        //print_r($existingPluses);exit;

        if ($existingPluses['counter'] != 0) {
            throw new CHttpException(400, 'You have already voted for this user');
        }

        // делаем запись в таблице karmaChange
        $karmaInsertResult = Yii::app()->db->createCommand()
                ->insert("{{karmaChange}}", array(
            'userId' => $userId,
            'answerId' => $answerId,
            'authorId' => Yii::app()->user->id,
        ));

        // обновляем запись в таблице пользователей
        $userKarmaUpdateResult = Yii::app()->db->createCommand()
                ->update("{{user}}", array(
            'karma' => ($answer->author->karma + 1),
                ), "id=:id", array(
            ':id' => $userId,
        ));
        //print_r($userKarmaUpdateResult);
        // обновляем запись в таблице ответов
        $answerKarmaUpdateResult = Yii::app()->db->createCommand()
                ->update("{{answer}}", array(
            'karma' => ($answer->karma + 1),
                ), "id=:id", array(
            ':id' => $answerId,
        ));
        //print_r($answerKarmaUpdateResult);
        //exit;
        if ($karmaInsertResult && $answerKarmaUpdateResult && $userKarmaUpdateResult) {
            echo CJSON::encode(array('answerId' => $answerId, 'status' => 1));
        } else {
            echo CJSON::encode(array('answerId' => $answerId, 'status' => 0, 'message' => 'Ошибка!'));
        }
    }

    public function actionStats() {
        if (!Yii::app()->user->id) {
            // запрет доступа для гостей
            throw new CHttpException(403, 'Доступ к этой странице для Вас закрыт');
        }

        $userId = (isset($_GET['userId'])) ? (int) $_GET['userId'] : 0;

        if (!$userId && (Yii::app()->user->role == User::ROLE_OPERATOR || Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_CALL_MANAGER)) {
            // без указания id пользователя к странице могут обратиться только роли, отвечающие на вопросы
            $userId = Yii::app()->user->id;
        }

        if (!$userId) {
            // если не определен пользователь, для которого выводим статистику
            throw new CHttpException(400, 'Не задан ID пользователя');
        }

        $user = User::model()->findByPk($userId);

        if (!(Yii::app()->user->checkAccess(User::ROLE_MANAGER) || Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->checkAccess(User::ROLE_OPERATOR))) {
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
                ->select("COUNT(*) counter, MONTH(`datetime`) month, YEAR(`datetime`) year")
                ->from("{{answer}}")
                ->where("authorId=:userId AND status IN (:status1, :status2) AND datetime IS NOT NULL", array(':userId' => $userId, ":status1" => Answer::STATUS_NEW, "status2" => Answer::STATUS_PUBLISHED))
                ->group("year, month")
                ->order("datetime DESC")
                ->queryAll();

        $this->render('stats', array(
            'statsRows' => $statsRows,
            'user' => $user,
        ));
    }

}
