<?php

/**
 * Страницы раздела API вебмастера.
 */
class ApiController extends Controller
{
    public $layout = '//lk/main';

    /**
     * Описание работы API.
     */
    public function actionIndex()
    {
        echo $this->render('index');
    }
}
