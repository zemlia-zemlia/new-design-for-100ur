<?php

/**
 * FAQ вебмастера
 */
class FaqController extends Controller
{
    public $layout='//lk/main';
    
    /**
     * Описание работы API
     */
    public function actionIndex()
    {
        echo $this->render('index');
    }
}
