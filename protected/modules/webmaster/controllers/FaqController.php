<?php

/**
 * FAQ вебмастера
 */
class FaqController extends Controller {

    public $layout='//frontend/webmaster';
    
    /**
     * Описание работы API
     */
    public function actionIndex() {
        
        echo $this->render('index');
    }
}