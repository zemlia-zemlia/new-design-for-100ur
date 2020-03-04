<?php

// виджет для вывода списка ссылок на посты блога

class Posts extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $category = null;

    public function run()
    {
        $popularPosts = Post::getPopularPosts($category);

        $this->render($this->template, [
            'popularPosts' => $popularPosts,
        ]);
    }
}
