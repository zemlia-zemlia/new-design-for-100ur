<?php
class QuestionSearch extends CFormModel
{
    public $townId; // искать вопросы из города
    public $noAnswers = true; // искать вопросы без ответов (true/false)
    public $today = false; // искать вопросы за сегодня (true/false)
    public $sameRegion = false; // искать вопросы из городов того же региона (true/false)
    public $limit = 100; // search results limit
    public $townName; // для вывода в форме имени города




    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
            return array(
                'townId'        =>  'город',
                'noAnswers'     =>  'без ответов',
                'today'         =>  'за 24 часа',
                'sameRegion'    =>  'из соседних городов',
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
			array('townId, today, noAnswers, sameRegion', 'numerical', 'integerOnly'=>true),
		);
	}

        
    // функция поиска
    public function search()
    {
//        CustomFuncs::printr($this->attributes);
        
        // соберем поисковый запрос
        $searchCommand = Yii::app()->db->createCommand();
        
        $searchCommand->
                select('q.title, q.publishDate, t.name townName, q.id, q.authorName')
                ->from('{{question q}}')
                ->leftJoin('{{town}} t', 't.id=q.townId')
                ->leftJoin('{{answer}} a', 'a.questionId = q.id')
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
                
        if($this->today) {
            $whereCondition[]= 'DATE(q.publishDate)>NOW()-INTERVAL 24 HOUR';
        }
        
        if($this->noAnswers) {
            $whereCondition[] = 'a.id IS NULL';
            
        }
        
        if($this->sameRegion) {
//            $searchCommand->leftJoin('{{town}} t', 't.id = q.townId');
//            $whereCondition[] = 't.ocrug = :region';
//            $whereParams += array(':region'  =>  $this->region);
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

