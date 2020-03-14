<?php


class QuestionController extends Controller
{
    public $layout = '//admin/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + toSpam', // we only allow deletion via POST request
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
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'view', 'getRandom', 'nocat', 'vip'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_JURIST . ') || Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['update', 'view', 'index', 'byPublisher', 'toSpam', 'setCategory', 'setTitle', 'duplicates'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_EDITOR . ')',
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update', 'admin', 'delete', 'publish', 'setPubTime', 'setTitles', 'duplicates', 'notifyYurists'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ],
            ['deny', // deny all users
                'users' => ['*'],
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
        $model = Question::model()->findByPk($id);

        $criteria = new CDbCriteria();
        $criteria->order = 't.id DESC';
        $criteria->addColumnCondition(['questionId' => $model->id]);

        $answersDataProvider = new CActiveDataProvider('Answer', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('view', [
            'model' => $model,
            'answersDataProvider' => $answersDataProvider,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Question();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Question'])) {
            $model->attributes = $_POST['Question'];
            if ($model->save()) {
                if (isset($_POST['Question']['categories'])) {
                    foreach ($_POST['Question']['categories'] as $categoryId) {
                        $q2cat = new Question2category();
                        $q2cat->qId = $model->id;
                        $q2cat->cId = $categoryId;
                        if (!$q2cat->save()) {
                        }
                    }
                } /* else {
                  // если не указана категория поста
                  $q2cat = new Question2category();
                  $q2cat->qId = $model->id;
                  $q2cat->cId = QuestionCategory::NO_CATEGORY;
                  if($q2cat->save());
                  } */
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // $allCategories - массив, ключи которого - id категорий, значения - названия
        $allCategories = QuestionCategory::getCategoriesIdsNames();
        if (isset($_GET['categoryId'])) {
            $categoryId = (int) $_GET['categoryId'];
        } else {
            $categoryId = null;
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('create', [
            'model' => $model,
            'allCategories' => $allCategories,
            'categoryId' => $categoryId,
            'townsArray' => $townsArray,
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
        $oldStatus = $model->status;
        $model->setScenario('convert'); // чтобы поле Email было необязательным
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Question'])) {
            $model->attributes = $_POST['Question'];
            if (Question::STATUS_MODERATED == $model->status && Question::STATUS_NEW == $oldStatus) {
                $model->publishDate = date('Y-m-d H:i:s');
                $model->publishedBy = Yii::app()->user->id;
            }
            if ($model->save()) {
                if (isset($_POST['Question']['categories'])) {
                    // удалим старые привязки вопроса к категориям
                    Question2category::model()->deleteAllByAttributes(['qId' => $model->id]);
                    // привяжем вопрос к категориям
                    foreach ($_POST['Question']['categories'] as $categoryId) {
                        $q2cat = new Question2category();
                        $q2cat->qId = $model->id;
                        $q2cat->cId = $categoryId;
                        if (!$q2cat->save()) {
                        }
                    }
                } /* else {
                  $q2cat = new Question2category();
                  $q2cat->qId = $model->id;
                  $q2cat->cId = QuestionCategory::NO_CATEGORY;
                  if($q2cat->save());
                  } */

                $this->redirect(['view', 'id' => $model->id, 'question_updated' => 'yes']);
            }
        }

        // $allCategories - массив, ключи которого - id категорий, значения - названия
        $allCategories = QuestionCategory::getCategoriesIdsNames();

        $townsArray = Town::getTownsIdsNames();

        $this->render('update', [
            'model' => $model,
            'allCategories' => $allCategories,
            'townsArray' => $townsArray,
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.id DESC';

        if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {
            $answersCountRelation = 'answersCount';
        } else {
            $answersCountRelation = ['answersCount' => [
                    'having' => 's=0',
            ]];
        }

        if (isset($_GET['moderatedBy'])) {
            $criteria->addColumnCondition(['moderatedBy' => (int) $_GET['moderatedBy']]);
            $criteria->order = 'moderatedTime DESC';
            $moderator = User::model()->findByPk((int) $_GET['moderatedBy']);
        } else {
            $moderator = null;
        }

        if (!isset($_GET['nocat'])) {
            $criteria->with = [
                'categories',
                'town',
                $answersCountRelation,
                'bublishUser',
            ];
            $nocat = false;
        } else {
            // если нужно показать опубликованные вопросы без категории
            $criteria->with = [
                'categories' => [
                    'on' => 'categories.id IS NULL',
                ],
                'town',
                (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)) ? 'answersCount' : 'answersCount' => [
                    'having' => 's=0',
                ],
            ];
            //$criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED, 't.status' =>  Question::STATUS_CHECK), "OR", "AND");
            $criteria->addCondition('t.status = ' . Question::STATUS_PUBLISHED . ' OR t.status = ' . Question::STATUS_CHECK);
            $nocat = true;
        }

        if (isset($_GET['notown'])) {
            $criteria->addColumnCondition(['t.status' => Question::STATUS_PUBLISHED]);
            $criteria->addColumnCondition(['t.townId' => 0]);
            $notown = true;
        }

        if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {
            // админу и контент-менеджеру позволяем фильтровать вопросы по статусу
            if (isset($_GET['status'])) {
                $status = (int) $_GET['status'];
                $criteria->addColumnCondition(['t.status' => $status]);
                if (Question::STATUS_NEW === $status && isset($_GET['email_unconfirmed'])) {
                    $criteria->addColumnCondition(['t.email!' => '']);
                } elseif (Question::STATUS_NEW === $status && !isset($_GET['email_unconfirmed'])) {
                    $criteria->addColumnCondition(['t.email' => '']);
                }
            } else {
                $status = null;
            }
        } else {
            // юристу показываем вопросы со статусами Модерирован и Опубликован
            $criteria->addInCondition('t.status', [Question::STATUS_MODERATED, Question::STATUS_PUBLISHED]);
        }

        $dataProvider = new CActiveDataProvider('Question', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $allDirections = QuestionCategory::getDirections();

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'nocat' => $nocat,
            'notown' => $notown,
            'allDirections' => $allDirections,
            'moderator' => $moderator,
        ]);
    }

    // вывод списка вопросов без категорий
    public function actionNocat()
    {
        //SELECT q.id, q2c.cId, COUNT(*) counter FROM `crm_question` q LEFT JOIN `crm_question2category` q2c on q.id=q2c.qId WHERE q2c.cId IS NULL AND q.status IN(2, 4) GROUP BY q.id ORDER BY q2c.cId

        $questions = Yii::app()->db->createCommand()
                ->select('q.id id, questionText, status, title, createDate, publishDate')
                ->from('{{question}} q')
                ->leftJoin('{{question2category}} q2c', 'q.id=q2c.qId')
                ->where('q2c.cId IS NULL AND q.status IN(' . Question::STATUS_PUBLISHED . ',' . Question::STATUS_CHECK . ', ' . Question::STATUS_MODERATED . ')')
                ->group('q.id')
                ->order('q.id DESC')
                ->limit(30)
                ->queryAll();
        $questionRepository = new QuestionRepository();
        $questionRepository->setCacheTime(600)->setLimit(10);

        $questionsCount = $questionRepository->countNoCat();

        $allDirections = QuestionCategory::getDirections(true, true);

        $this->render('nocat', [
            'questions' => $questions,
            'allDirections' => $allDirections,
            'questionsCount' => $questionsCount,
        ]);
    }

    // вывод списка вопросов без категорий
    public function actionVip()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.id DESC';

        $criteria->addColumnCondition(['t.payed' => 1]);

        $dataProvider = new CActiveDataProvider('Question', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('vip', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // выводит список вопросов, одобренных заданным пользователем с id=$id
    public function actionByPublisher($id)
    {
        $publisher = User::model()->findByPk($id);
        if (!$publisher) {
            throw new CHttpException(404, 'Пользователь не найден');
        }

        $criteria = new CDbCriteria();
        $criteria->order = 't.id desc';

        $criteria->addColumnCondition(['publishedBy' => (int) $id]);

        $dataProvider = new CActiveDataProvider('Question', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('byPublisher', [
            'dataProvider' => $dataProvider,
            'publisher' => $publisher,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Question('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Question'])) {
            $model->attributes = $_GET['Question'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function actionPublish()
    {
        $sqlCommandResult = Yii::app()->db->createCommand('UPDATE {{question}} SET status=' . Question::STATUS_PUBLISHED . ', publishDate=NOW() WHERE status=' . Question::STATUS_MODERATED)->execute();
        $this->redirect('/question');
    }

    public function actionToSpam()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
        }
        $model = $this->loadModel($id);
        $model->status = Question::STATUS_SPAM;
        if ($model->save()) {
            echo CJSON::encode(['id' => $id, 'status' => 1]);
        } else {
            //print_r($model->errors);
            echo CJSON::encode(['status' => 0]);
        }
    }

    public function actionGetRandom()
    {
        $question = Yii::app()->db->createCommand()
                ->select('q.id id, questionText, townId, authorName')
                ->from('{{question q}}')
                ->leftJoin('{{answer a}}', 'a.questionId = q.id')
                ->where('q.status=:status AND a.id IS NULL', [':status' => Question::STATUS_PUBLISHED])
                ->order('RAND()')
                ->limit(1)
                ->queryRow();

        if ($question) {
            echo CJSON::encode([
                'question' => nl2br(mb_substr(CHtml::encode($question['questionText']), 0, 300, 'utf-8')),
                'name' => $question['authorName'],
                'town' => Town::getName($question['townId']),
                'code' => 0,
                'id' => $question['id'],
            ]);
        } else {
            echo 'NULL';
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Question the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Question::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Question $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'question-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSetPubTime()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'TIME(publishDate) = "00:00:00" AND status=' . Question::STATUS_PUBLISHED;
        //$criteria->limit=10;
        $questions = Question::model()->findAll($criteria);

        foreach ($questions as $question) {
            $oldDate = $question->publishDate;
            $dateArray = explode(' ', $oldDate);

            $oldTime = $dateArray[1];
            $oldDate = $dateArray[0];

            $newTime = mt_rand(0, 23) . ':' . mt_rand(0, 59) . ':' . mt_rand(0, 59);

            $question->publishDate = $oldDate . ' ' . $newTime;

            $question->save();
        }
    }

    public function actionSetCategory()
    {
        $catId = ($_POST['catId']) ? (int) $_POST['catId'] : false;
        $questionId = ($_POST['questionId']) ? (int) $_POST['questionId'] : false;

        if (false == $questionId || false == $catId) {
            echo CJSON::encode(['questionId' => $questionId, 'status' => 400]);

            return;
        }
        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);

        $q2c = new Question2category();
        $q2c->qId = $questionId;
        $q2c->cId = $catId;

        if ($q2c->save()) {
            // проверим, не является ли указанная категория дочерней
            // если является, найдем ее родителя и запишем в категории вопроса
            foreach ($allDirectionsHierarchy as $parentId => $parentCategory) {
                if (!$parentCategory['children']) {
                    continue;
                }

                foreach ($parentCategory['children'] as $childId => $childCategory) {
                    if ($childId == $catId) {
                        $q2cat = new Question2category();
                        $q2cat->qId = $questionId;
                        $q2cat->cId = $parentId;
                        $q2cat->save();
                        break;
                    }
                }
            }
            echo CJSON::encode(['questionId' => $questionId, 'status' => 0]);

            return;
        } else {
            echo CJSON::encode(['questionId' => $questionId, 'status' => 500]);

            return;
        }
    }

    /**
     * Быстрое редактирование вопроса - заголовок и текст
     */
    public function actionSetTitle()
    {
        if (isset($_POST['my']) || isset($_GET['my'])) {
            $showMy = true;
        } else {
            $showMy = false;
        }

        if (isset($_POST['Question'])) {
            // если была отправлена форма, сохраним вопрос
            $id = $_POST['Question']['id'];
            $question = Question::model()->findByPk($id);
            $question->attributes = $_POST['Question'];
            $question->isModerated = 1;
            $question->moderatedBy = Yii::app()->user->id;
            $question->moderatedTime = date('Y-m-d H:i:s');
            $question->status = Question::STATUS_PUBLISHED;

            if ($question->save()) {
                setcookie('lastModeratedQuestionId', $question->id);
                //Yii::app()->user->setState('lastModeratedQuestionId', $question->id);
                if ($showMy) {
                    $this->redirect(['question/setTitle', 'my' => 1]);
                } else {
                    $this->redirect(['question/setTitle']);
                }
            }
        } elseif (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $question = Question::model()->findByPk($id);
        } else {
            // если формы не было, найдем немодерированный вопрос в базе и выведем форму
            $criteria = new CDbCriteria();
            $criteria->limit = 1;

            // Если задано показывать модерированные мной вопросы, выбираем 1 вопрос, который был модерирован максимально давно
            if (true == $showMy) {
                $criteria->order = 'moderatedTime ASC';
                $criteria->addColumnCondition(['isModerated' => 1, 'moderatedBy' => Yii::app()->user->id]);
            } else {
                $criteria->order = 'id DESC';
                $criteria->addColumnCondition(['isModerated' => 0]);
            }

            $criteria->addCondition('status IN (' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED . ', ' . Question::STATUS_MODERATED . ')');

            $question = Question::model()->find($criteria);
        }
        $questionRepository = new QuestionRepository();
        $questionRepository->setCacheTime(600)->setLimit(10);

        $questionsCount = $questionRepository->countForModerate();


        $questionsModeratedByMe = Yii::app()->db->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{question}}')
                ->where('isModerated=1 AND status IN (:status1, :status2, :status3) AND moderatedBy=:userId', [':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED, ':status3' => Question::STATUS_MODERATED, ':userId' => Yii::app()->user->id])
                ->queryRow();
        $questionsModeratedByMeCount = $questionsModeratedByMe['counter'];

        /*
         * вытащим статистику по пользователям, которые модерируют вопросы
         * SELECT u.name, u.lastName, COUNT(*) counter FROM `100_question` q LEFT JOIN `100_user` u ON u.id=q.moderatedBy WHERE q.moderatedBy!=0 GROUP BY u.id
         */

        $moderatorsStats = Yii::app()->db->createCommand()
                ->select('u.id, u.name, u.lastName, COUNT(*) counter')
                ->from('{{question}} q')
                ->leftJoin('{{user}} u', 'u.id=q.moderatedBy')
                ->where('q.moderatedBy!=0')
                ->group('u.id')
                ->order('counter DESC')
                ->queryAll();

        $this->render('setTitle', [
            'model' => $question,
            'questionsCount' => $questionsCount,
            'questionsModeratedByMeCount' => $questionsModeratedByMeCount,
            'moderatorsStats' => $moderatorsStats,
            'showMy' => $showMy,
        ]);
    }

    /**
     * Находит несколько вопросов с одинаковым текстом и показывает их.
     */
    public function actionDuplicates()
    {
        $questions = [];

//            SELECT id, md5(questionText), COUNT(*) counter
//            FROM `100_question`
//            WHERE status IN (2,4)
//            GROUP BY MD5(questionText)
//            HAVING counter>1
//            ORDER BY counter DESC
//            LIMIT 1

        $md5 = Yii::app()->db->createCommand()
                ->select('id, md5(title) hash, COUNT(*) counter')
                ->from('{{question}}')
                ->where('status IN (2,4)')
                ->group('MD5(title)')
                ->having('counter>1')
                ->order('counter DESC')
                ->limit(1)
                ->queryRow();

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['MD5(title)' => $md5['hash']]);
        $criteria->addInCondition('status', [Question::STATUS_CHECK, Question::STATUS_PUBLISHED]);
        $dataProvider = new CActiveDataProvider(
            'Question',
            [
            'criteria' => $criteria, ]
        );

        $this->render('duplicates', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNotifyYurists()
    {
        Question::sendRecentQuestionsNotifications();
    }
}
