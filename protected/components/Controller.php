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


    public function init()
    {

        if(strpos($_SERVER['SERVER_NAME'], 'www.') === 0) {
            $uri = $_SERVER['REQUEST_URI'];
            $domain = str_replace('www.', '', $_SERVER['SERVER_NAME']);
            $schema = Yii::app()->request->isSecureConnection? 'https' : 'http';
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$schema}://{$domain}{$uri}");
            exit();
        }

        if(Yii::app()->user->profile && !Yii::app()->user->isImpersonate()) {
            if(Yii::app()->user->isBanned == User::BAN_TYPE_FULL) {
                Yii::app()->user->logout(true);
                $this->redirect(['/site/login']);
            }

            $password_session = Yii::app()->session->get('password_hash', false);
            if((Yii::app()->user->profile && $password_session && $password_session !== Yii::app()->user->profile->password)
                || !Helper::isEqualNetwork(Yii::app()->session->get('session_ip'), Yii::app()->request->userHostAddress))
            {
                Yii::app()->user->logout();
                $this->redirect(['/site/login']);
            }
        }

        //Force logout not exist user
        if(!Yii::app()->user->isGuest && is_null(Yii::app()->user->profile)) {
            Yii::app()->user->logout();
        }
        parent::init();

        if(!Yii::app()->user->isImpersonate()) {
            if(!Yii::app()->user->isGuest) {
                Yii::app()->user->profile->updateLastInfo();
            }
        }
    }

    public function beforeAction($action)
    {
        if($this->route == 'site/index' && Yii::app()->request->isSecureConnection) {
            Yii::app()->request->redirect('http://' . Yii::app()->getRequest()->serverName
                . Yii::app()->getRequest()->requestUri);
        }

        if(Yii::app()->user->isImpersonate()) {
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