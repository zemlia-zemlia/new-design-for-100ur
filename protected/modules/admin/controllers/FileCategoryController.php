<?php

class FileCategoryController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//admin/main';

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
        return
            [
                [
                    'allow',
                    'actions' => ['createModalForObject'],
                    'users' => ['*'],
                ],
                [
                    'allow',
                    'actions' => ['index', 'view',  'create', 'update', 'delete'],
                    'users' => ['@'],
                    'expression' => 'Yii::app()->user->checkAccess('.User::ROLE_EDITOR.') || Yii::app()->user->checkAccess('.User::ROLE_ROOT.')',
                ],

                [
                    'deny', // deny all users
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
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = 0)
    {
        $model = new FileCategory();
        $model->active = 1;


        if (isset($_POST['FileCategory'])) {
            if (0 != $id) {
                $model->attributes = $_POST['FileCategory'];
                $root = FileCategory::model()->findByPk($id);
                $model->appendTo($root);
            } else {
                $model->attributes = $_POST['FileCategory'];

                $model->saveNode();
            }

            Yii::app()->user->setFlash('success', 'Категория добавлена');
            if ($id) {
                $url =  Yii::app()->createUrl('/admin/fileCategory/view', ['id' => $id]);
            } else {
                $url = Yii::app()->createUrl('/admin/docs/index');
            }

            return $this->redirect($url);
        }

        $this->render('create', [
            'model' => $model,
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


        if (isset($_POST['FileCategory'])) {
            $model->attributes = $_POST['FileCategory'];

            if ($model->saveNode()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
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
        $model = $this->loadModel($id);
        $parent_id = $model->parentObj->id;
        $childrens = $model->children()->findAll();
        $model->active = 0;
        $model->saveNode();
        foreach ($childrens as $child) {
            $child->active = 0;
            $child->saveNode();
        }

        if ($parent_id) {
            $url =  Yii::app()->createUrl('/admin/fileCategory/view', ['id' => $parent_id]);
        } else {
            $url = Yii::app()->createUrl('/admin/docs/index');
        }

        Yii::app()->user->setFlash('success', 'Категория удалена');

        return $this->redirect($url);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('FileCategory');
        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return FileCategory the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = FileCategory::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    public function actionCreateModalForObject($id = 0)
    {
        if (0 != $id) {
            $category = $this->loadModel($id);
        } else {
            $category = null;
        }
        if (!$category) {
            $categories = FileCategory::model()->roots()->findAll();
        } else {
            $categories = $category->children()->findAll();
        }

        $this->renderPartial('index-files', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }
}
