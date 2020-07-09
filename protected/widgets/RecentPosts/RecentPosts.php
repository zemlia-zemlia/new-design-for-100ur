<?php

// виджет для вывода списка ссылок на посты блога

use App\models\Post;

class RecentPosts extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $category = null;
    public $number = 3; // число постов
    public $order = 'views'; // порядок выборки (comments | views | fresh_views)
    public $intervalDays = 150; // за какое число дней искать свежие посты

    public function run()
    {
//        $recentPosts = Post::getRecentPosts($this->category, $this->number, $this->order, $this->intervalDays);
        $recentPosts = Post::model()->findAll(['limit' => $this->number, 'order' => 'id DESC']);
//        CVarDumper::dump( $recentPosts,5,true);die;
        $this->render($this->template, [
            'recentPosts' => $recentPosts,
        ]);
    }
}
