<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));

        $this->registerAssets();
	}

    public function registerAssets()
    {
        $assetsPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias($this->id.'.assets'), false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($assetsPath.'/main.css');
        $cs->registerCssFile($assetsPath.'/dashboard.css');
        $cs->registerScriptFile($assetsPath.'/main.js');
        $cs->registerScriptFile($assetsPath.'/d3.min.js');
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
