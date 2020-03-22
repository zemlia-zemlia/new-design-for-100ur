<?php

use App\helpers\DateHelper;
use App\helpers\StringHelper;
use App\models\Campaign;
use App\models\File;
use App\models\Question;
use App\models\QuestionCategory;
use App\models\Town;
use App\models\User;
use App\modules\admin\controllers\AbstractAdminController;

class QuestionCategoryController extends AbstractAdminController
{
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
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'view', 'create', 'update', 'admin', 'delete', 'translit', 'showActiveUrls'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'view', 'create', 'update', 'ajaxGetList', 'directions', 'setDirectionParent', 'indexHierarchy'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_EDITOR . ')',
            ],
            ['deny',  // deny all users
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
        $model = QuestionCategory::model()->with('parent', 'children')->findByPk($id);

        $subCategoriesArray = QuestionCategory::getCategoriesArrayByParent($id);

        $questionsCriteria = new CdbCriteria();
        $questionsCriteria->with = [
            'categories' => [
                'condition' => 'categories.id = ' . $model->id,
            ],
        ];
        $questionsCriteria->order = 't.id DESC';

        $questions = Question::model()->findAll($questionsCriteria);

        $questionsDataProvider = new CArrayDataProvider($questions, [
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('view', [
            'model' => $model,
            'questionsDataProvider' => $questionsDataProvider,
            'subCategoriesArray' => $subCategoriesArray,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new QuestionCategory();

        $model->publish_date = date('Y-m-d');

        if (isset($_GET['parentId']) && $_GET['parentId']) {
            $model->parentId = (int) $_GET['parentId'];
        }

        if (isset($_POST['App_models_QuestionCategory'])) {
            $model->attributes = $_POST['App_models_QuestionCategory'];

            // загрузка иконки
//            $model->fileIcon = CUploadedFile::getInstance($model, 'fileIcon');
//            if ($model->fileIcon){
//                if (!$model->uploadIcon()){
//                    $this->redirect(['update', 'id' => $model->id]);
//                }
//            }// раскоментировать, если необходимо и при создании категории грузить иконку.

            if ($model->parentId) {
                $parent = QuestionCategory::model()->findByPk($model->parentId);
                if (!$parent) {
                    throw new CHttpException(400, 'Родительский элемент не найден');
                }
                // прикрепим категорию к родительской (в иерархии)
                $model->appendTo($parent);
            }
            $model->publish_date = DateHelper::invertDate($model->publish_date);
            if ($model->saveNode()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // для работы визуального редактора подключим необходимую версию JQuery
        $scriptMap = Yii::app()->clientScript->scriptMap;
        $scriptMap['jquery.js'] = '/js/jquery-1.8.3.min.js';
        $scriptMap['jquery.min.js'] = '/js/jquery-1.8.3.min.js';
        Yii::app()->clientScript->scriptMap = $scriptMap;

        $model->publish_date = DateHelper::invertDate($model->publish_date);

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
        $oldImagePath = $model->getImagePath();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['App_models_QuestionCategory'])) {
            $model->attributes = $_POST['App_models_QuestionCategory'];
            $now = new DateTime();
            $model->publish_date = (new DateTime($model->publish_date))->setTime($now->format('H'), $now->format('i'), $now->format('s'))->format('Y-m-d H:i:s');

            if ($model->parentId) {
                $parent = QuestionCategory::model()->findByPk($model->parentId);
                if (!$parent) {
                    throw new CHttpException(400, 'Родительский элемент не найден');
                }
                // прикрепим категорию к родительской (в иерархии)
                $model->moveAsLast($parent);
            }

            $imageFile = CUploadedFile::getInstance($model, 'imageFile');

            if ($imageFile && 0 == $imageFile->getError()) { // если файл нормально загрузился
                // определяем имя файла для хранения на сервере
                $newFileName = md5($imageFile->getName() . $imageFile->getSize()) . '.' . $imageFile->getExtensionName();
                Yii::app()->ih
                    ->load($imageFile->tempName)
                    ->adaptiveThumb(850, 570)
                    ->save(Yii::getPathOfAlias('webroot') . QuestionCategory::IMAGES_DIRECTORY . '/' . $newFileName);
                $model->image = $newFileName;
            }

            // загрузка иконки
            $model->fileIcon = CUploadedFile::getInstance($model, 'fileIcon');
            if ($model->fileIcon){
                if (!$model->uploadIcon()){
                    $this->redirect(['update', 'id' => $model->id]);
                }
            }




            $attachment = CUploadedFile::getInstance($model, 'attachments');

            if ($attachment && false == $attachment->getHasError()) {
                $attachmentFile = new File();
                $attachmentFile->objectType = File::ITEM_TYPE_OBJECT_CATEGORY;
                $attachmentFile->objectId = $model->id;
                $attachmentFile->name = $attachment->name;
                $attachmentFile->filename = $attachmentFile->createFileName($attachment);
                $attachmentFileFolder = $attachmentFile->createFolderFromFileName();

                if (!is_dir(Yii::getPathOfAlias('webroot') . $attachmentFileFolder)) {
                    @mkdir(Yii::getPathOfAlias('webroot') . $attachmentFileFolder, 0777, true);
                }

                if ($attachment->saveAs(Yii::getPathOfAlias('webroot') . $attachmentFileFolder . '/' . $attachmentFile->filename)) {
                    $attachmentFile->save();
                }
            }

            if ($model->saveNode()) {
                // Если загрузили новую картинку вместо старой, удалим старую
                if ($oldImagePath && $newFileName) {
                    @unlink(Yii::getPathOfAlias('webroot') . $oldImagePath);
                }
                // при изменении категории, заново найдем путь до нее
                $model->getUrl(true);

                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // для работы визуального редактора подключим необходимую версию JQuery
//        $scriptMap = Yii::app()->clientScript->scriptMap;
//        $scriptMap['jquery.js'] = '/js/jquery-1.8.3.min.js';
//        $scriptMap['jquery.min.js'] = '/js/jquery-1.8.3.min.js';
//        Yii::app()->clientScript->scriptMap = $scriptMap;

        $model->publish_date = DateHelper::invertDate((new DateTime($model->publish_date))->format('Y-m-d'));

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
        $this->loadModel($id)->deleteNode();

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        /*
         * Извлекаем список категорий с иерархией
         * SELECT c.id, c.name, LENGTH(c.description1),  LENGTH(c.description2), LENGTH(c.seoTitle), LENGTH(c.seoDescription), LENGTH(c.seoKeywords), LENGTH(c.seoH1), c.isDirection, child.id, child.name, LENGTH(child.description1),  LENGTH(child.description2), LENGTH(child.seoTitle), LENGTH(child.seoDescription), LENGTH(child.seoKeywords), LENGTH(child.seoH1), child.isDirection
            FROM `100_questionCategory` c
            LEFT JOIN `100_questionCategory` child ON child.parentId = c.id
            ORDER BY c.name
            LIMIT 100
         */

        $parentId = (int) Yii::app()->request->getParam('parentId');

        $categoriesArray = QuestionCategory::getCategoriesArrayByParent($parentId);

        // Найдем количество категорий, у которых отсутствует описание
        $emptyCategoriesRow = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{questionCategory}}')
            ->where("description1='' AND description2=''")
            ->queryRow();

        $emptyCategoriesCount = (is_array($emptyCategoriesRow)) ? $emptyCategoriesRow['counter'] : 0;

        // Найдем количество категорий, у которых отсутствует описание
        $totalCategoriesRow = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{questionCategory}}')
            ->queryRow();

        $totalCategoriesCount = (is_array($totalCategoriesRow)) ? $totalCategoriesRow['counter'] : 0;

        $this->render('index', [
            'categoriesArray' => $categoriesArray,
            'emptyCategoriesCount' => $emptyCategoriesCount,
            'totalCategoriesCount' => $totalCategoriesCount,
        ]);
    }

    /**
     * Выводит список URL категорий, которые заполнены.
     */
    public function actionShowActiveUrls()
    {
        $categories = QuestionCategory::model()->findAll('description1!="" OR description2!=""');

        $this->render('showActiveUrls', [
            'categories' => $categories,
        ]);
    }

    /**
     * Временный метод для показа (контроля) иерархии категорий.
     */
    public function actionIndexHierarchy()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.root, t.lft'; // or 't.root, t.lft' for multiple trees
        $categories = QuestionCategory::model()->findAll($criteria);
        $level = 0;

        foreach ($categories as $n => $category) {
            if ($category->level == $level) {
                echo CHtml::closeTag('li') . "\n";
            } elseif ($category->level > $level) {
                echo CHtml::openTag('ul') . "\n";
            } else {
                echo CHtml::closeTag('li') . "\n";

                for ($i = $level - $category->level; $i; --$i) {
                    echo CHtml::closeTag('ul') . "\n";
                    echo CHtml::closeTag('li') . "\n";
                }
            }

            echo CHtml::openTag('li');
            echo CHtml::encode($category->name);
            $level = $category->level;
        }

        for ($i = $level; $i; --$i) {
            echo CHtml::closeTag('li') . "\n";
            echo CHtml::closeTag('ul') . "\n";
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new QuestionCategory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['App_models_QuestionCategory'])) {
            $model->attributes = $_GET['App_models_QuestionCategory'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function actionTranslit()
    {
        $categories = QuestionCategory::model()->findAll();
        foreach ($categories as $cat) {
            if ('' == $cat->alias) {
                $cat->alias = StringHelper::translit($cat->name);
                $cat->save();
            }
        }

        $towns = Town::model()->findAllByAttributes(['alias' => '']);
        foreach ($towns as $town) {
            if ('' == $town->alias) {
                $town->alias = StringHelper::translit($town->name) . '-' . $town->id;
                echo $town->name . ' - ' . $town->alias . '<br />';
                if (!$town->save()) {
                    StringHelper::printr($town->errors);
                }
            }
        }
    }

    public function actionAjaxGetList()
    {
        $term = addslashes(CHtml::encode($_GET['term']));

        $arr = [];

        $condition = "name LIKE '%" . $term . "%'";
        $params = ['limit' => 5];

        $allCats = QuestionCategory::model()->cache(10000)->findAllByAttributes([], $condition, $params);

        foreach ($allCats as $cat) {
            $arr[] = [
                'value' => CHtml::encode($cat->name),
                'id' => $cat->id,
            ];
        }
        echo CJSON::encode($arr);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return QuestionCategory the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = QuestionCategory::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param QuestionCategory $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'question-category-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    // выводит список категорий-направлений с их иерархией
    public function actionDirections()
    {
        $directions = QuestionCategory::getDirections(true, true);

        return $this->render('directions', [
            'directions' => $directions,
        ]);
    }

    /**
     * Установка направлению нового родителя через AJAX запрос
     */
    public function actionSetDirectionParent()
    {
        $directionId = (int) $_POST['id'];
        $parentId = (int) $_POST['parentId'];

        // проверим, что направление и новый родитель существуют и являются направлениями
        $direction = QuestionCategory::model()->findByPk($directionId);
        $parent = QuestionCategory::model()->findByPk($parentId);

        if (!$direction || !($parent || 0 == $parentId)) {
            echo json_encode(['code' => 404, 'message' => 'Направление или новый родитель не найдены']);
            Yii::app()->end();
        }

        if (!$direction->isDirection || !($parent->isDirection || 0 == $parentId)) {
            echo json_encode(['code' => 404, 'message' => 'Категория или новый родитель не являются направлением']);
            Yii::app()->end();
        }

        if (Yii::app()->db->createCommand()->update('{{questionCategory}}', ['parentDirectionId' => $parentId], 'id=' . $directionId)) {
            echo json_encode(['code' => 0, 'message' => 'Категория обновлена. Перезагрузите страницу']);
            Yii::app()->end();
        }

        $campaign = Campaign::model()->findByPk($campaignId);
    }
}
