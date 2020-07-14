<?php

use App\models\Question;
use App\models\QuestionCategory;

class QuestionCategoryController extends Controller
{
    public $layout = '//frontend/index';

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
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'view', 'alias'],
                'users' => ['*'],
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
        $model = QuestionCategory::model()->with('parent', 'children')->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'Категория не найдена');
        }

        // если обратились по id , делаем редирект на ЧПУ
        $this->redirect(['questionCategory/alias', 'name' => CHtml::encode($model->alias)], true, 301);

        $questionsCriteria = new CdbCriteria();
        $questionsCriteria->addColumnCondition(['categoryId' => $model->id]);
        $questionsCriteria->addColumnCondition(['status' => Question::STATUS_PUBLISHED]);
        $questionsCriteria->order = 'id DESC';

        $questionsDataProvider = new CActiveDataProvider(Question::class, [
            'criteria' => $questionsCriteria,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $questionModel = new Question();

        $this->render('view', [
            'model' => $model,
            'questionsDataProvider' => $questionsDataProvider,
            'questionModel' => $questionModel,
        ]);
    }

    /**
     * Показ категории вопроса по ее псевдониму.
     *
     * @param strung $level1 псевдоним категории
     *
     * @throws CHttpException
     */
    public function actionAlias()
    {
        $categoriesPerPage = 15;

        $name = CHtml::encode($_GET['name']);

        // если в урле заглавные буквы, редиректим на вариант с маленькими
        if (preg_match('/[A-Z]/', $name)) {
            $this->redirect(['questionCategory/alias', 'name' => strtolower($name)], true, 301);
        }

        $model = QuestionCategory::model()->findByAttributes(['alias' => CHtml::encode($name)]);

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

        // все предки категории
        $ancestors = $model->ancestors()->findAll();
        // все потомки
        $childrenRaw = $model->children();
        $children = [];

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
            for ($i = 0; $i < $categoriesPerPage; ++$i) {
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

        $neighboursPrev = [];
        $neighboursNext = [];

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
        if (0 == sizeof($questions)) {
            $questions = $this->findQuestions($model, false);
        }

        $newQuestionModel = new Question();

        $this->render('view', [
            'model' => $model,
            'questions' => $questions,
            'newQuestionModel' => $newQuestionModel,
            'childrenCategories' => $childrenCategories,
            'parentCategory' => $parentCategory,
            'neighboursPrev' => $neighboursPrev,
            'neighboursNext' => $neighboursNext,
            'ancestors' => $ancestors,
            'children' => $children,
        ]);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider(QuestionCategory::class, [
            'criteria' => [
                'order' => 't.name',
                /*'with' => 'children',*/
                'condition' => 't.parentId=0',
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
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

    /* возвращает массив вопросов
     * $thisCategory = true - вопросы данной категории
     * $thisCategory = false - новые вопросы без привязки к категории
     */

    protected function findQuestions($category, $thisCategory = true)
    {
        $questionsCommand = Yii::app()->db->createCommand()
                ->select('q.id id, q.publishDate date, q.title title, a.id answerId')
                ->from('{{question}} q')
                ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                ->where('q.status IN (:status1, :status2)', [':status1' => Question::STATUS_PUBLISHED, ':status2' => Question::STATUS_CHECK])
                ->limit(20)
                ->order('q.publishDate DESC');

        if (true === $thisCategory) {
            $questionsCommand = $questionsCommand->join('{{question2category}} q2c', 'q2c.qId=q.id AND q2c.cId=:catId', [':catId' => $category->id]);
        }

        $questions = $questionsCommand->queryAll();

        $questionsArray = [];

        foreach ($questions as $question) {
            $questionsArray[$question['id']]['id'] = $question['id'];
            $questionsArray[$question['id']]['date'] = $question['date'];
            $questionsArray[$question['id']]['title'] = $question['title'];
            if (!is_null($question['answerId'])) {
                ++$questionsArray[$question['id']]['counter'];
            }
        }

        return $questionsArray;
    }
}
