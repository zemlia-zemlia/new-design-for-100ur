<?php

class QuestionCategoryController extends Controller {

    public $layout = '//frontend/question';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'alias'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = QuestionCategory::model()->with('parent', 'children')->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Категория не найдена');
        }

        // если обратились по id , делаем редирект на ЧПУ
        $this->redirect(array('questionCategory/alias', 'name' => CHtml::encode($model->alias)), true, 301);


        $questionsCriteria = new CdbCriteria;
        $questionsCriteria->addColumnCondition(array('categoryId' => $model->id));
        $questionsCriteria->addColumnCondition(array('status' => Question::STATUS_PUBLISHED));
        $questionsCriteria->order = 'id DESC';

        $questionsDataProvider = new CActiveDataProvider('Question', array(
            'criteria' => $questionsCriteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        $questionModel = new Question();

        $this->render('view', array(
            'model' => $model,
            'questionsDataProvider' => $questionsDataProvider,
            'questionModel' => $questionModel,
        ));
    }

    /**
     * Показ категории вопроса по ее псевдониму
     * 
     * @param strung $level1 псевдоним категории
     * @throws CHttpException
     */
    public function actionAlias() {
        $categoriesPerPage = 15;

        $name = CHtml::encode($_GET['name']);


        // если в урле заглавные буквы, редиректим на вариант с маленькими
        if (preg_match("/[A-Z]/", $name)) {
            $this->redirect(array('questionCategory/alias', 'name' => strtolower($name)), true, 301);
        }

//            $model = QuestionCategory::model()->with('parent','children')->findByAttributes(array('alias'=>CHtml::encode($name)));
        $model = QuestionCategory::model()->findByAttributes(array('alias' => CHtml::encode($name)));

        if (!$model) {
            throw new CHttpException(404, 'Категория не найдена');
        }

        /*
         * Редирект для решения проблемы дублей алиасов в конце адреса
         */
        $pageRightUrl = Yii::app()->createUrl('questionCategory/alias', $model->getUrl());
        $pageRealUrl = Yii::app()->urlManager->baseUrl . $_SERVER['REQUEST_URI'];

        if ($pageRightUrl != $pageRealUrl) {
            $this->redirect($pageRightUrl, true, 301);
        }

        /* если к категории не первого уровня обратились по имени, 
         * надо сделать редирект на полный адрес
         */
        if ($model->level > 1 && !$_GET['level2']) {
            //CustomFuncs::printr($model->getUrl());
            $this->redirect(Yii::app()->createUrl('questionCategory/alias', $model->getUrl()), true, 301);
        }


        // все предки категории
        $ancestors = $model->ancestors()->findAll();
        // все потомки
        $childrenRaw = $model->children();
        $children = array();

        if (sizeof($childrenRaw) > 0) {
            // нужно выбрать не более 15 дочерних категорий из всех
            // если категорий всего до 15, берем все
            if (sizeof($childrenRaw) <= $categoriesPerPage) {
                $children = $childrenRaw;
            } else {
                // если больше 15, вычислим шаг, с которым будем выбирать категории
                $step = sizeof($childrenRaw) / $categoriesPerPage; // шаг - дробное число
            }
            $prevChildId = 0;
            for ($i = 0; $i < $categoriesPerPage; $i++) {
                // чтобы не записать несколько раз один элемент
                if ($prevChildId == $childrenRaw[$i * floor($step)]->id) {
                    break;
                }
                $children[] = $childrenRaw[$i * floor($step)];
                $prevChildId = $childrenRaw[$i * floor($step)]->id;
            }
        }
        // родитель | NULL
        $parent = $model->parent();

        $neighboursPrev = array();
        $neighboursNext = array();

        if ($parent) {
            // ищем соседей с тем же родителем
            $neighbours = $parent->children();

            // найдем в массиве соседей позицию текущего элемента
            foreach ($neighbours as $neighbourNumber => $neighbour) {
                if ($neighbour->id === $model->id) {
                    $currentElementPosition = $neighbourNumber;
                }
            }

            foreach ($neighbours as $neighbourNumber => $neighbour) {
                if ($neighbourNumber > $currentElementPosition - 7 && $neighbourNumber < $currentElementPosition) {
                    $neighboursPrev[] = $neighbour;
                }
            }

            foreach ($neighbours as $neighbourNumber => $neighbour) {
                if ($neighbourNumber < $currentElementPosition + 7 && $neighbourNumber > $currentElementPosition) {
                    $neighboursNext[] = $neighbour;
                }
            }
        }

        $questions = $this->findQuestions($model);

        // если в категории не нашлось вопросов
        if (sizeof($questions) == 0) {
            $questions = $this->findQuestions($model, false);
        }

        $newQuestionModel = new Question();

        $this->render('view', array(
            'model' => $model,
            'questions' => $questions,
            'newQuestionModel' => $newQuestionModel,
            'childrenCategories' => $childrenCategories,
            'parentCategory' => $parentCategory,
            'neighboursPrev' => $neighboursPrev,
            'neighboursNext' => $neighboursNext,
            'ancestors' => $ancestors,
            'children' => $children,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider(QuestionCategory::model(), array(
            'criteria' => array(
                'order' => 't.name',
                /*'with' => 'children',*/
                'condition' => 't.parentId=0',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return QuestionCategory the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = QuestionCategory::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param QuestionCategory $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'question-category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /* возвращает массив вопросов
     * $thisCategory = true - вопросы данной категории
     * $thisCategory = false - новые вопросы без привязки к категории
     */

    protected function findQuestions($category, $thisCategory = true) {
        $questionsCommand = Yii::app()->db->createCommand()
                ->select('q.id id, q.publishDate date, q.title title, a.id answerId')
                ->from('{{question}} q')
                ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                ->where('q.status IN (:status1, :status2)', array(':status1' => Question::STATUS_PUBLISHED, ':status2' => Question::STATUS_CHECK))
                ->limit(20)
                ->order('q.publishDate DESC');



        if ($thisCategory === true) {
            $questionsCommand = $questionsCommand->join("{{question2category}} q2c", "q2c.qId=q.id AND q2c.cId=:catId", array(':catId' => $category->id));
        }


        $questions = $questionsCommand->queryAll();

        $questionsArray = array();

        foreach ($questions as $question) {
            $questionsArray[$question['id']]['id'] = $question['id'];
            $questionsArray[$question['id']]['date'] = $question['date'];
            $questionsArray[$question['id']]['title'] = $question['title'];
            if (!is_null($question['answerId'])) {
                $questionsArray[$question['id']]['counter'] ++;
            }
        }

        return $questionsArray;
    }

}
