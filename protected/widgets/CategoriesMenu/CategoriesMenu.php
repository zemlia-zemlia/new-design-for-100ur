<?php
/**
 * Виджет, выводящий соседей и потомков категории
 * Class CategoriesMenu.
 */
class CategoriesMenu extends CWidget
{
    public $template = 'tree'; // представление виджета по умолчанию
    public $cacheTime = 300; // по умолчанию кэшируем  на 5 минут
    /** @var QuestionCategory */
    public $category; // текущая категория, для которой строится меню

    public function run()
    {
        // все потомки
        $children = $this->category->children();

        // родитель | NULL
        $parent = $this->category->parent();
        $neighbours = (!is_null($parent)) ? $parent->children() : [];

        return $this->render($this->template, [
            'category' => $this->category,
            'children' => $children,
            'neighbours' => $neighbours,
        ]);
    }
}
