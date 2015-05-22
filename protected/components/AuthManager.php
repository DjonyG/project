<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 23.07.12
 * Time: 16:40
 */

class AuthManager extends CPhpAuthManager
{
    public function init()
    {
        if($this->authFile === null){
            $this->authFile=Yii::getPathOfAlias('application.config').DIRECTORY_SEPARATOR.'roles.php';
        }

        parent::init();

        // Для гостей у нас и так роль по умолчанию guest.
        if(!Yii::app()->user->isGuest){
            $this->assign(Yii::app()->user->getRole(), Yii::app()->user->id);
        }
    }
}