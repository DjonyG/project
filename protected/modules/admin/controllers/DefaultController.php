<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
        $this->layout = '/layouts/column2';
		$this->render('index');
	}
}