<?php

// виджет для вывода списка ссылок на посты блога

class RecentPosts extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $category = null;
    public $number = 4; // число постов
    public $order = 'views'; // порядок выборки (comments | views | fresh_views)
    public $intervalDays = 150; // за какое число дней искать свежие посты

    public function run()
    {
        $recentPosts = Post::getRecentPosts($this->category, $this->number, $this->order, $this->intervalDays);

        $this->render($this->template, array(
            'recentPosts'  =>  $recentPosts,
        ));
    }
}
