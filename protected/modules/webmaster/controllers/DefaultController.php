<?php

class DefaultController extends Controller {

    public $layout='//frontend/webmaster';
    
    public function actionIndex() {
        
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
        $criteria->limit = 10;
        
        $leadsDataProvider = new CActiveDataProvider('Lead', array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
        
        // Найдем вопросы, которые пришли из источников, которые привязаны к текущему пользователю
        $questionCriteria = new CDbCriteria();
        $questionCriteria->limit = 10;
        $questionCriteria->order = 't.id DESC';
        $questionCriteria->addInCondition('sourceId', $mySourcesIds);
        
        $questionsDataProvider = new CActiveDataProvider('Question', array(
            'criteria' => $questionCriteria,
            'pagination' => false,
        ));
        
        $this->render('index', array(
            'dataProvider'          =>  $leadsDataProvider,
            'questionsDataProvider' =>  $questionsDataProvider,
        ));
    }
}
?>