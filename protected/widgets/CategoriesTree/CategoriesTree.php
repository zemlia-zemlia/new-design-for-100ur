<?php
class CategoriesTree extends CWidget
{
    public $template = 'tree'; // представление виджета по умолчанию
    public $cacheTime = 300; // по умолчанию кэшируем  на 5 минут
    
    public function run()
    {
                
        // вытаскиваем из базы список категорий верхнего уровня
        $topCategories = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('id, alias, name')
                ->from('{{questionCategory}}')
                ->where('parentId=0')
                ->order('name')
                ->queryAll();
        
        $this->render($this->template, array('topCategories' =>  $topCategories));
    }
}
?>
