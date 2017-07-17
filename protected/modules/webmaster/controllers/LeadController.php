<?php

class LeadController extends Controller {

    public $layout='//frontend/webmaster';
    
    public function actionIndex() {
        
        $criteria = new CDbCriteria;
        
        $criteria->with = "source";
        
        $mySourcesIds = array();
        $mySourcesIdsRows = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{leadsource100}}')
                ->where('userId = :userId', array(':userId' => Yii::app()->user->id))
                ->queryAll();
        foreach ($mySourcesIdsRows as $row) {
            $mySourcesIds[] = $row['id'];
        }
        
        // Найдем лиды, которые пришли из источников, которые привязаны к текущему пользователю
        $criteria->order = 't.id DESC';
        $criteria->addInCondition('sourceId', $mySourcesIds);
        
        $dataProvider = new CActiveDataProvider('Lead100', array(
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
        $model = Lead100::model()->findByPk($id);
        
        if(!$model) {
            throw new CHttpException(404, 'Лид не найден');
        }
        
        if($model->source->userId !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать чужие лиды');
        }
        
        $this->render('view', array(
            'model' => $model,
        ));
    }

}
