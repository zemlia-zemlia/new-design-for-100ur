<?php

/**
 * Модель для работы с формой поиска вопросов
 */
class QuestionSearch extends CFormModel
{
    public $townId; // искать вопросы из города
    public $noAnswers = true; // искать вопросы без ответов (true/false)
    public $today = false; // искать вопросы за сегодня (true/false)
    public $sameRegion = 0; // искать вопросы из городов того же региона (true/false)
    public $limit = 100; // search results limit
    public $townName; // для вывода в форме имени города
    public $myCats; // для вывода вопросов по направлениям юриста
    public $myTown = 0; // для вывода вопросов по своему городу
    public $payed = 0; // для вывода платных вопросов




    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return array(
            'townId'        =>  'город',
            'noAnswers'     =>  'без ответов',
            'today'         =>  'за 24 часа',
            'myCats'        =>  'по моей специальности',
            'sameRegion'    =>  'включая соседние города',
            'myTown'        =>  'из моего города',
            'payed'         =>  'платные вопросы',
        );
    }
    
    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('townId, today, noAnswers, sameRegion, myCats, myTown, payed', 'numerical', 'integerOnly'=>true),
        );
    }

        
    /**
     * функция поиска
     * 
     * @return array массив записей из базы
     */
    public function search()
    {
//        CustomFuncs::printr($this->attributes);
        
        // соберем поисковый запрос
        $searchCommand = Yii::app()->db->createCommand();
        
        $searchCommand->
                select('q.title, q.publishDate, t.name townName, q.id, q.authorName, COUNT(a.id) answersCount')
                ->from('{{question q}}')
                ->leftJoin('{{town}} t', 't.id=q.townId')
                ->leftJoin('{{answer}} a', 'a.questionId = q.id')
                ->group('q.id')
                ->order('q.publishDate DESC')
                ->limit($this->limit);
        

        $whereCondition = array('and', array('in', 'q.status', array(Question::STATUS_CHECK, Question::STATUS_PUBLISHED)));
        $whereParams = array();
        $groupArray = array();
        $groupArray[] = 'q.id';
                
        if($this->townId) {
            $whereCondition[] = 'q.townId=:townId';
            $whereParams += array(':townId'  =>  $this->townId);
        }
        
        if($this->myTown) {
            $whereCondition[] = 'q.townId=:townId';
            $whereParams += array(':townId'  =>  Yii::app()->user->townId);
        }
                
        if($this->today) {
            $whereCondition[]= 'DATE(q.publishDate)>NOW()-INTERVAL 24 HOUR';
        }
        
        if($this->noAnswers) {
            $whereCondition[] = 'a.id IS NULL';
        }

        if($this->payed) {
            $whereCondition[] = 'q.price>0 AND payed=1';
        }
        
        if($this->sameRegion) {
            $myTownId = Yii::app()->user->townId;
            $myTown = Town::model()->findByPk($myTownId);
            $closeTowns = $myTown->getCloseTowns();
            
            if(sizeof($closeTowns)) {
                $closeTownsStr = '';
                $closeTownsIds = array();
                foreach($closeTowns as $town) {
                    $closeTownsIds[] = $town->id;
                }
                $closeTownsStr = implode(',', $closeTownsIds);
                $whereCondition[] = 't.id IN(' . $closeTownsStr . ')';
            }
        }
        
        if($this->myCats && Yii::app()->user->categories) {
            // если выбрано "моя специальность"
            $searchCommand = $searchCommand->leftJoin("{{question2category}} q2c", "q.id = q2c.qId");
            // получаем массив объектов-категорий
            $myCategories = Yii::app()->user->categories;
            $myCategoriesStr = '';
            $myCategoriesIds = array();
            foreach($myCategories as $cat) {
                $myCategoriesIds[] = $cat->id;
            }
            $myCategoriesStr = implode(',', $myCategoriesIds);
            $whereCondition[] = 'cId IN(' . $myCategoriesStr . ')';
        }
        
        $searchCommand->where($whereCondition, $whereParams);
        $searchCommand->group($groupArray);
        
//        CustomFuncs::printr($whereCondition);
//        CustomFuncs::printr($searchCommand->text);
//        exit;
        
        
        $searchRows = $searchCommand->queryAll();
//        CustomFuncs::printr($searchRows);

        return $searchRows;
        
        
    }
}

