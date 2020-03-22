<?php

/**
 * Страницы раздела API вебмастера.
 */
class ApiController extends Controller
{
    public $layout = '//admin/main';

    /**
     * Описание работы API.
     */
    public function actionIndex()
    {
        echo $this->render('index');
    }
}
