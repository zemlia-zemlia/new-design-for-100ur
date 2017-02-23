<?php

// виджет для вывода списка ссылок на посты блога

class RecentPosts extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $category = NULL;
    public $number = 4; // число постов
    
    public function run()
    {
        $recentPosts = Post::getRecentPosts($this->category, $this->number);
        
        //CustomFuncs::printr($recentPosts);
        
        $this->render($this->template, array(
            'recentPosts'  =>  $recentPosts,
        ));
    }
}
?>