<?php

class DocsController extends Controller
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
                    'actions' => ['attachFilesToObject', 'deAttachFilesToObject', 'download'],
                    'users' => ['*'],
                ],
                [
                    'allow',
                    'actions' => ['index', 'view', 'create', 'update', 'delete'],
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

    public function actionCreate($id)
    {
        $model = new Docs();
        $model->setScenario('pull');
        $category = FileCategory::model()->findByPk($id);

        if (isset($_POST['Docs'])) {
            $model->attributes = $_POST['Docs'];
            $model->file = CUploadedFile::getInstance($model, 'file');
            if (!$model->file && $model->getIsNewRecord()) {
                Yii::app()->user->setFlash('error', 'Ошибка');
                $this->render('create', ['model' => $model, 'category' => $category]);
            }
            $name = $model->generateName();
            $path = Yii::getPathOfAlias('webroot').'/upload/files/'.$name;
            $model->file->saveAs($path);
            $model->type = $model->file->getExtensionName();
            $model->size = $model->file->getSize();
            $model->filename = $name;
            $model->uploadTs = time();
            if ($model->save()) {
                $category = new File2Category();
                $category->file_id = $model->id;
                $category->category_id = $id;
                if ($category->save()) {
                    Yii::app()->user->setFlash('success', 'Файл загружен');
                } else {
                    Yii::app()->user->setFlash('error', 'Ошибка');
                }

                return $this->redirect('/admin/file-category/view/?id='.$id);
            }
        }
        $this->render('create', ['model' => $model, 'category' => $category]);
    }

    public function actionDownload($id)
    {
        $model = $this->loadModel($id);

        return $this->redirect($model->getDownloadLink());
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

        if (isset($_POST['Docs'])) {
            $model->attributes = $_POST['Docs'];
            $model->file = CUploadedFile::getInstance($model, 'file');
            if ($model->file) {
                $name = $model->generateName();
                $path = Yii::getPathOfAlias('webroot').'/upload/files/'.$name;
                $model->file->saveAs($path);
                $model->type = $model->file->getExtensionName();
                $model->size = $model->file->getSize();
                unlink(Yii::getPathOfAlias('webroot').'/upload/files/'.$model->filename);
                $model->filename = $name;
            }
            $model->save();
            Yii::app()->user->setFlash('success', 'Файл изменен');

            return $this->redirect('/admin/file-category/view/?id='.$model->categories[0]->id);
            $this->redirect('index');
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
        $category = $model->categories[0];
        File2Category::model()->find('file_id = '.$id)->delete();
        unlink(Yii::getPathOfAlias('webroot').'/upload/files/'.$model->filename);
        $model->delete();
        Yii::app()->user->setFlash('success', 'Файл удален');

        return $this->redirect('/admin/file-category/view/?id='.$category->id);
    }

    /**
     * Lists all models.
     */
    public function actionIndex($id = 0)
    {
        if (0 != $id) {
            $category = FileCategory::model()->findByPk($id);
        } else {
            $category = null;
        }
        if (!$category) {
            $categories = FileCategory::model()->roots()->findAll();
        } else {
            $categories = $category->children()->findAll();
        }

        $this->render('index', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Docs the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Docs::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    public function actionAttachFilesToObject()
    {
        if (isset($_POST['fileIds']) && isset($_POST['objId'])) {
            $objId = $_POST['objId'];
            foreach ($_POST['fileIds'] as $fileId) {
                if (File2Object::model()->findAll('object_id = '.$objId.' AND file_id ='.$fileId)) {
                    continue;
                }
                $fileToObj = new File2Object();
                $fileToObj->file_id = $fileId;
                $fileToObj->object_id = $objId;
                $fileToObj->object_type = 1;
                $fileToObj->save();
            }
            $model = QuestionCategory::model()->findByPk($objId); ?>
            <?php if (is_array($model->docs)):
                foreach ($model->docs as $doc): ?>
                    <div>
                        <h6><?php echo CHtml::link(CHtml::encode($doc->name), '/admin/docs/download/?id='.$doc->id, ['target' => '_blank']); ?>
                            (<?php echo CHtml::encode($doc->downloads_count); ?>)
                            <a data="<?php echo $doc->id; ?>" id="deattach" href="">открепить</a></h6>
                    </div>
                <?php endforeach;
            endif;
        }

        return '<p>error</p>';
    }

    public function actionDeAttachFilesToObject()
    {
        if (isset($_POST['fileId']) && isset($_POST['objId'])) {
            $objId = $_POST['objId'];
            $fileId = $_POST['fileId'];
            File2Object::model()->find('object_id = '.$objId.' AND file_id ='.$fileId)->delete();
            $model = QuestionCategory::model()->findByPk($objId);
            if (is_array($model->docs)):
                foreach ($model->docs as $doc): ?>
                    <div>
                        <h6><?php echo CHtml::link(CHtml::encode($doc->name), '/admin/docs/download/?id='.$doc->id, ['target' => '_blank']); ?>
                            (<?php echo CHtml::encode($doc->downloads_count); ?>)
                            <a data="<?php echo $doc->id; ?>" id="deattach" href="">открепить</a></h6>
                    </div>
                <?php endforeach;
            endif;
        }

        return '<p>error</p>';
    }
}
