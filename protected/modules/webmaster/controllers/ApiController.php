<?php

/**
 * Страницы раздела API вебмастера
 */
class ApiController extends Controller
{
    public $layout='//frontend/webmaster';
    
    /**
     * Описание работы API
     */
    public function actionIndex()
    {
        echo $this->render('index');
    }
}
