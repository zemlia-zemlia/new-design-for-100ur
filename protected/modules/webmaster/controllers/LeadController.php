<?php

class LeadController extends Controller
{
    public $layout='//lk/main';
    
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        
        $criteria->with = "source";
        
        $mySourcesIds = array();
        $mySourcesIdsRows = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{leadsource}}')
                ->where('userId = :userId', array(':userId' => Yii::app()->user->id))
                ->queryAll();
        foreach ($mySourcesIdsRows as $row) {
            $mySourcesIds[] = $row['id'];
        }
        
        // Найдем лиды, которые пришли из источников, которые привязаны к текущему пользователю
        $criteria->order = 't.id DESC';
        $criteria->addInCondition('sourceId', $mySourcesIds);
        
        $dataProvider = new CActiveDataProvider('Lead', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    public function actionView($id)
    {
        $model = Lead::model()->findByPk($id);
        
        if (!$model) {
            throw new CHttpException(404, 'Лид не найден');
        }
        
        if ($model->source->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие лиды');
        }
        
        $this->render('view', array(
            'model' => $model,
        ));
    }
    
    /**
     * Добавление лида вебмастером
     */
    public function actionCreate()
    {
        $model = new Lead;

        // Проверим, есть ли источники у текущего пользователя. Если нет, перенаправим на создание источника
        $mySources = Leadsource::getSourcesArrayByUser(Yii::app()->user->id);
        if (sizeof($mySources) == 0) {
            $this->redirect(['source/create']);
        }
       
        if ($_GET['sourceId']) {
            $model->sourceId = (int)$_GET['sourceId'];
        }
        
        if (isset($_POST['Lead'])) {
            $model->attributes = $_POST['Lead'];
            $model->phone = PhoneHelper::normalizePhone($model->phone);
            
            // посчитаем цену покупки лида, исходя из города и региона
            $prices = $model->calculatePrices();
            if ($prices[0]) {
                $model->buyPrice = $prices[0];
            } else {
                $model->buyPrice = 0;
            }

            // уточним цену покупки лида с учетом коэффициента покупателя
            
            $priceCoeff = Yii::app()->user->priceCoeff; // коэффициент, на который умножается цена покупки лида

            $model->buyPrice = $model->buyPrice * $priceCoeff;
        
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model'     => $model,
        ));
    }
    
    public function actionPrices()
    {
        $this->render('prices', array());
    }
}
