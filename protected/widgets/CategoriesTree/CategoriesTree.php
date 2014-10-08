<?php
class CategoriesTree extends CWidget
{
    public $template = 'tree'; // представление виджета по умолчанию
    public $cacheTime = 300; // по умолчанию кэшируем  на 5 минут
    
    public function run()
    {
        
        $dataProvider=new CActiveDataProvider(QuestionCategory::model()->cache($this->cacheTime, NULL, 3), array(
            'criteria'      =>  array(
                'order'     =>  't.name',
                'with'      =>  'children',
                'condition' =>  't.parentId=0',
            ),
            'pagination'    =>  array(
                        'pageSize'=>400,
                    ),
        ));
        
        $this->render($this->template, array('dataProvider'=>$dataProvider));
    }
}
?>
