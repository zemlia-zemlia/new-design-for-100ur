<?php

class DefaultController extends Controller
{
    public $layout='//admin/main';

    public function actionIndex()
	{
		$this->render('index');
	}
}