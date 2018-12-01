<?php

// виджет для вывода списка ссылок на категории

class RecentCategories extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $number = 3; // число категорий
    
    public function run()
    {
        $recentCategories = QuestionCategory::getRecentCategories($this->number);

        $this->render($this->template, array(
            'recentCategories' =>  $recentCategories,
        ));
    }
}
?>