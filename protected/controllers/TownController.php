<?php

class TownController extends Controller
{
    public $layout = '//frontend/question';

    /**
     * @return array action filters
     */
    public function filters()
    {
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
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'alias', 'aliasOld', 'ajaxGetList'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    // список городов
    public function actionIndex()
    {
        throw new CHttpException(404, 'Этой страницы больше не существует...');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $town = Town::model()->findByPk($id);
        if (!$town) {
            throw new CHttpException(404, 'Город не найден');
        }
        // если обратились по id города, делаем редирект на ЧПУ
        $this->redirect(array('town/alias', 'name' => $town->alias), true, 301);

        $criteria = new CDbCriteria;
        $criteria->order = 't.id desc';
        //$criteria->addColumnCondition(array('t.status IN (' . Question::STATUS_PUBLISHED . ', ' . Question::STATUS_CHECK . ')'));
        //$criteria->addCondition('t.townId IN (' . implode(',', $closeTownsIds) . ')');
        $criteria->with = array('categories', 'town', 'answersCount');

        $dataProvider = new CActiveDataProvider('Question', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 7,
            ),
        ));

        $questionModel = new Question();

        $this->render('view', array(
            'model' => $town,
            'dataProvider' => $dataProvider,
            'questionModel' => $questionModel,
        ));
    }

    /**
     * Вывод страницы города по алиасу
     *
     * @param type $name
     * @throws CHttpException
     */
    public function actionAlias($name)
    {
        $this->layout = "/frontend/catalog";
        
        $model = Town::model()->cache(60)->findByAttributes(array('alias' => CHtml::encode($name)));
        if (empty($model)) {
            throw new CHttpException(404, 'Город не найден');
        }

        if (isset($_GET['Question_page'])) {
            //die('try to redirect');
            return $this->redirect(array(
                'town/alias',
                'name' => $model->alias,
                'countryAlias' => $model->country->alias,
                'regionAlias' => $model->region->alias,
                    ), true, 301);
        }

        // при попытке обратиться по адресу типа town/alias/xxxx, переадресуем на адрес со страной и регионом
        if (!isset($_GET['regionAlias'])) {
            $this->redirect(array(
                'town/alias',
                'name' => $model->alias,
                'countryAlias' => $model->country->alias,
                'regionAlias' => $model->region->alias,
                    ), 301);
        }

        // найдем Id соседних городов
        $closeTowns = $model->getCloseTowns(100, 20);
        $closeTownsIds = array();
        foreach ($closeTowns as $t) {
            $closeTownsIds[] = $t->id;
        }

        // если в радиусе 100 километров есть города, добавим к выборке вопросов вопросы из соседних городов
        $questionsCloseTowns = array();
        if (sizeof($closeTownsIds)) {
            $questionsCloseTowns = Yii::app()->db->cache(300)->createCommand()
                    ->select('q.id id, q.publishDate date, q.title title, q.townId, COUNT(a.id) counter')
                    ->from('{{question}} q')
                    ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                    ->group('q.id')
                    ->where('(q.status=:status1 OR q.status=:status2) AND q.townId IN(' . implode(", ", $closeTownsIds) . ')', array(':status1' => Question::STATUS_PUBLISHED, ':status2' => Question::STATUS_CHECK))
                    ->limit(15)
                    ->order('q.publishDate DESC')
                    ->queryAll();
        }

        
        $questionModel = new Question();

        $regionId = $model->regionId;

        // массив соседних городов
        //$closeTowns = $model->getCloseTowns();
        // категории вопросов - направления
        $allDirections = QuestionCategory::getDirections(true);
        
        
        $criteria = new CDbCriteria;

        $criteria->order = "rating DESC, karma DESC";
        $criteria->with = array("settings", "town", "town.region", "categories", "answersCount");
        $criteria->addColumnCondition(array('active100' => 1));
        $criteria->addColumnCondition(array('avatar!' => ''));
        $criteria->addColumnCondition(array('t.townId' => $model->id));
        $criteria->addCondition("role = " . User::ROLE_JURIST);

        $yuristsDataProvider = new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        

        $this->render('view', array(
            'model' => $model,
            'questionModel' => $questionModel,
            'closeTowns' => $closeTowns,
            'allDirections' => $allDirections,
            'yuristsDataProvider'   =>  $yuristsDataProvider,
        ));
    }

    /**
     * метод для обработки старых адресов вида konsultaciya-yurista-voronezh
     * и редиректа на новые адреса городов
     * @deprecated
     */
    public function actionAliasOld($name)
    {
        $town = Town::model()->findByAttributes(array('alias' => $name));
        if (!$town) {
            throw new CHttpException(404, 'Страница города не найдена');
        }

        if (!($town->region && $town->country)) {
            throw new CHttpException(404, 'Страница города не найдена, страна и регион не определены');
        }

        $this->redirect(array(
            'town/alias',
            'name' => $town->alias,
            'countryAlias' => $town->country->alias,
            'regionAlias' => $town->region->alias,
                ), true, 301);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Town the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Town::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Town $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'town-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAjaxGetList()
    {
        $term = addslashes(CHtml::encode($_GET['term']));

        $arr = array();

//            SELECT t.id, t.name, r.name FROM `100_town` t
//            LEFT JOIN `100_region` r ON t.regionId=r.id
//            WHERE t.name LIKE "Мос%"
//            LIMIT 5

        $townsRows = Yii::app()->db->createCommand()
                ->select("t.id, t.name, r.name region")
                ->from("{{town}} t")
                ->leftJoin("{{region}} r", "t.regionId=r.id")
                ->where("t.name LIKE '" . $term . "%'")
                ->limit(5)
                ->queryAll();

        foreach ($townsRows as $town) {
            $arr[] = array(
                'value' => CHtml::encode($town['name'] . ' (' . $town['region'] . ')'),
                'id' => $town['id'],
            );
        }
        echo CJSON::encode($arr);
    }
}
