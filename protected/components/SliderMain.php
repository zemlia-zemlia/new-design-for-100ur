<?php
class SliderMain extends CWidget {

	public $title = 'Title CWidget'; // Заголовок слайдера
	public $idcat = array(1); // Массив категорий
	public $limit = 6; // Сколько выгружаем

    public function run() {    	$criteria=new CDbCriteria;
    	$criteria->condition = 'on_main=1 AND status=1';
    	$criteria->addInCondition('cat_id',$this->idcat);
    	$criteria->limit = $this->limit;
    	$criteria->order = 'create_time DESC';
    	//$params = array(':limit'=>1);
    	//$criteria->params = $params;

        $items = Articles::model()->findAll($criteria);

        $this->render('sliderMain',array('title'=>$this->title,'idcat'=>$this->idcat,'limit'=>$this->limit,'items'=>$items));
    }
}
?>