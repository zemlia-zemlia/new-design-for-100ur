<?php

use App\models\Comment;
use App\models\User;

class CommentController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'admin', 'delete', 'publish', 'setPubTime', 'toSpam'],
                        'users' => ['@'],
                        'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
                ],
                ['deny',  // deny all users
                        'users' => ['*'],
                ],
            ];
    }

    public function actionView($id)
    {
        $model = Comment::model()->findByPk($id);

        $this->render('view', [
                    'model' => $model,
            ]);
    }

    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.id DESC';
        $criteria->with = 'author';
        $status = null;

        if (isset($_GET['status'])) {
            $status = (int) $_GET['status'];
            $criteria->addColumnCondition(['t.status' => $status]);
        }

        if (isset($_GET['type'])) {
            $type = (int) $_GET['type'];
            $criteria->addColumnCondition(['t.type' => $type]);
        } else {
            $criteria->addCondition('t.type IN(' . Comment::TYPE_COMPANY . ', ' . Comment::TYPE_ANSWER . ')');
        }

        $dataProvider = new CActiveDataProvider('Comment', [
                    'criteria' => $criteria,
                    'pagination' => [
                                'pageSize' => 20,
                            ],
                ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
                        'status' => $status,
                        'type' => $type,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $oldStatus = $model->status;

        if (isset($_POST['Comment'])) {
            $model->attributes = $_POST['Comment'];

            if ($model->saveNode()) {
                $this->redirect(['view', 'id' => $model->id, 'question_updated' => 'yes']);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPublish()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
        }

        $model = $this->loadModel($id);
        $model->status = Comment::STATUS_CHECKED;
        if ($model->saveNode()) {
            echo CJSON::encode(['id' => $id, 'status' => 1]);
        } else {
            //print_r($model->errors);
            echo CJSON::encode(['status' => 0]);
        }
    }

    public function actionToSpam()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
        }
        $model = $this->loadModel($id);
        $model->status = Comment::STATUS_SPAM;
        if ($model->saveNode()) {
            echo CJSON::encode(['id' => $id, 'status' => 1]);
        } else {
            //print_r($model->errors);
            echo CJSON::encode(['status' => 0]);
        }
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->deleteNode();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    public function loadModel($id)
    {
        $model = Comment::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'Отзыв не найден');
        }

        return $model;
    }
}
