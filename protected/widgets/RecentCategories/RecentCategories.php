<?php

// виджет для вывода списка ссылок на категории

class RecentCategories extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $number = 3; // число категорий
    public $rootId; // id раздела, в котором нужно выбрать категории
    public $hasPicture = true; // найти только категории с заглавной картинкой
    public $title = null; // Заголовок блока (HTML)
    public $columns = 2; // количество колонок при выводе

    public function run()
    {
        $recentCategories = QuestionCategory::getRecentCategories($this->number, $this->hasPicture, $this->rootId);

        $this->render($this->template, [
            'recentCategories' => $recentCategories,
            'title' => $this->title,
            'columns' => $this->columns,
        ]);
    }
}

?>