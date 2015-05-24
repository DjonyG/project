<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();

    public static $counter = 0;


    public function beforeAction($action)
    {
        self::$counter++;

        if($this->route == 'site/index' && Yii::app()->request->isSecureConnection) {
            Yii::app()->request->redirect('http://' . Yii::app()->getRequest()->serverName
                . Yii::app()->getRequest()->requestUri);
        }

        if(Yii::app()->user->isImpersonate() && self::$counter == 1) {
            UserMessage::add('Impersonate mode! &ensp; <a href="' . Yii::app()->createUrl('/auth/logout') . '">Return</a>', UserMessage::TYPE_WARNING);
        }

        return parent::beforeAction($action);
    }


    public function render($view,$data=null,$return=false, $processOutput=false)
    {
        if(Yii::app()->request->isAjaxRequest) {
            parent::renderPartial($view, $data, $return, $processOutput);
        } else {
            parent::render($view, $data, $return);
        }
    }

    public function redirect($url, $terminate = true, $statusCode = 302)
    {
        if(Yii::app()->request->isAjaxRequest) {
            header('Complete', true, 201);
            if(is_array($url)) {
                $route=isset($url[0]) ? $url[0] : '';
                $url=$this->createUrl($route,array_splice($url,1));
            }
            echo json_encode(array('url'=>$url));
            exit;
        }
        parent::redirect($url, $terminate, $statusCode);
    }

    public function redirectReturn($url, $terminate = true, $statusCode = 302)
    {
        $this->redirect(Yii::app()->user->returnUrl?:$url, $terminate, $statusCode);
    }

    public function closePopUp()
    {
        if(Yii::app()->request->isAjaxRequest) {
            header('Complete', true, 202);
            exit;
        }
    }
}